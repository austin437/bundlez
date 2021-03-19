<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Bundlez_Settings_Api {

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function register_options_page() {
        add_options_page('Bundlez Settings', 'Bundlez', 'manage_options', 'bundlez-settings', array( $this, 'options_page' ) );
    }

    public function options_page_css() {
        echo "
            <style type='text/css'>
                .bundlez-text-input {
                    width: 20rem;
                }
            </style>
        ";
    }

    public function options_page() {        
        require_once ADMIN_PATH . '/partials/bundlez-admin-settings-page.php';
    }

    public function register_settings() {

        register_setting( 'bundlez_options_group', 'bundlez_cm_memberpress_url', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );
        register_setting( 'bundlez_options_group', 'bundlez_cm_memberpress_api', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );

        register_setting( 'bundlez_options_group', 'bundlez_cc_memberpress_url', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );
        register_setting( 'bundlez_options_group', 'bundlez_cc_memberpress_api', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );

    }

}