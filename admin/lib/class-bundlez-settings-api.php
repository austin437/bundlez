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

        $bundlez_cm_memberpress_url = get_option('bundlez_cm_memberpress_url');
        $bundlez_cm_memberpress_api = get_option('bundlez_cm_memberpress_api');

        if( $bundlez_cm_memberpress_url ) 
        {
            $cm_memberships = json_decode(
                wp_remote_get( 
                    $bundlez_cm_memberpress_url . '/memberships?per_page=1000',
                    array(
                        'headers' => array(
                            'MEMBERPRESS-API-KEY' => $bundlez_cm_memberpress_api
                        )
                    )
                )['body']
            );
        }
        else
        {
            $cm_memberships = [];
        }
        

        if( ! is_array( $cm_memberships ) ) $cm_memberships = [];        

        $bundlez_cc_memberpress_url = get_option('bundlez_cc_memberpress_url');
        $bundlez_cc_memberpress_api = get_option('bundlez_cc_memberpress_api');

        if( $bundlez_cc_memberpress_url ) 
        {
            $cc_memberships = json_decode(
                wp_remote_get( 
                    $bundlez_cc_memberpress_url . '/memberships?per_page=1000',
                    array(
                        'headers' => array(
                            'MEMBERPRESS-API-KEY' => $bundlez_cc_memberpress_api
                        )
                    )
                )['body']
            );
        }
        else
        {
            $cc_memberships = [];
        }

        if( ! is_array( $cc_memberships ) ) $cc_memberships = [];
        
        require_once ADMIN_PATH . '/partials/bundlez-admin-settings-page.php';
    }

    public function register_settings() {

        register_setting( 'bundlez_options_group', 'bundlez_cm_memberpress_url', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );
        register_setting( 'bundlez_options_group', 'bundlez_cm_memberpress_api', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );

        register_setting( 'bundlez_options_group', 'bundlez_cc_memberpress_url', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );
        register_setting( 'bundlez_options_group', 'bundlez_cc_memberpress_api', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );

    }

}