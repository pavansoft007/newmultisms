<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_settings extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('file'); // Load file helper for reading/writing files
        $this->load->config('api_credentials', TRUE); // Load the custom config file
        $this->load->model('provider_model'); // Load the Provider model
    }

    public function index() {
        if (!get_permission('api_provider_settings', 'is_view')) { // Changed permission name
            access_denied();
        }

        $this->data['sub_page'] = 'api_settings/index';
        $this->data['title'] = translate('service_provider_settings'); // Changed title
        $this->data['providers'] = $this->provider_model->get_all_providers();
        // The old api_key from config can still be loaded if needed for a separate section
        $this->data['global_api_key'] = $this->config->item('api_key', 'api_credentials');
        $this->load->view('layout/index', $this->data);
    }

    // Method to show the add provider form
    public function add() {
        if (!get_permission('api_provider_settings', 'is_add')) {
            access_denied();
        }
        $this->data['sub_page'] = 'api_settings/add_provider'; // New view for adding
        $this->data['title'] = translate('add_service_provider');
        $this->load->view('layout/index', $this->data);
    }

    // Method to process the add provider form
    public function create() {
        if (!get_permission('api_provider_settings', 'is_add')) {
            access_denied();
        }

        $this->_set_provider_validation_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->data['validation_error'] = true;
            $this->add(); // Show add form again with errors
        } else {
            $data = $this->_get_provider_data_from_post();
            $this->provider_model->add_provider($data);
            set_alert('success', translate('information_has_been_saved_successfully'));
            redirect(base_url('api_settings/index'));
        }
    }

    // Method to show the edit provider form
    public function edit($id) {
        if (!get_permission('api_provider_settings', 'is_edit')) {
            access_denied();
        }
        $this->data['provider'] = $this->provider_model->get_provider_by_id($id);
        if (!$this->data['provider']) {
            set_alert('error', translate('provider_not_found'));
            redirect(base_url('api_settings/index'));
        }
        $this->data['sub_page'] = 'api_settings/edit_provider'; // New view for editing
        $this->data['title'] = translate('edit_service_provider');
        $this->load->view('layout/index', $this->data);
    }

    // Method to process the edit provider form
    public function update($id) {
        if (!get_permission('api_provider_settings', 'is_edit')) {
            access_denied();
        }

        $this->_set_provider_validation_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->data['validation_error'] = true;
            $this->data['provider'] = (object)$this->input->post(); // Repopulate form with submitted data
            $this->data['provider']->id = $id; // Keep the ID
             $this->data['sub_page'] = 'api_settings/edit_provider';
            $this->data['title'] = translate('edit_service_provider');
            $this->load->view('layout/index', $this->data);
        } else {
            $data = $this->_get_provider_data_from_post();
            $this->provider_model->update_provider($id, $data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('api_settings/index'));
        }
    }
    
    // Method to delete a provider
    public function delete($id) {
        if (!get_permission('api_provider_settings', 'is_delete')) {
            access_denied();
        }
        $provider = $this->provider_model->get_provider_by_id($id);
        if (!$provider) {
            set_alert('error', translate('provider_not_found'));
        } else {
            $this->provider_model->delete_provider($id);
            set_alert('success', translate('information_has_been_deleted_successfully'));
        }
        redirect(base_url('api_settings/index'));
    }

    // Helper method to set validation rules for provider form
    private function _set_provider_validation_rules() {
        $this->form_validation->set_rules('service_type', translate('service_type'), 'trim|required');
        $this->form_validation->set_rules('provider_name', translate('provider_name'), 'trim|required');
        $this->form_validation->set_rules('api_key', translate('api_key'), 'trim');
        $this->form_validation->set_rules('api_secret', translate('api_secret'), 'trim');
        $this->form_validation->set_rules('api_url', translate('api_url'), 'trim|valid_url');
        $this->form_validation->set_rules('username', translate('username'), 'trim');
        $this->form_validation->set_rules('password', translate('password'), 'trim');
        $this->form_validation->set_rules('other_config_json', translate('other_config_json'), 'trim|callback_validate_json');
        // is_active will be handled by checking if the POST variable is set
    }

    // Helper method to get provider data from POST
    private function _get_provider_data_from_post() {
        $data = array(
            'service_type' => $this->input->post('service_type'),
            'provider_name' => $this->input->post('provider_name'),
            'api_key' => $this->input->post('api_key'),
            'api_secret' => $this->input->post('api_secret'),
            'api_url' => $this->input->post('api_url'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'other_config_json' => $this->input->post('other_config_json'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
        );
        return $data;
    }

    // Callback function to validate JSON
    public function validate_json($str) {
        if (empty($str)) {
            return TRUE; // Allow empty JSON
        }
        json_decode($str);
        if (json_last_error() == JSON_ERROR_NONE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('validate_json', 'The {field} field must contain a valid JSON string.');
            return FALSE;
        }
    }

    // This 'save' method is for the global API key in api_credentials.php
    // It can be kept if it serves a separate purpose, or removed if this new system replaces it.
    public function save_global_api_key() { // Renamed to avoid conflict
        if (!get_permission('global_api_key_settings', 'is_edit')) { // Assuming a different permission
            access_denied();
        }

        $this->form_validation->set_rules('global_api_key_field', translate('global_api_key'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Need a way to show errors for this specific form, perhaps on the same page or a dedicated one.
            // For simplicity, redirecting back with an error.
            set_alert('error', validation_errors());
            redirect(base_url('api_settings/index'));
        } else {
            $new_api_key = $this->input->post('global_api_key_field');
            $config_path = APPPATH . 'config/api_credentials.php';
            $config_file_content = read_file($config_path);

            if ($config_file_content !== FALSE) {
                $old_api_key_line = '$config[\'api_key\'] = \'' . $this->config->item('api_key', 'api_credentials') . '\';';
                $new_api_key_line = '$config[\'api_key\'] = \'' . $new_api_key . '\';';
                $updated_config_content = str_replace($old_api_key_line, $new_api_key_line, $config_file_content);

                if (write_file($config_path, $updated_config_content)) {
                    set_alert('success', translate('global_api_key_has_been_saved_successfully'));
                } else {
                    set_alert('error', translate('error_writing_to_config_file'));
                }
            } else {
                set_alert('error', translate('error_reading_config_file'));
            }
            redirect(base_url('api_settings/index'));
        }
    }
}