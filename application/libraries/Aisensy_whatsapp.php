<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aisensy_whatsapp {

    protected $CI;
    private $api_key;
    private $api_url;
    private $user_name;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('provider_model'); // Load the new model

        // Fetch Aisensy provider details for WhatsApp
        $provider = $this->CI->provider_model->get_active_provider_by_name('whatsapp', 'Aisensy');

        if ($provider && !empty($provider->api_key) && !empty($provider->api_url) && !empty($provider->username)) {
            $this->api_key = $provider->api_key;
            $this->api_url = $provider->api_url;
            $this->user_name = $provider->username; // Assuming 'username' field in db stores this
        } else {
            $this->api_key = null;
            $this->api_url = null;
            $this->user_name = null;
            log_message('error', 'AiSensy WhatsApp provider details not found, not active, or incomplete in the database.');
            // Optionally throw an exception or handle the error as appropriate
        }
    }

    /**
     * Send a WhatsApp message using AiSensy API.
     *
     * @param string $recipient_mobile_number The recipient's mobile number with country code (e.g., 91xxxxxxxxxx).
     * @param string $campaign_name A unique name for your campaign.
     * @param string $template_name The pre-approved template name on AiSensy.
     * @param array $template_params An array of parameters for the template's body (e.g., ["param1" => "value1"]).
     * @param string $source Optional. Source of the campaign (e.g., "API"). Defaults to "API".
     * @param array $media Optional. Media attachment details for header.
     *                     Example: ['url' => 'your_media_url', 'filename' => 'your_filename.jpg']
     * @param array $button_values Optional. For templates with dynamic URL buttons.
     *                             Example: ["0" => ["website|https://yourwebsite.com/{{param1}}"]]
     * @param array $header_values Optional. For templates with dynamic text in header.
     *                             Example: ["param1" => "value1"]
     * @return array Response from AiSensy API or error information.
     */
    public function send_message($recipient_mobile_number, $campaign_name, $template_params_list = [], $source = "API", $media = [], $buttons = [], $carouselCards = [], $location = [], $attributes = [], $paramsFallbackValue = []) {
        if (empty($recipient_mobile_number) || empty($campaign_name)) {
            return ['status' => 'error', 'message' => 'Recipient mobile number and campaign name are required.'];
        }

        $payload = [
            "apiKey" => $this->api_key,
            "campaignName" => $campaign_name,
            "destination" => $recipient_mobile_number,
            "userName" => $this->user_name,
            "templateParams" => $template_params_list, // from new function arg
            "source" => $source,
            "media" => (object) $media, // If $media is [], becomes {}. If ['url'=>'...'], stays as is.
            "buttons" => $buttons, // If $buttons is [], becomes [].
            "carouselCards" => $carouselCards, // If $carouselCards is [], becomes [].
            "location" => (object) $location, // If $location is [], becomes {}.
            "attributes" => (object) $attributes // If $attributes is [], becomes {}.
        ];

        // paramsFallbackValue is an object with key-value pairs. Add it only if it has content.
        if (!empty($paramsFallbackValue)) { // from new function arg
            $payload['paramsFallbackValue'] = $paramsFallbackValue;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            log_message('error', 'AiSensy API cURL Error: ' . $curl_error);
            return ['status' => 'error', 'message' => 'cURL Error: ' . $curl_error, 'http_code' => $http_code];
        }

        $decoded_response = json_decode($response, true);

        if ($http_code >= 200 && $http_code < 300) {
             // AiSensy success response might not always have a 'status' field in the body,
             // but a 2xx HTTP code indicates success at the transport layer.
             // The actual success of the message sending might be in the `data` part of the response.
            if (is_array($decoded_response) && (isset($decoded_response['status']) && $decoded_response['status'] === 'success' || !isset($decoded_response['status']))) {
                 return ['status' => 'success', 'data' => $decoded_response, 'http_code' => $http_code];
            } elseif (is_array($decoded_response) && isset($decoded_response['status']) && $decoded_response['status'] === 'error') {
                log_message('error', 'AiSensy API Error: ' . json_encode($decoded_response));
                return ['status' => 'error', 'message' => 'AiSensy API returned an error.', 'details' => $decoded_response, 'http_code' => $http_code];
            } else {
                log_message('warning', 'AiSensy API response format unexpected or indicates an issue: ' . $response . ' HTTP Code: ' . $http_code);
                return ['status' => 'warning', 'message' => 'Unexpected API response format or non-success status in body.', 'raw_response' => $response, 'http_code' => $http_code, 'decoded_response' => $decoded_response];
            }
        } else {
            log_message('error', 'AiSensy API HTTP Error: ' . $http_code . ' - Response: ' . $response);
            return ['status' => 'error', 'message' => 'HTTP Error: ' . $http_code, 'response' => $decoded_response ?: $response, 'http_code' => $http_code];
        }
    }
}