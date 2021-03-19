<?php

if( ! defined('ABSPATH') ) exit;

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

    public function add_menu_pages(){
        add_menu_page(  
            'Bundlez', 
            'Bundlez', 
            'manage_options', 
            'bundlez-settings',  
            array( $this, 'render_settings_page' ),
            'dashicons-paperclip',
            '2'
        );

        add_submenu_page( 'bundlez-settings', 'Bundlez', 'Settings', 'manage_options', 'bundlez-settings');

    }   



    public function register(){
        register_setting( 
            'ipn_redirect_options_group', 
            'ipn_redirect_sandbox_mode', 
            array( 
                'type' => 'string', 
                'sanitize_callback' => array( $this, 'sanitize_me' )  
            ) 
        );      
    }

    public function sanitize_me( $input ){
        return $input;
    }

    public function render_settings_page(){
        require_once ADMIN_PATH . '/partials/bundlez-admin-settings.php';
    }
}