<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Bundlez
 * @subpackage Bundlez/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bundlez
 * @subpackage Bundlez/admin
 * @author     Your Name <email@example.com>
 */
class Bundlez_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bundlez    The ID of this plugin.
	 */
	private $bundlez;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $bundlez       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bundlez, $version ) {

		$this->bundlez = $bundlez;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bundlez_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bundlez_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->bundlez, plugin_dir_url( __FILE__ ) . 'css/bundlez-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bundlez_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bundlez_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->bundlez, plugin_dir_url( __FILE__ ) . 'js/bundlez-admin.js', array( 'jquery' ), $this->version, false );

	}


    public function check_memberpress_installed(){
        if ( is_admin() && current_user_can( 'activate_plugins' ) &&  ! is_plugin_active( 'memberpress/memberpress.php' ) ) {
        add_action( 'admin_notices', array( $this, 'memberpress_not_installed_notice' ) );

            deactivate_plugins( BASE_NAME ); 

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
    }

    function memberpress_not_installed_notice(){
        ?>
            <div class="error">
                <p>Sorry, but Bundlez requires MemberPress to be installed and active.</p>
            </div>
        <?php
    }

     function wp_mail( $mail ){

        $subject = $mail['subject'];   

        if( strpos($subject, 'Set Your New Password') !== false ){            
            $mail['to'] = 'wp-admin@conquermaths.com';
        }

        return $mail;
    }

}
