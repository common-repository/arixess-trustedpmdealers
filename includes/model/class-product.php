<?php namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product
{
    //public static $new_product = true;
    private $data;

    public function __construct() {
        $this->data = array();
    }

    public function set_data_from_db( $data ) {
       if ( $data ) {
           $data['images'] = unserialize( $data['images'] );
           if ( \strlen( $data['yearValue'] ) > 4 ) {
               $data['yearValue'] = unserialize( $data['yearValue'] );
           }
           if ( $data['categories'] ){
               $data['categories'] = unserialize( $data['categories'] );
           }
	       if ( $data['specialPrice'] ){
		       $data['specialPrice'] = unserialize( $data['specialPrice'] );
	       }
	       if ( $data['priceTiers'] ){
		       $data['priceTiers'] = unserialize( $data['priceTiers'] );
	       }
       }
       $this->data = $data;
    }

    public function set_data_from_post_request( $post_id, $attr ) {
        $this->data = $this->parse_post_request( $post_id, $attr );
    }

    public function get_post_id() {
        if ( isset( $this->data['id'] ) ) {
            return $this->data['id'];
        } else {
            return false;
        }
    }

    public function get_tpmd_post_id() {
        if ( isset( $this->data['productId'] ) ) {
            return $this->data['productId'];
        } else {
            return false;
        }
    }

    public function get_field( $field ) {

        if ( empty( $this->data['priceCurrency'] ) ){
            $this->data['priceCurrency'] = Option_Db_Dao::get_instance()->get_api_currency();
        }

        return isset( $this->data[ $field ] ) ? $this->data[ $field ] : '';
    }

    public function set_field($field, $val){
        return 	$this->data[ $field ] = $val;
    }

    public function get_data_db() {
        $data = $this->data;
        $data['images'] = serialize( $data['images'] );
        if ( \is_array($data['yearValue'] ) ) {
            $data['yearValue'] = serialize( $data['yearValue'] );
        }
        if ( $data['categories']){
            $data['categories'] = serialize( $data['categories'] );
        }
        if($data['specialPrice']){
	        $data['specialPrice'] = serialize( $data['specialPrice'] );

        }
	    if($data['priceTiers']){
		    $data['priceTiers'] = serialize( $data['priceTiers'] );

	    }
        return $data;
    }

    public function get_data_rest() {
        $data = $this->data;
        unset( $data['id'] );
        return $data;
    }

    private function parse_post_request( $post_id, $attr ) {
        $data = array();

        foreach ( $_POST as $key => $value ) {
            if ( preg_match( '/^tpmd-/', $key ) ) {
                $data[ str_replace('tpmd-', '', $key) ] = $value;
            }

        }

	    $categories = array();
        if(isset($data['categories'])){
	        foreach ( $data['categories'] as $key => $value ){
		        $categories[] = $value;
	        }
        }


        if(!$data['specialPrice']['premium']){
	        $data['specialPrice'] = null;
        }else{
	        $data['specialPrice']['startDate'] = $_POST['_sale_price_dates_from'];
	        $data['specialPrice']['endDate'] = $_POST['_sale_price_dates_to'];
        }

        foreach ($data['priceTiers'] as $k=>$priceTier){
	        if(!$priceTier['qty'] or !$priceTier['premium']){
		        unset($data['priceTiers'][$k]);
	        }
        }

        if(empty($data['priceTiers']) && !isset($_POST['old-priceTier'])){
	        unset($data['priceTiers']);
        }


	    if(!empty($data['oldProductId']) && ($data['productId'] != $data['oldProductId'])){
		    $data['newProductId'] = $data['productId'];
		    $data['productId'] = $data['oldProductId'];

	    }

	    unset($data['oldProductId']);

        $data['categories'] = $categories;
        $data['id'] = $post_id;
        $data['productUrl'] = get_permalink( $post_id );
        $data['images'] = array();

        $data['images'][0] = (get_the_post_thumbnail_url( $post_id )?get_the_post_thumbnail_url( $post_id ):wc_placeholder_img_src());
        $woo_product = new \WC_Product( $post_id );
        $attachment_ids = $woo_product->get_gallery_attachment_ids();
        if ( $attachment_ids ) {
            $data['images'][1] = wp_get_attachment_url( $attachment_ids[0] );
        }
        if ( ! isset( $_POST['tpmd-allow_send'] ) ) {
            $data['allow_send'] = 'no';
        } else {
            $data['allow_send'] = 'yes';
        }
        trpmd_log($data);
        return $data;
    }
}