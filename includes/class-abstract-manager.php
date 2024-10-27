<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Abstract_Manager
{
    protected $option_db_dao;
    protected $product_db_dao;
    protected $product_rest_dao;
    protected $attr_rest_dao;
    protected $ajax_manager;

    public function __construct() {
        $this->option_db_dao = Option_Db_Dao::get_instance();
        $this->attr_rest_dao = Attributes_Rest_Dao::get_instance();
        $this->product_db_dao = Product_Db_Dao::get_instance();
        $this->product_rest_dao = Product_Rest_Dao::get_instance();
        $this->ajax_manager = new Ajax_Manager( $this->option_db_dao, $this->attr_rest_dao);
    }
}