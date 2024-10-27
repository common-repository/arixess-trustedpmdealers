<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Abstract_Rest_Dao extends Abstract_Dao
{
    protected $rest;
    protected $request_header;

    public function __construct() {
         $this->rest = Rest_HTTP_Client::get_instance();
         $this->request_header = array(
            'Content-type: application/json',
            'Expect:',
         );
    }

    protected function request( $url, $request_method, $request_body = null ) {
        trpmd_log('requestBody');
        trpmd_log($request_body);

        $result = $this->rest->makeRequest( $url, $request_method, json_encode( $request_body ), $this->request_header );
        $status_code = $result['status']['statusCode'];

        trpmd_log($result);

        if ( '500' == $status_code ) {
            throw new \Exception( __('Internal Server error', 'tr_pmd_locale' ) );
        }
        $body = json_decode( $result['body'], true );
        if ( isset( $body['success'] ) && false == $body['success'] ){
            throw new \Exception( __( $body['data']['message'], 'tr_pmd_locale' ) );
        }
        $body['statusCode'] = $status_code;
        return $body;
    }
}