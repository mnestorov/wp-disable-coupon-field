<?php
/**
 * Plugin Name:          MN - WooCommerce Disable Coupon Field
 * Plugin URI:           https://github.com/mnestorov/wp-disable-coupon-field
 * Description:          Disable the WooCommerce coupon field on the cart and/or checkout page and adds a custom message which can be edited from the settings page.
 * Version:              1.0.0
 * Author:               Martin Nestorov
 * Author URI:           https://github.com/mnestorov
 * License:     	 GPL-2.0+
 * License URI: 	 http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:          mn-woocommerce-disable-coupon-field
 * Domain Path:          /languages/
 * WC requires at least: 3.0.0
 * WC tested up to:      5.1.0
 * Requires Plugins:	 woocommerce
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add a submenu page to the WooCommerce menu in the admin dashboard
function mn_wc_disable_coupon_menu() {
    add_submenu_page(
        'woocommerce',
        'Disable Coupon Field',
        'Disable Coupon',
        'manage_options',
        'mn-disable-coupon-settings',
        'mn_wc_disable_coupon_settings_page'
    );
}
add_action('admin_menu', 'mn_wc_disable_coupon_menu');

// Render the content of the settings page
function mn_wc_disable_coupon_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="mn-plugin-settings-content">
        <form action="options.php" method="post">
            <?php
            settings_fields('mn_wc_disable_coupon_options');
            do_settings_sections('mn-disable-coupon-settings');
            submit_button();
            ?>
        </form>
        </div>
    </div>
    <?php
}

// Initialize and register the plugin settings
function mn_wc_disable_coupon_settings_init() {
	// Check if the settings were saved and set a transient
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        set_transient('mn_wc_disable_coupon_settings_updated', 'yes', 5);
    }
	
    // Register the settings
    register_setting(
		'mn_wc_disable_coupon_options', // Option group
		'mn_wc_disable_coupon_settings', // Option name
		'mn_wc_sanitize_custom_css' // Sanitize callback
	);
    // Add settings section
    add_settings_section(
        'mn_wc_disable_coupon_section', 
        '', 
        'mn_wc_disable_coupon_section_cb', 
        'mn-disable-coupon-settings'
    );
    
    // Add settings fields
    add_settings_field(
        'mn_wc_disable_coupon_message', 
        'Custom Message', 
        'mn_wc_disable_coupon_message_cb', 
        'mn-disable-coupon-settings', 
        'mn_wc_disable_coupon_section'
    );

    add_settings_field(
        'mn_wc_disable_coupon_checkbox', 
        'Disable Field', 
        'mn_wc_disable_coupon_checkbox_cb', 
        'mn-disable-coupon-settings', 
        'mn_wc_disable_coupon_section'
    );

	add_settings_field(
        'mn_wc_disable_coupon_custom_css', // ID
        'Custom CSS', // Title
        'mn_wc_disable_coupon_custom_css_callback', // Callback function
        'mn-disable-coupon-settings', // Page
        'mn_wc_disable_coupon_section' // Section
    );
}
add_action('admin_init', 'mn_wc_disable_coupon_settings_init');

// Callback for custom CSS field
function mn_wc_disable_coupon_custom_css_callback() {
    $options = get_option('mn_wc_disable_coupon_settings');
    $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
    echo '<textarea id="mn_wc_disable_coupon_custom_css" name="mn_wc_disable_coupon_settings[custom_css]" rows="5" cols="50">' . esc_textarea($custom_css) . '</textarea>';
    echo '<p class="description">Enter any custom CSS for the message box here. It will be added to the page head.</p>';
}

// Function to enqueue custom styles added by the user
function mn_wc_disable_coupon_enqueue_custom_styles() {
    $options = get_option('mn_wc_disable_coupon_settings');
    if (!empty($options['disable_field']) && (is_cart() || is_checkout())) {
        // Echo out the custom styles if any are provided.
        if (!empty($options['custom_css'])) {
            echo '<style type="text/css">' . 
                wp_strip_all_tags($options['custom_css']) . 
                '</style>';
        }
    }
}
add_action('wp_head', 'mn_wc_disable_coupon_enqueue_custom_styles');

// Function to sanitize custom CSS input
function mn_wc_sanitize_custom_css( $input ) {
    // Sanitize the CSS input before saving.
    $input['custom_css'] = wp_strip_all_tags($input['custom_css']); // Basic sanitation
    // You may add more specific CSS sanitization here if needed.
    
    // Return the sanitized input.
    return $input;
}

// Callback function for the settings section
function mn_wc_disable_coupon_section_cb($args) {
    echo "<p>Customize the disable coupon field behavior.</p>";
}

// Callback function for the custom message input field
function mn_wc_disable_coupon_message_cb($args) {
    $options = get_option('mn_wc_disable_coupon_settings');
    ?>
    <input type="text" id="mn_wc_disable_coupon_message" name="mn_wc_disable_coupon_settings[custom_message]" size="50" value="<?php echo isset($options['custom_message']) ? esc_attr($options['custom_message']) : ''; ?>">
    <p class="description">This message will be displayed below the coupon field on the cart and checkout pages.</p>
    <?php
}

// Callback function for the disable coupon field checkbox
function mn_wc_disable_coupon_checkbox_cb($args) {
    $options = get_option('mn_wc_disable_coupon_settings');
    $checkbox = isset($options['disable_field']) ? 'checked' : '';
    ?>
    <input type="checkbox" id="mn_wc_disable_coupon_checkbox" name="mn_wc_disable_coupon_settings[disable_field]" <?php echo $checkbox; ?>>
    <label for="mn_wc_disable_coupon_checkbox">Check to disable the coupon field on the cart and checkout pages.</label>
    <?php
}

// Function to check for the transient and displays a notice if it's present
function mn_wc_disable_coupon_admin_success_notice() {
    if (get_transient('mn_wc_disable_coupon_settings_updated')) {
        echo '<div class="notice notice-success mn-auto-hide-notice"><p>' . __('Settings saved.', 'mn-woocommerce-disable-coupon-field') . '</p></div>';
		// Delete the transient so we don't keep displaying the notice
        delete_transient('mn_wc_disable_coupon_settings_updated');
    }
}
add_action('admin_notices', 'mn_wc_disable_coupon_admin_success_notice');

// Function to auto-hide the admin notices
function mn_wc_disable_coupon_admin_scripts() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $(".mn-auto-hide-notice").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 3000); // Adjust the time (in milliseconds) as per your requirement
        });
    </script>
    <?php
}
add_action('admin_head', 'mn_wc_disable_coupon_admin_scripts');

// Function to enqueue inline styles related to the coupon field and custom message box
function mn_wc_disable_coupon_enqueue_styles() {
    $options = get_option('mn_wc_disable_coupon_settings');
    if (!empty($options['disable_field']) && (is_cart() || is_checkout())) {
        // Inline styles with increased specificity
        $inline_css = "
            <style type='text/css'>
                form.woocommerce-coupon-form #coupon_code, 
                form.woocommerce-coupon-form button[name=\"apply_coupon\"] { 
                    opacity: 0.5; 
                    pointer-events: none; 
                }
                form.woocommerce-coupon-form .mn-custom-coupon-message { 
                    clear: both; 
                    display: block; 
                    color: red; 
                    margin-top: 10px; 
                }
            </style>";
        echo $inline_css; // Print inline styles
    }
}
add_action('wp_head', 'mn_wc_disable_coupon_enqueue_styles');

// Function to print the custom coupon message on the cart and checkout pages
function mn_wc_print_custom_coupon_message() {
    static $message_printed = false;
    
	if ($message_printed) {
        return;
    }

    $options = get_option('mn_wc_disable_coupon_settings');
	
    if (!empty($options['custom_message']) && !empty($options['disable_field'])) {
        // Check if the current page is either cart or checkout
        if (is_cart() || is_checkout()) {
			// Print the hidden message
            echo '<div class="woocommerce-info mn-custom-coupon-message" style="display: none; clear:both; padding-top: 1em;">' . esc_html($options['custom_message']) . '</div>';
       		$message_printed = true;
		}
    }
}
add_action('woocommerce_before_cart', 'mn_wc_print_custom_coupon_message');
add_action('woocommerce_before_checkout_form', 'mn_wc_print_custom_coupon_message');

// Function to manage the coupon field behavior and show the message on click
function mn_wc_disable_coupon_field_script() {
    $options = get_option('mn_wc_disable_coupon_settings');
    if (!empty($options['disable_field'])) {
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                // Check if the coupon code field exists
                if ($('#coupon_code').length) {
                    // Create a new div element to serve as a wrapper
                    var wrapper = $('<div id="mn-coupon-wrapper"></div>');

                    // Wrap the coupon code field with the new div
                    $('#coupon_code').wrap(wrapper);

                    // Apply CSS to disable pointer events on the input field and the apply button
                    $('#coupon_code, button[name="apply_coupon"]').css('pointer-events', 'none').css('opacity', '0.5');

                    // Add click event listener to the wrapper
                    $('#mn-coupon-wrapper').on('click', function() {
                        // Toggle the visibility of the custom message
                        $('.mn-custom-coupon-message').slideToggle();
                    });
                }
            });
        </script>
        <?php
    }
}
add_action('wp_footer', 'mn_wc_disable_coupon_field_script');

// Add a link to the settings page directly from the plugin page
function mn_wc_disable_coupon_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=mn-disable-coupon-settings') . '">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mn_wc_disable_coupon_action_links');

// Load plugin textdomain for translations
function mn_wc_disable_coupon_load_textdomain() {
    load_plugin_textdomain('mn-woocommerce-disable-coupon-field', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'mn_wc_disable_coupon_load_textdomain');
