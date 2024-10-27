<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Attributes_Rest_Dao extends Abstract_Rest_Dao implements Attribute_Rest_Dao_Interface
{
    public function __construct() {
        parent::__construct();
    }

    public function get_all_attributes_ajax() {
        $result = null;
        try{
            if ( ! isset( $_POST['key'] ) ) {
                throw new \Exception( __( 'Please enter API KEY', 'tr_pmd_locale' ) );
            }
            $key = $_POST['key'];
            $result = $this->request( TR_PMD_API_URL . 'products/attributes?token=' . $key . '&client='. TR_PMD_API_CLIENT, 'GET' );
            $result = array(
                'data'      =>  $result['attributes'],
                'message'   =>  __( 'The API Key is valid', 'tr_pmd_locale' ),
                'success'   =>  'true',
            );
        } catch ( \Exception $e ) {
            $result = array(
                'message'   =>  __( 'The API Key is incorrect', 'tr_pmd_locale' ),
                'success'   =>  'false',
            );
        }
        echo json_encode( $result );
        wp_die();
    }

    public function get_all_attributes( $key ) {
        try{
            $result = $this->request( TR_PMD_API_URL . 'products/attributes?token=' . $key . '&client='. TR_PMD_API_CLIENT, 'GET' );

        } catch ( \Exception $e ) {
            return null;
        }
        arsort($result['attributes']['purity'][1]);
        arsort($result['attributes']['purity'][2]);
        arsort($result['attributes']['purity'][3]);
        arsort($result['attributes']['purity'][4]);
        unset($result['attributes']['metal'][5]);
        return $result['attributes'];
    }
}
