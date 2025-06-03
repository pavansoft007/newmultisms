<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_sender extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('aisensy_whatsapp');
        $this->load->helper('url'); // For redirect or base_url if needed in views
        // $this->load->model('your_contacts_model'); // Uncomment if you have a model for contacts
    }

    /**
     * Index method - can be used for a simple test form or info page.
     */
    public function index() {
        echo "<h1>WhatsApp Sender Controller</h1>";
        echo "<p>Use specific methods to send messages.</p>";
        echo "<p><a href='" . site_url('whatsapp_sender/test_send') . "'>Test Send Single Message</a></p>";
        // Add more links or a form here if needed
    }

    /**
     * Send a single test message.
     * Replace with actual campaign name, destination, and user name.
     */
    public function test_send() {
        // --- IMPORTANT: Replace these with your actual data ---
        $campaignName = 'YOUR_CAMPAIGN_NAME'; // The campaign name registered in AI Sensy
        $destination = '919876543210';    // Recipient's phone number with country code
        $userName = 'Test User';             // A user identifier
        
        // Optional: Template parameters (if your template needs them)
        // Example: $templateParams = ["John Doe", "Order #123", "Shipped"];
        $templateParams = []; 

        // Optional: Media attachment (if your template supports it)
        // Example: $media = ['url' => 'https://example.com/image.jpg', 'filename' => 'invoice.jpg'];
        $media = null; 
        // --- End of data to replace ---

        if ($campaignName === 'YOUR_CAMPAIGN_NAME' || $destination === '919876543210') {
            echo "<h1>Test Send Aborted</h1>";
            echo "<p>Please open <code>application/controllers/Whatsapp_sender.php</code> and update the <code>\$campaignName</code> and <code>\$destination</code> variables in the <code>test_send()</code> method with your actual AI Sensy campaign name and a test recipient phone number.</p>";
            return;
        }

        echo "<h1>Sending Test WhatsApp Message...</h1>";
        echo "<p>Campaign: " . htmlspecialchars($campaignName) . "</p>";
        echo "<p>To: " . htmlspecialchars($destination) . "</p>";
        echo "<p>User: " . htmlspecialchars($userName) . "</p>";

        $result = $this->aisensy_whatsapp->send_campaign_message($campaignName, $destination, $userName, $templateParams, $media);

        if ($result['success']) {
            echo "<h2>Message Sent Successfully!</h2>";
            echo "<pre>";
            print_r($result['response']);
            echo "</pre>";
        } else {
            echo "<h2>Failed to Send Message!</h2>";
            echo "<p>Error: " . htmlspecialchars($result['error']) . "</p>";
            if (!empty($result['response'])) {
                echo "<p>API Response:</p>";
                echo "<pre>";
                print_r($result['response']);
                echo "</pre>";
            }
        }
    }

    /**
     * Placeholder for bulk sending functionality.
     * You would typically fetch contacts from a database or a CSV file.
     */
    public function bulk_send() {
        // 1. Get your list of contacts.
        // Example: $contacts = $this->your_contacts_model->get_active_contacts();
        // Or read from a CSV, etc.
        $contacts = [
            ['phone' => '919876543211', 'name' => 'User One', 'params' => ['Product A']],
            ['phone' => '919876543212', 'name' => 'User Two', 'params' => ['Service B']],
            // Add more contacts
        ];

        $campaignName = 'YOUR_BULK_CAMPAIGN_NAME'; // The campaign name for bulk messages

        if ($campaignName === 'YOUR_BULK_CAMPAIGN_NAME') {
            echo "<h1>Bulk Send Aborted</h1>";
            echo "<p>Please open <code>application/controllers/Whatsapp_sender.php</code> and update the <code>\$campaignName</code> in the <code>bulk_send()</code> method with your actual AI Sensy campaign name for bulk messages.</p>";
            echo "<p>Also, update the <code>\$contacts</code> array with actual recipient data or implement logic to fetch them.</p>";
            return;
        }

        echo "<h1>Processing Bulk WhatsApp Messages...</h1>";

        $results = [];
        foreach ($contacts as $contact) {
            echo "<hr>Sending to: " . htmlspecialchars($contact['name']) . " (" . htmlspecialchars($contact['phone']) . ")";
            
            // Assuming templateParams are specific to each contact
            $templateParams = isset($contact['params']) ? $contact['params'] : [];
            
            $result = $this->aisensy_whatsapp->send_campaign_message(
                $campaignName,
                $contact['phone'],
                $contact['name'],
                $templateParams
                // Add media here if applicable for the bulk campaign
            );

            $results[] = [
                'contact' => $contact,
                'status' => $result['success'] ? 'Success' : 'Failed',
                'response' => $result['response'],
                'error' => $result['error']
            ];

            if ($result['success']) {
                echo "<br>Status: <strong style='color:green;'>Success</strong>";
                // log_message('info', 'Bulk WhatsApp sent to ' . $contact['phone'] . ' successfully.');
            } else {
                echo "<br>Status: <strong style='color:red;'>Failed</strong> - " . htmlspecialchars($result['error']);
                // log_message('error', 'Failed to send bulk WhatsApp to ' . $contact['phone'] . '. Error: ' . $result['error']);
            }
            // Optional: Add a small delay between messages if sending a large volume
            // sleep(1); 
        }

        echo "<h2>Bulk Sending Summary:</h2>";
        echo "<pre>";
        print_r($results);
        echo "</pre>";

        // You might want to render a view with these results
    }
/**
     * Manage WhatsApp Templates.
     * This will list templates and allow CRUD operations.
     */
    public function templates() {
        // Check permission
        // if (!get_permission('whatsapp_templates', 'is_view')) {
        //     access_denied();
        // }

        $data['main_menu'] = 'whatsapp_sender';
        $data['sub_page'] = 'whatsapp_sender/templates';
        $data['title'] = translate('whatsapp_templates');
        // $data['templates'] = $this->your_whatsapp_model->get_all_templates(); // Example model call
        $this->load->view('layout/index', $data); // Assumes a view file at application/views/whatsapp_sender/templates.php
    }

    /**
     * Edit a WhatsApp Template.
     */
    public function template_edit($id = null) {
        // Check permission for add/edit
        // if (!get_permission('whatsapp_templates', 'is_add') && !get_permission('whatsapp_templates', 'is_edit')) {
        //     access_denied();
        // }

        // Logic to handle template creation or update
        // if ($this->input->post()) {
        //     // Handle form submission
        // }

        $data['main_menu'] = 'whatsapp_sender';
        $data['sub_page'] = 'whatsapp_sender/template_edit';
        $data['title'] = $id ? translate('edit_whatsapp_template') : translate('add_whatsapp_template');
        // $data['template'] = $id ? $this->your_whatsapp_model->get_template($id) : null; // Example
        $this->load->view('layout/index', $data); // Assumes a view file at application/views/whatsapp_sender/template_edit.php
    }

    /**
     * Interface to send WhatsApp messages.
     * Similar to SMS/Email sending interface.
     */
    public function send() {
        // Check permission
        // if (!get_permission('whatsapp_send', 'is_view')) { // Or 'is_add' depending on how you structure permissions
        //     access_denied();
        // }

        // if ($this->input->post()) {
        //     // Handle sending logic
        //     // $contacts = $this->input->post('contacts');
        //     // $template_name = $this->input->post('template_name');
        //     // $params = $this->input->post('params');
        //     // Loop through contacts and send messages
        // }

        $data['main_menu'] = 'whatsapp_sender';
        $data['sub_page'] = 'whatsapp_sender/send';
        $data['title'] = translate('send_whatsapp');
        $this->load->view('layout/index', $data); // Assumes a view file at application/views/whatsapp_sender/send.php
    }

    /**
     * Display reports for WhatsApp campaigns/messages.
     */
    public function reports() {
        // Check permission
        // if (!get_permission('whatsapp_reports', 'is_view')) {
        //     access_denied();
        // }

        $data['main_menu'] = 'whatsapp_sender';
        $data['sub_page'] = 'whatsapp_sender/reports';
        $data['title'] = translate('whatsapp_reports');
        // $data['reports'] = $this->your_whatsapp_model->get_campaign_reports(); // Example
        $this->load->view('layout/index', $data); // Assumes a view file at application/views/whatsapp_sender/reports.php
    }
}