<p align="center"><a href="https://wordpress.org" target="_blank"><img src="https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/wordpress/wordpress.png" width="100" alt="WordPress Logo"></a></p>

# WordPress - Disable Coupon Field for WooCommerce

[![Licence](https://img.shields.io/badge/LICENSE-GPL2.0+-blue)](./LICENSE)

- **Developed by:** Martin Nestorov 
    - Explore more at [nestorov.dev](https://github.com/mnestorov)
- **Plugin URI:** https://github.com/mnestorov/wp-disable-coupon-field

## Overview

**_Disable the coupon field on the WooCommerce cart and/or checkout page and add a custom message_**

The WooCommerce Disable Coupon Field plugin allows you to deactivate the coupon field on the cart and/or checkout page and display a custom message below it. This message can be easily customized from the plugin's settings page under the WooCommerce menu.

## Features

- Disable the coupon field on the WooCommerce cart and/or checkout page.
- Add a custom and translatable message below the disabled coupon field.
- Customize the message from the plugin settings page in the admin area.
- Easy to use with no coding knowledge required.

## Installation

1. Upload the `wp-disable-coupon-field` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the WooCommerce->Disable Coupon menu to configure the plugin.

## Usage

After installation, navigate to the WooCommerce->Disable Coupon settings page to:

- Enter the custom message to be displayed below the disabled coupon field on the checkout page.

## Functions

- `mn_wc_disable_coupon_admin_success_notice`:

    **Purpose:** Displays an admin notice in the WordPress admin area when plugin settings are successfully updated.

    **Action Hook:** `admin_notices` – Adds the admin notice when the admin dashboard is loaded.

- `mn_wc_disable_coupon_admin_scripts`:

    **Purpose:** Enqueues a JavaScript script to automatically hide admin notices after a specified duration.

    **Action Hook:** `admin_head` – Injects the script into the admin page header.

- `mn_wc_disable_coupon_menu`:
    
    **Purpose:** Adds a submenu page for the plugin under the WooCommerce menu in the WordPress admin.
    
    **Action Hook:** `admin_menu` – Adds the submenu when the admin menu is being built.

- `mn_wc_disable_coupon_settings_page`:

    **Purpose:** Renders the content of the settings page in the WordPress admin.

    **Usage:** Called internally by WordPress when the plugin's settings page is accessed.

- `mn_wc_disable_coupon_settings_init`:

    **Purpose:** Registers the plugin's settings, settings section, and settings fields. It also sets up the sanitization callback for custom CSS input.
    
    **Action Hook:** `admin_init` – Initializes the settings when the admin panel is loaded.

- `mn_wc_disable_coupon_custom_css_callback`:

    **Purpose:** Callback function for rendering the custom CSS input field in the plugin's settings page.

- `mn_wc_disable_coupon_enqueue_custom_styles`:

    **Purpose:** Enqueues custom CSS added by the user on the front-end pages.

    **Action Hook:** `wp_head` – Inserts custom styles into the page header.

- `mn_wc_sanitize_custom_css`:

    **Purpose:** Sanitizes the custom CSS input from the plugin's settings page to ensure safe and valid CSS.

- `mn_wc_disable_coupon_section_cb`:

    **Purpose:** Callback function for the settings section, primarily used for displaying a description or additional information about the settings section.

- `mn_wc_disable_coupon_message_cb`:

    **Purpose:** Callback function for the custom message input field in the plugin's settings page.

- `mn_wc_disable_coupon_checkbox_cb`:

    **Purpose:** Callback function for the disable coupon field checkbox in the plugin's settings page.

- `mn_wc_disable_coupon_enqueue_styles`:

    **Purpose:** Enqueues inline styles that apply to the coupon field and custom message box on the front-end.
    
    **Action Hook:** `wp_head` – Adds inline styles to the page header.

- `mn_wc_print_custom_coupon_message`:

    **Purpose:** Prints the custom message on the front-end. The message is shown on the cart and checkout pages.

    **Action Hook:** `woocommerce_before_cart` and `woocommerce_before_checkout_form` – Places the message before the cart and checkout forms.

- `mn_wc_disable_coupon_field_script`:

    **Purpose:** Outputs a script that disables the coupon code field and the "apply coupon" button.

    **Action Hook:** `wp_footer` – Adds the script to the footer, ensuring it executes after the page elements have loaded.

- `mn_wc_disable_coupon_action_links`:

    **Purpose:** Adds a settings link to the plugin's entry on the WordPress plugins page, providing quick access to the plugin's settings.

    **Filter Hook:** `plugin_action_links_[plugin_name]` – Modifies the action links for the plugin.

- `mn_wc_disable_coupon_load_textdomain`:

    **Purpose:** Loads the plugin's textdomain for internationalization, allowing it to be translated into different languages.
    
    **Action Hook:** `plugins_loaded` – Ensures the textdomain is loaded after all plugins are loaded.

## Error Handling and Debugging

Ensure that file uploads are enabled and the file types for icons are allowed on your WordPress installation. Check the browser console and server error logs for any issues.

## Frequently Asked Questions

### How do I change the custom message?

Go to the WooCommerce->Disable Coupon settings page and enter your new message into the 'Custom Message' field, then save your changes.

### Can I use HTML in the custom message?

Yes, basic HTML tags are allowed in the custom message field to format the message.

## Translation

This plugin is translation-ready, and translations can be added to the `languages` directory.

## Changelog

For a detailed list of changes and updates made to this project, please refer to our [Changelog](./CHANGELOG.md).

## Support The Project

If you find this script helpful and would like to support its development and maintenance, please consider the following options:

- **_Star the repository_**: If you're using this script from a GitHub repository, please give the project a star on GitHub. This helps others discover the project and shows your appreciation for the work done.

- **_Share your feedback_**: Your feedback, suggestions, and feature requests are invaluable to the project's growth. Please open issues on the GitHub repository or contact the author directly to provide your input.

- **_Contribute_**: You can contribute to the project by submitting pull requests with bug fixes, improvements, or new features. Make sure to follow the project's coding style and guidelines when making changes.

- **_Spread the word_**: Share the project with your friends, colleagues, and social media networks to help others benefit from the script as well.

- **_Donate_**: Show your appreciation with a small donation. Your support will help me maintain and enhance the script. Every little bit helps, and your donation will make a big difference in my ability to keep this project alive and thriving.

Your support is greatly appreciated and will help ensure all of the projects continued development and improvement. Thank you for being a part of the community!
You can send me money on Revolut by following this link: https://revolut.me/mnestorovv

---

## License

This project is released under the [GPL-2.0+ License](http://www.gnu.org/licenses/gpl-2.0.txt).
