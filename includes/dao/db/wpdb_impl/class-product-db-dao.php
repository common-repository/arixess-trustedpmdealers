<?php

namespace TrustedPMDealers;

class Product_Db_Dao extends Abstract_Dao implements Product_Db_Dao_Interface
{
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'tpmd_product';
    }

    public function get_product_by_id( $id ) {
       global $wpdb;
       $result = $wpdb->get_row( "SELECT * FROM $this->table_name  WHERE id = $id", ARRAY_A );
       $product = new Product();
       $product->set_data_from_db($result);
       return $product;
    }

    public function check_product( $id ) {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT id, productId, rest_uploaded, rest_status FROM $this->table_name  WHERE id = $id" );
        return $result;
    }

    public function is_allow( $id ) {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT allow_send FROM $this->table_name  WHERE id = $id" );
        return $result;
    }

    public function add_product( Product $product) {
        global $wpdb;
        $format = array();
        $result = $wpdb->insert( $this->table_name, $product->get_data_db(), $format );
        return $result;
    }

    public function update_product( Product $product ) {
        global $wpdb;
        $result = $wpdb->update( $this->table_name, $product->get_data_db(), array( 'id' => $product->get_post_id() ) );
        return $result;
    }

    public function delete_product( $id ) {
        global $wpdb;
        $result = $wpdb->delete( $this->table_name, array( 'id' => $id ) );
        return $result;
    }

    public function update_rest_status( $id, $status ) {
        global $wpdb;
        $result = $wpdb->update( $this->table_name, array( 'rest_status' => $status ), array( 'id' => $id ) );
        return $result;
    }

    public function set_error_field( $id, $fields )
    {
        global $wpdb;
        $result = $wpdb->update( $this->table_name, array( 'rest_error_fields' => serialize( $fields ) ), array( 'id' => $id ) );
        return $result;
    }

    public function get_error_field( $id )
    {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT rest_error_fields FROM $this->table_name  WHERE id = $id" );
        if ( $result ) {
            return  unserialize( $result->rest_error_fields );
        }
        return $result;
    }

    public function set_uploaded( $id )
    {
        global $wpdb;
        $result = $wpdb->update( $this->table_name, array( 'rest_uploaded' => true ), array( 'id' => $id ) );
        return $result;
    }
}