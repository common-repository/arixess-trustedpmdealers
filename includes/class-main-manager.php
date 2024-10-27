<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Main_Manager extends Abstract_Manager
{
    private $loader;

    /*
    * meta info
    */
    private $plugin_slug;
    private $version;
    private $api_key;

    public function __construct() {
        parent::__construct();
        $this->loader = new Loader();
        $this->plugin_slug = 'trusted-pm-dealers-slug';
        $this->version = '1.0';
        $this->api_key = $this->option_db_dao->get_api_key();
    }

    public function run() {
        $this->product_rest_dao->set_key( $this->option_db_dao->get_api_key() );
        $this->add_hook();
        $this->create_option_page();
        $this->loader->run();
    }

    private function add_hook() {
        /*
         * Create TrustedPMDealers tab on product edit page
         */
        $this->loader->add_filter( 'manage_edit-product_columns', $this, 'add_column_to_product_table', 10, 1 );
        $this->loader->add_action( 'manage_product_posts_custom_column', $this, 'add_column_value_to_product_table', 10, 2 );
        $this->loader->add_filter( 'product_type_options', Product_Panel_Hook::get_instance(), 'add_allow_product_option', 10, 1 );

        /*
         * Add to Woocommerce general tab product edit page
         */
        $this->loader->add_filter( 'woocommerce_product_options_pricing', $this, 'general_product_data', 10, 1 );

        /*
        * Add to Woocommerce inventory tab product edit page
        */
        $this->loader->add_filter( 'woocommerce_product_options_stock_status', $this, 'inventory_product_data', 10, 1 );

        /*
        * Create TrustedPMDealers panel on product edit page
        */
        $this->loader->add_filter( 'woocommerce_product_data_tabs', $this, 'create_product_tab', 10, 1 );
        $this->loader->add_action( 'woocommerce_product_data_panels', $this, 'create_product_panel', 10, 0 );
        $this->loader->add_action( 'woocommerce_process_product_meta', $this, 'save_product_data', 10, 1 );
        $this->loader->add_action( 'deleted_post', $this, 'delete_product', 10, 1);

        /*
         * script and style for TPMD plugin
         */
        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'load_scripts', 10, 1);

        /*
         * AJAX handlers for TPMD plugin
         */
        $this->loader->add_action( 'wp_ajax_get_all_attributes_ajax', $this->ajax_manager, 'get_all_attributes_ajax', 10, 0 );
        $this->loader->add_action( 'wp_ajax_set_option_data_ajax', $this->ajax_manager, 'save_option_ajax', 10, 0 );
        $this->loader->add_action( 'wp_ajax_get_mint_state', $this->ajax_manager, 'get_mint_state_ajax', 10, 0 );
        $this->loader->add_action( 'wp_ajax_get_grade_service', $this->ajax_manager, 'get_grade_service_ajax', 10, 0 );
        $this->loader->add_action( 'wp_ajax_get_grade_service_root', $this->ajax_manager, 'get_grade_service_root_ajax', 10, 0 );
        $this->loader->add_action( 'wp_ajax_get_categories_attr', $this->ajax_manager, 'get_categories_attr_ajax', 10, 0 );

        /*
         * Notice
         */
        $this->loader->add_action('admin_notices', $this, 'tpmd_admin_notice', 10, 0 );
    }

    // submit-update-delete product
    public function save_product_data( $post_id ) {
        if ( ! $this->api_key ) {
            return;
        }
        $this->set_error( $post_id, null );
        $product = new Product();
        $product->set_data_from_post_request( $post_id, $this->option_db_dao->get_attributes() );
        $exist_product = $this->product_db_dao->check_product( $post_id );

        try {
            if ( ! $exist_product ) { // add new product
                $this->add_product( $product );
                trpmd_log( "ADD" );
            } else { // update existing product
                trpmd_log( "UPDATE" );
                $this->update_product( $product, $exist_product );
            }
        } catch ( \Exception $e ) {
            $this->set_error( $post_id, null );
        }
    }

    private function add_product( Product $product ) {
        $this->product_db_dao->add_product( $product );
        if ( isset( $_POST['post_status'] ) && 'publish' == $_POST['post_status'] ) {
            $this->add_product_rest( $product );
        }
    }

    private function update_product( Product $product, $exist_product ) {
        $this->product_db_dao->update_product( $product );
        if ( isset( $_POST['post_status'] ) && 'publish' == $_POST['post_status'] ) {
            if ( $exist_product->rest_uploaded ) {
                $this->update_product_rest( $product );
            } else {
                $this->add_product_rest( $product );
            }
        }
    }

    private function add_product_rest( Product $product ) {
        if ( ! $this->is_allow_send() ) {
            return;
        }
        $rest_response = $this->product_rest_dao->submit_product( $product );
        if ( 201 == $rest_response['statusCode'] ) {
            $this->product_db_dao->set_uploaded( $product->get_post_id() );
            $this->set_success( $product->get_post_id(), null );
        } else if ( 422 == $rest_response['statusCode'] ) {
            $this->set_error( $product->get_post_id(), $rest_response );
        } else {
            throw new \Exception( 'Error Add Product to TPMD' );
        }
        trpmd_log($rest_response);
    }

    private function update_product_rest( Product $product ) {
        if ( ! $this->is_allow_send() ) {
            return;
        }
        $rest_response = $this->product_rest_dao->update_product( $product );
        if ( 422 == $rest_response['statusCode'] ) {
            $this->set_error( $product->get_post_id(), $rest_response );
        } else if ( 200 == $rest_response['statusCode'] ) {
            $this->set_success( $product->get_post_id(), null );
        } else {
            throw new \Exception( 'Error Update Product to TPMD' );
        }
        trpmd_log($rest_response);
    }

    public function delete_product( $id ) {
        if ( ! $this->api_key ){
            return;
        }
        try {
            $product_id_tpmd = $this->product_db_dao->check_product( $id );
            $this->product_db_dao->delete_product( $id );
            if ( $product_id_tpmd->productId ) {
                $rest_response = $this->product_rest_dao->delete_product( $product_id_tpmd->productId );
            }
            if ( 204 == $rest_response['statusCode'] ) {
                $this->set_success( $id, null );
            } else {
                throw new \Exception( 'Error delete product from TPMD' );
            }
        } catch ( \Exception $e ) {
            $this->set_error( $id, null );
        }
        trpmd_log($rest_response);
    }

    private function set_error($id, $rest_response) {
        $this->product_db_dao->update_rest_status( $id, false );
        $this->product_db_dao->set_error_field( $id, $rest_response );
        if ( $this->is_allow_send() ){
            Notification::set_notification( Notification::ERROR );
        }
    }

    private function set_success( $id ) {
        $this->product_db_dao->update_rest_status( $id, true );
        $this->product_db_dao->set_error_field( $id, null );
        Notification::set_notification( Notification::SUCCESS );
    }

    // create panels
    public function create_product_tab( $product_data_tabs ) {
        $panel = Product_Panel_Hook::get_instance();
        return $panel->create_product_tab( $product_data_tabs );
    }

    public function create_product_panel() {
        global $post;
        $panel = Product_Panel_Hook::get_instance();
        $attributes = $this->option_db_dao->get_attributes();
        $error_fields = $this->product_db_dao->get_error_field( $post->ID );
        $product = $this->product_db_dao->get_product_by_id( $post->ID );
        $panel->create_product_panel( $product, $attributes, $error_fields );
    }

    public function general_product_data(){
        global $post;
        $panel = Product_Panel_Hook::get_instance();
        $attributes = $this->option_db_dao->get_attributes();
        $error_fields = $this->product_db_dao->get_error_field( $post->ID );
        $product = $this->product_db_dao->get_product_by_id( $post->ID );
        $panel->general_product_data( $product, $attributes, $error_fields );
    }

    public function inventory_product_data(){
        global $post;
        $panel = Product_Panel_Hook::get_instance();
        $attributes = $this->option_db_dao->get_attributes();
        $error_fields = $this->product_db_dao->get_error_field( $post->ID );
        $product = $this->product_db_dao->get_product_by_id( $post->ID );
        $panel->inventory_product_data( $product, $attributes, $error_fields );
    }

    public function add_column_to_product_table( $column ) {
        $custom_column = Column_Value_Hook::get_instance();
        return $custom_column->add_column_to_product_table( $column );
    }

    public function add_column_value_to_product_table( $column, $post_id ) {
        $status = $this->product_db_dao->check_product( $post_id );
        if ( ! $status ) {
            $status = Column_Value_Hook::NOT_LOADED;
         } else if ( ! $status->rest_status ) {
            $status = Column_Value_Hook::ERROR;
        } else {
            $status = Column_Value_Hook::SUCCESS;
        }
        $custom_column = Column_Value_Hook::get_instance();
        $custom_column->add_column_value_to_product_table( $column, $post_id, $status );
    }

    public function get_version() {
        return $this->version;
    }

    public function load_scripts($page_slug) {
        global $post;
        /*
         * include scripts only on the required pages
         */
        $optionPage = Option_Page_Hook::get_instance();
        if ($page_slug != 'toplevel_page_' . $optionPage->get_page_name() &&
            $page_slug != 'edit.php' && $page_slug != 'post.php' &&
            $page_slug != 'post-new.php'
        ) {
            return;
        }

        wp_enqueue_style( 'tpmd_admin_css', TR_PMD_URL . 'assets/css/style.css', '', '1.1' );
        wp_enqueue_style( 'tooltipster.bundle.min', TR_PMD_URL . 'assets/css/tooltipster.bundle.min.css' );
        wp_enqueue_style( 'tpmd_data_picker_css', TR_PMD_URL . 'assets/css/datepicker3.min.css' );
        wp_enqueue_style( 'tpmd_treeselect_css', TR_PMD_URL . 'assets/css/jquery.treeselect.css' );

        wp_enqueue_script( 'tpmd_treeselect_js', TR_PMD_URL . 'assets/js/jquery.treeselect.min.js', array('jquery'), null, true );
        wp_enqueue_script( 'tooltipster.bundle.min', TR_PMD_URL . 'assets/js/tooltipster.bundle.min.js', array('jquery'), null, true );
        //wp_enqueue_script( 'tpmd_year_picker_script', TR_PMD_URL . 'assets/js/bootstrap-datepicker.min.js', array('jquery'), null, true );
        wp_enqueue_script( 'tpmd_admin_script', TR_PMD_URL . 'assets/js/script.js', array('jquery', 'jquery-ui-accordion'), '1.1', true );
        wp_enqueue_script( 'tpmd_product_script', TR_PMD_URL . 'assets/js/tpmd-product-script.js', array('jquery', 'jquery-ui-accordion'), '1.1', true );
        if ( isset( $post ) ) {
            wp_localize_script('tpmd_product_script', 'tpmd_post_ajax',
                array(
                    'tpmd_id' => $post->ID,
                )
            );
        }
    }

    private function create_option_page() {
        $option_page = Option_Page_Hook::get_instance();
        $page_data = $this->option_db_dao->get_all_option();
        $option_page->set_page_data( $page_data );

    }

    public function tpmd_admin_notice() {
        Notification::get_notification();
        Notification::reset_notification();
    }

    private function is_allow_send(){
        return isset( $_POST['tpmd-allow_send'] ) ? true : false;
    }
}