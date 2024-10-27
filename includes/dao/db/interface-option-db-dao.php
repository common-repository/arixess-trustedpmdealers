<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface Option_Db_Dao_Interface
{
    public function get_all_option();
    public function set_all_option( $key, $currency, $attr );
    public function save_option_ajax(Attributes_Rest_Dao $attr_rest_dao );
    public function get_api_key();
    public function set_api_key($key);
    public function delete_api_key();
    public function get_api_currency();
    public function set_api_currency($key );
    public function delete_api_currency();
    public function get_attributes();
    public function set_attributes( $attr );
    public function delete_attributes();
}