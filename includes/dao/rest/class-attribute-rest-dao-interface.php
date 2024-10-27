<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface Attribute_Rest_Dao_Interface
{
    public function get_all_attributes_ajax();
    public function get_all_attributes( $key );
}