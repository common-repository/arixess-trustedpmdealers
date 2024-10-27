<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface Product_Rest_Dao_Interface
{
    public function get_product_by_id( $id );
    public function submit_product( Product $product );
    public function update_product( Product $product );
    public function delete_product($id );
}