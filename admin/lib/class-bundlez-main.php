<?php

if( ! defined('ABSPATH') ) exit;

class Bundlez_Main {

    private $memberpress_api;
    private $plugin_name;
    private $version;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( Bundlez_Memberpress_Api $memberpress_api, $plugin_name, $version ) {

        $this->memberpress_api = $memberpress_api;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function add_menu_pages(){
        add_menu_page(  
            'Bundlez', 
            'Bundlez', 
            'manage_options', 
            'bundlez',  
            array( $this, 'render_settings_page' ),
            'dashicons-paperclip',
            '2'
        );

        add_submenu_page( 'bundlez', 'Bundlez', 'Bundlez', 'manage_options', 'bundlez');

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

        $bundlez_options = get_option( 'bundlez_option', [] );

        $memberpress_memberships = $this->memberpress_api->get_memberships();

        $bundlez_cm_memberpress_url = get_option('bundlez_cm_memberpress_url');
        $bundlez_cm_memberpress_api = get_option('bundlez_cm_memberpress_api');

        if( $bundlez_cm_memberpress_url ) 
        {
            $bundlez_cm_memberships = json_decode(
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
            $bundlez_cm_memberships = [];
        }

        $bundlez_cc_memberpress_url = get_option('bundlez_cc_memberpress_url');
        $bundlez_cc_memberpress_api = get_option('bundlez_cc_memberpress_api');

        if( $bundlez_cc_memberpress_url ) 
        {
            $bundlez_cc_memberships = json_decode(
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
            $bundlez_cc_memberships = [];
        }

        require_once ADMIN_PATH . '/partials/bundlez-admin-main.php';
    }

    public function save_new_bundle(){
        check_admin_referer( 'add-new-bundle');

        $post_data = sanitize_post( $_POST );

        $bundle_membership = explode( "|", $post_data['bundlez']['bundle_membership']) ;
        $post_data['bundlez']['bundle_membership'] = [];
        $post_data['bundlez']['bundle_membership']['membership_id'] = $bundle_membership[0];
        $post_data['bundlez']['bundle_membership']['membership_title'] = $bundle_membership[1];
    
        $cm_membership = explode( "|", $post_data['bundlez']['cm']['membership']) ;
        $post_data['bundlez']['cm']['membership_id'] = $cm_membership[0];
        $post_data['bundlez']['cm']['membership_title'] = $cm_membership[1];
        unset($post_data['bundlez']['cm']['membership']);

        $cc_membership = explode( "|", $post_data['bundlez']['cc']['membership']) ;
        $post_data['bundlez']['cc']['membership_id'] = $cc_membership[0];
        $post_data['bundlez']['cc']['membership_title'] = $cc_membership[1];
        unset($post_data['bundlez']['cc']['membership']);

        $bundlez_option = get_option( 'bundlez_option', [] );

        $bundlez_option[] = $post_data['bundlez'];

        update_option( 'bundlez_option', $bundlez_option );

        wp_redirect(admin_url('admin.php?page=bundlez'));

        die();
    }

    public function delete_bundle(){
        check_admin_referer( 'delete-bundle');

        $post_data = sanitize_post( $_POST );

        $bundle_key = $post_data['bundle_key'];

        $bundlez_option = get_option( 'bundlez_option', [] );

        unset( $bundlez_option[$bundle_key]);

        update_option( 'bundlez_option', $bundlez_option );

        wp_redirect(admin_url('admin.php?page=bundlez'));

        die();
       

    }

    
}