<?php

if( ! defined('ABSPATH') ) exit;

class Bundlez_Register_Txn_Api {

    public function mepr_txn_status_complete($txn){
        $txn_id = $txn->rec->id;
        $bundle = $this->get_bundle( $txn_id );
        error_log( print_r( $bundle, true ), 3, LOG_PATH );
    }

    private function get_bundle( $txn_id )
    {
        
    }

    private function create_remote_txn( $txn_data )
    {

    }

    private function send_to_convict( $txn_data )
    {

    }

}