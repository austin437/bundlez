<?php

if( ! defined('ABSPATH') ) exit;

class Bundlez_Register_Txn_Api {

    public function mepr_txn_status_complete($txn){
        $product_id = $txn->rec->product_id;
        $bundle = $this->get_bundle( $product_id );

        if( count( $bundle ) === 0 ) return;
        error_log( print_r( $bundle, true ), 3, LOG_PATH );
    }

    private function get_bundle( $product_id )
    {
        $bundlez_option = get_option( 'bundlez_option', [] );

        foreach( $bundlez_option as $bundle ){
            if( (int) $product_id === (int) $bundle['bundle_membership']['membership_id']) return $bundle;
        }

        return [];
    }

    private function create_remote_txn( $txn_data )
    {
        // $response = wp_remote_post( $ftg_cm_memberpress_url . '/members', array(
        //     'method'      => 'POST',
        //     'timeout'     => 45,
        //     'redirection' => 5,
        //     'httpversion' => '1.0',
        //     'blocking'    => true,
        //     'headers'     => array(
        //         'MEMBERPRESS-API-KEY' => $ftg_cm_memberpress_api
        //     ),
        //     'body' => array(
        //         'email' => $user_personal_email,
        //         'username' => $user_personal_email,
        //         'first_name' => $user_first_name,
        //         'last_name' => $user_last_name,
        //         'transaction' => array(
        //             'membership' => $ftg_cm_membership,
        //             'coupon' => $ftg_cm_coupon,
        //             'status' => 'complete',
        //             'expires_at' => $ftg_membership_expiry_date. ' 23:59:59'
        //         )
        //     ),
        //     'cookies'  => array()
        //     )
        // );
    }

    private function send_to_convict( $txn_data )
    {

    }

}