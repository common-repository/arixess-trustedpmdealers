<?php

namespace TrustedPMDealers;

if ( !defined( 'ABSPATH' )) {
    exit;
}

interface Product_Db_Dao_Interface
{
    public function get_product_by_id( $id );
    public function add_product( Product $product );
    public function update_product( Product $product );
    public function delete_product($id );
    public function check_product($id );
    public function update_rest_status($id, $status );
    public function set_error_field($id, $fields );
    public function get_error_field($id );
    public function set_uploaded($id );
}