<?php

namespace TrustedPMDealers;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Option_Db_Dao extends Abstract_Dao implements Option_Db_Dao_Interface
{
    public function __construct(){}

    public function set_all_option($key, $currency, $attr ) {
        $this->delete_api_key();
        $this->delete_api_currency();
        $this->delete_attributes();

        if ( $this->set_api_key( $key ) && $this->set_api_currency( $currency ) && $this->set_attributes( $attr ) ) {
            return true;
        }
        return false;
    }

    public function get_api_key() {
        return get_option(TR_PMD_API_KEY, '');
    }

    public function set_api_key($key ) {
        return update_option( TR_PMD_API_KEY, $key );
    }

    public function delete_api_key() {
        return delete_option( TR_PMD_API_KEY );
    }

    public function get_api_currency() {
        return get_option( TR_PMD_API_CUR, '' );
    }

    public function set_api_currency($cur) {
        return update_option( TR_PMD_API_CUR, $cur );
    }

    public function delete_api_currency() {
        return delete_option( TR_PMD_API_CUR );
    }

    /*
     * Handle ajax request save option api
     */
    public function save_option_ajax (Attributes_Rest_Dao $attrRestDao ) {
        $result = null;
        try {
            if ( ! isset( $_POST['key'] ) || ! isset( $_POST['cur'] ) ) {
                throw new \Exception( __( 'Please check enter data', 'tr_pmd_locale') );
            }
            $key = $_POST['key'];
            $cur = $_POST['cur'];
            $attr = $attrRestDao->get_all_attributes( $key );

            if ( ! $this->set_all_option( $key, $cur, $attr ) ){
                throw new \Exception( __( 'Save unsuccessful, check and try again', 'tr_pmd_locale' ) );
            }
            $result = array(
                'message' => __( 'Settings Saved', 'tr_pmd_locale' ),
                'success' => 'true',
            );
        } catch (\Exception $e) {
            $result = array(
                'message' => $e->getMessage(),
                'success' => 'false',
            );
        }
        echo json_encode( $result );
        wp_die();
    }

    public function get_attributes() {
        return get_option( TR_PMD_API_ATTR, '' );
    }

    public function set_attributes( $attr ) {
        return update_option( TR_PMD_API_ATTR, $attr );
    }

    public function delete_attributes() {
        return delete_option( TR_PMD_API_ATTR );
    }

    public function get_all_option() {
        $key = $this->get_api_key();
        $cur = $this->get_api_currency();
        $attr = $this->get_attributes();
        return new Option( $key, $cur, $attr );
    }
}
