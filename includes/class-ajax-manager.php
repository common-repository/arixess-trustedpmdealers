<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ajax_Manager
{
    private $option_db_dao;
    private $attr_rest_dao;

    public function __construct( Option_Db_Dao $option_db_dao, Attributes_Rest_Dao $attr_rest_dao ) {
        $this->option_db_dao = $option_db_dao;
        $this->attr_rest_dao = $attr_rest_dao;
    }

    public function get_categories_attr_ajax() {
        $post_id = isset( $_POST['tmpd_post_id'] ) ? $_POST['tmpd_post_id'] : 0;
        $selected_cat = [];
        $attr = $this->option_db_dao->get_attributes();
        if ( $post_id ){
            $product = Product_Db_Dao::get_instance()->get_product_by_id( $post_id );
            $selected_cat = $product->get_field('categories');
        } else {
            $selected_cat = null;
        }
        Product_Form_Ajax_Hook::get_instance()->get_categories_attr_ajax( $attr, $selected_cat );
    }

    public function get_grade_service_root_ajax() {
        $attr = $this->option_db_dao->get_attributes();
        Product_Form_Ajax_Hook::get_instance()->get_grade_service_root_ajax( $attr );
    }

    public function get_grade_service_ajax() {
        $attr = $this->option_db_dao->get_attributes();
        Product_Form_Ajax_Hook::get_instance()->get_grade_service_ajax( $attr );
    }

    public function get_mint_state_ajax() {
        $attr = $this->option_db_dao->get_attributes();
        Product_Form_Ajax_Hook::get_instance()->get_mint_state_ajax( $attr );
    }

    public function get_all_attributes_ajax() {
        $this->attr_rest_dao->get_all_attributes_ajax();
    }

    public function save_option_ajax() {
        $this->option_db_dao->save_option_ajax( $this->attr_rest_dao );
    }
}