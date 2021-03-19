<?php

if( ! defined('ABSPATH') ) exit;

class Bundlez_Register_Txn_Api {

    private $txn;
    private $user;
    private $bundle;
   
    function __construct()
    {
        $this->cm_object = new stdClass();
        $this->cc_object = new stdClass();
    }

    public function mepr_txn_status_complete($txn){

        $this->txn = $txn;
        $product_id = $txn->rec->product_id;
        $this->load_user( $txn->rec->user_id );      
        $this->load_bundle( $product_id );

        if( count( $this->bundle ) === 0 ) return;

        $cm_object = $this->load_cm_object();
        $cm_response = $this->send_remote_txn( $cm_object );
        if ( is_wp_error( $cm_response ) ) throw new \Exception( $cm_response->get_error_message() );     

        $cc_object = $this->load_cc_object();
        $cc_response = $this->send_remote_txn( $cc_object );
        if ( is_wp_error( $cc_response ) ) throw new \Exception( $cc_response->get_error_message() );     

        $convict_response = $this->send_to_convict( $cm_object );
        if ( is_wp_error( $convict_response ) ) throw new \Exception( $cm_response->get_error_message() );     

        // error_log( print_r( $txn, true ), 3, LOG_PATH );
        // error_log( print_r( $this->membership, true ), 3, LOG_PATH );
        // error_log( print_r( $this->user, true ), 3, LOG_PATH );
        // error_log( print_r( $this->bundle, true ), 3, LOG_PATH );
        // error_log( print_r( $this->cm_object, true ), 3, LOG_PATH );
        // error_log( print_r( $this->cc_object, true ), 3, LOG_PATH );

        
    }

    private function load_cm_object(){
        return array(
            'memberpress_url' => get_option('bundlez_cm_memberpress_url'),
            'memberpress_api' => get_option('bundlez_cm_memberpress_api'),
            'membership_id' => $this->bundle['cm']['membership_id'],
            'membership_amount' => $this->bundle['cm']['membership_amount'],
            'send_welcome_email' => false,
            'send_password_email' => false
        );
    }

    private function load_cc_object(){
        return array(
            'memberpress_url' => get_option('bundlez_cc_memberpress_url'),
            'memberpress_api' => get_option('bundlez_cc_memberpress_api'),
            'membership_id' => $this->bundle['cc']['membership_id'],
            'membership_amount' => $this->bundle['cc']['membership_amount'],
            'send_welcome_email' => true,
            'send_password_email' => true
        );
    }

    private function load_user($user_id)
    {
        $user = get_user_by( 'ID', $user_id );
        $user->first_name = get_user_meta( $user->ID, 'first_name', true );
        $user->last_name = get_user_meta( $user->ID, 'last_name', true );
        $this->user = $user;
    }

    private function load_bundle( $product_id )
    {
        $bundlez_option = get_option( 'bundlez_option', [] );

        $this->bundle = [];

        foreach( $bundlez_option as $bundle ){
            if( (int) $product_id === (int) $bundle['bundle_membership']['membership_id']) $this->bundle = $bundle;
        }
    }

    private function send_remote_txn( $data )
    {
        $body = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(
                'MEMBERPRESS-API-KEY' => $data['memberpress_api']
            ),
            'body' => array(
                'email' => $this->user->user_email,
                'username' => $this->user->user_login,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'send_welcome_email' => $data['send_welcome_email'],
                'send_password_email' => $data['send_password_email'],
                'transaction' => array(
                    'membership' => $data['membership_id'],
                    'status' => 'complete',
                    'amount' => $data['membership_amount'],
                    'expires_at' => $this->txn->rec->expires_at
                )
            ),
            'cookies'  => array()
        );
        
        //error_log( print_r( $body, true ), 3, LOG_PATH );

        return wp_remote_post( $data['memberpress_url'] . '/members', $body );

    }

    private function send_to_convict( $data )
    {
        $body = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'body' =>  json_encode( array(
                'data' => array(
                    'expires_at' => $this->txn->rec->expires_at,
                    'member' => array(
                        'first_name' => $this->user->first_name,
                        'last_name' => $this->user->last_name,
                        'email' => $this->user->user_email,
                        'profile' => array(
                            'mepr_house_no' => '',
                            'mepr_postcode' => '',
                            'mepr_country' => '',
                            'mepr_mobile' => '',
                        )
                    ),
                    'membership' => array(
                        'id' => $data['membership_id'],
                        'period' => '1',
                        'price' => $data['membership_amount'],
                    )
                )
            ) ),
            'cookies'  => array()
        );

        //error_log( print_r( $body, true ), 3, LOG_PATH );
        
        $response = wp_remote_post( 'http://178.62.67.10/payments.php', $body );
                
    }

}