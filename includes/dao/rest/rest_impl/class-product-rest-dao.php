<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Rest_Dao extends Abstract_Rest_Dao implements Product_Rest_Dao_Interface
{
    private $key;

    public function set_key( $key ) {
        $this->key = $key;
    }

    public function get_product_by_id( $id ) {
        $result = $this->request( TR_PMD_API_URL . 'products/'. $id .'?token=' . $this->key . '&client='. TR_PMD_API_CLIENT, 'GET' );
        return $result;
    }

    public function submit_product( Product $product ) {
        $result = $this->request( TR_PMD_API_URL . 'products?token=' . $this->key . '&client='. TR_PMD_API_CLIENT, 'POST', $product->get_data_rest() );
        return $result;
    }

    public function update_product( Product $product ) {
        $result = $this->request( TR_PMD_API_URL . 'products/'. $product->get_tpmd_post_id() .'?token=' . $this->key . '&client='. TR_PMD_API_CLIENT, 'PUT', $product->get_data_rest() );
        return $result;
    }

    public function delete_product( $id ) {
        $result = $this->request( TR_PMD_API_URL . 'products/'. $id .'?token=' . $this->key . '&client='. TR_PMD_API_CLIENT, 'DELETE' );
        return $result;
    }
}