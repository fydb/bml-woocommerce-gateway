<?php
/**
 * Plugin Name: Bank of Maldives Payment Gateway for WooCommerce
 * Plugin URI: https://yourwebsite.com/bml-woocommerce-gateway
 * Description: Accept payments through Bank of Maldives Connect in your WooCommerce store
 * Version: 1.0.0
 * Author: Mohamed Ailam
 * Text Domain: bank-of-maldives-gateway
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 4.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * DISCLAIMER: This is NOT an official Bank of Maldives plugin.
 * This plugin is an independent integration and is not affiliated with,
 * officially connected to, or endorsed by Bank of Maldives PLC.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BML_GATEWAY_VERSION', '1.0.0');
define('BML_GATEWAY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BML_GATEWAY_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Initialize the plugin
 */
function bml_gateway_init() {
    // Load plugin text domain
    load_plugin_textdomain('bank-of-maldives-gateway', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'bml_gateway_woocommerce_missing_notice');
        return;
    }

    // Include the main gateway class
    require_once BML_GATEWAY_PLUGIN_DIR . 'includes/class-wc-bank-of-maldives-gateway.php';

    // Declare HPOS compatibility
    add_action('before_woocommerce_init', function() {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    });

    // Add the gateway to WooCommerce
    add_filter('woocommerce_payment_gateways', 'bml_add_payment_gateway');
}
add_action('plugins_loaded', 'bml_gateway_init');

/**
 * Add the gateway to WooCommerce
 */
function bml_add_payment_gateway($gateways) {
    $gateways[] = 'WC_Bank_of_Maldives_Gateway';
    return $gateways;
}

/**
 * WooCommerce missing notice
 */
function bml_gateway_woocommerce_missing_notice() {
    ?>
    <div class="error">
        <p><?php _e('Bank of Maldives Payment Gateway requires WooCommerce to be installed and active.', 'bank-of-maldives-gateway'); ?></p>
    </div>
    <?php
}

/**
 * Add plugin action links
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bml_gateway_plugin_links');
function bml_gateway_plugin_links($links) {
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=bank_of_maldives') . '">' . __('Settings', 'bank-of-maldives-gateway') . '</a>',
    );
    return array_merge($plugin_links, $links);
}
