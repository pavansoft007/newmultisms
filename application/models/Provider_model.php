<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provider_model extends CI_Model {

    private $table_name = 'service_providers';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get a provider's details by service type and provider name.
     *
     * @param string $service_type e.g., 'whatsapp', 'sms'
     * @param string $provider_name e.g., 'Aisensy', 'Twilio'
     * @return object|null The provider object or null if not found or not active.
     */
    public function get_active_provider_by_name($service_type, $provider_name) {
        $this->db->where('service_type', $service_type);
        $this->db->where('provider_name', $provider_name);
        $this->db->where('is_active', 1);
        $query = $this->db->get($this->table_name);
        return $query->row();
    }

    /**
     * Get a provider's details by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function get_provider_by_id($id) {
        $query = $this->db->get_where($this->table_name, array('id' => $id));
        return $query->row();
    }

    /**
     * Get all providers, optionally filtered by service type.
     *
     * @param string|null $service_type
     * @return array
     */
    public function get_all_providers($service_type = null) {
        if ($service_type) {
            $this->db->where('service_type', $service_type);
        }
        $query = $this->db->get($this->table_name);
        return $query->result();
    }
    
    /**
     * Get all active providers, optionally filtered by service type.
     *
     * @param string|null $service_type
     * @return array
     */
    public function get_all_active_providers($service_type = null) {
        if ($service_type) {
            $this->db->where('service_type', $service_type);
        }
        $this->db->where('is_active', 1);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    /**
     * Add a new provider.
     *
     * @param array $data
     * @return int|false Insert ID or false on failure.
     */
    public function add_provider($data) {
        if (isset($data['other_config_json']) && is_array($data['other_config_json'])) {
            $data['other_config_json'] = json_encode($data['other_config_json']);
        }
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * Update an existing provider.
     *
     * @param int $id
     * @param array $data
     * @return bool True on success, false on failure.
     */
    public function update_provider($id, $data) {
        if (isset($data['other_config_json']) && is_array($data['other_config_json'])) {
            $data['other_config_json'] = json_encode($data['other_config_json']);
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table_name, $data);
    }

    /**
     * Delete a provider.
     *
     * @param int $id
     * @return bool True on success, false on failure.
     */
    public function delete_provider($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }
}