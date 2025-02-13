<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bank of Maldives Payment Gateway Class
 */
class WC_Bank_of_Maldives_Gateway extends WC_Payment_Gateway {
    
    /**
     * Constructor for the gateway
     */
    public function __construct() {
        $this->id                 = 'bank_of_maldives';
        $this->icon               = apply_filters('woocommerce_bml_icon', BML_GATEWAY_PLUGIN_URL . 'assets/bml-logo.png');
        $this->has_fields         = true;
        $this->method_title       = __('Bank of Maldives Connect', 'bank-of-maldives-gateway');
        $this->method_description = __('Process payments through Bank of Maldives Connect payment gateway', 'bank-of-maldives-gateway');

        // Load the settings
        $this->init_form_fields();
        $this->init_settings();

        // Define user-set variables
        $this->title        = $this->get_option('title');
        $this->description  = $this->get_option('description');
        $this->enabled      = $this->get_option('enabled');
        $this->testmode     = 'yes' === $this->get_option('testmode');
        $this->api_key      = $this->testmode ? $this->get_option('test_api_key') : $this->get_option('api_key');
        $this->secret_key   = $this->testmode ? $this->get_option('test_secret_key') : $this->get_option('secret_key');

        // Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'bank-of-maldives-gateway'),
                'type'    => 'checkbox',
                'label'   => __('Enable Bank of Maldives Payment', 'bank-of-maldives-gateway'),
                'default' => 'no'
            ),
            'testmode' => array(
                'title'       => __('Test mode', 'bank-of-maldives-gateway'),
                'type'        => 'checkbox',
                'label'       => __('Enable Test Mode', 'bank-of-maldives-gateway'),
                'default'     => 'yes',
                'description' => __('Place the payment gateway in test mode.', 'bank-of-maldives-gateway'),
            ),
            'title' => array(
                'title'       => __('Title', 'bank-of-maldives-gateway'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'bank-of-maldives-gateway'),
                'default'     => __('Bank of Maldives', 'bank-of-maldives-gateway'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'bank-of-maldives-gateway'),
                'type'        => 'textarea',
                'description' => __('Payment method description that the customer will see on your checkout.', 'bank-of-maldives-gateway'),
                'default'     => __('Pay securely through Bank of Maldives', 'bank-of-maldives-gateway'),
                'desc_tip'    => true,
            ),
            'api_key' => array(
                'title'       => __('Live API Key', 'bank-of-maldives-gateway'),
                'type'        => 'text',
                'description' => __('Enter your Bank of Maldives Live API Key', 'bank-of-maldives-gateway'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'secret_key' => array(
                'title'       => __('Live Secret Key', 'bank-of-maldives-gateway'),
                'type'        => 'password',
                'description' => __('Enter your Bank of Maldives Live Secret Key', 'bank-of-maldives-gateway'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_api_key' => array(
                'title'       => __('Test API Key', 'bank-of-maldives-gateway'),
                'type'        => 'text',
                'description' => __('Enter your Bank of Maldives Test API Key', 'bank-of-maldives-gateway'),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'test_secret_key' => array(
                'title'       => __('Test Secret Key', 'bank-of-maldives-gateway'),
                'type'        => 'password',
                'description' => __('Enter your Bank of Maldives Test Secret Key', 'bank-of-maldives-gateway'),
                'default'     => '',
                'desc_tip'    => true,
            ),
        );
    }

    // [Previous methods remain the same: payment_fields(), process_payment(), validate_payment(), etc.]
    // Include all the methods from the previous version
}
