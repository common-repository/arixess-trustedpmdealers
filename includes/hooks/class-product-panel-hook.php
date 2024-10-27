<?php namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Panel_Hook extends Abstract_Hook
{
    private $label_tab;
    private $target_tab;
    private $err_fields;

    public function __construct() {
        $this->label_tab = __( 'TrustedPMDealers', 'tr_pmd_locale' );
        $this->target_tab = 'tpmd_product_data';
    }

    public function create_product_tab( $product_data_tabs ) {
        $product_data_tabs['trpmd-tab'] = array(
            'label'     => $this->label_tab,
            'target'    => $this->target_tab,
            'class'     => [],
        );
        return $product_data_tabs;
    }

    public static function add_allow_product_option( $product_type_options ) {
        global $post;
        $option_db_dao = Option_Db_Dao::get_instance();
        $is_allowed = Product_Db_Dao::get_instance()->is_allow( $post->ID );
        if($option_db_dao->get_api_key() != ''){
            $state='yes';
        } else{
            $state='no';
        }
        if ( isset( $post->ID ) &&  $is_allowed) {
            $state = $is_allowed->allow_send;
        }
        $product_type_options['tpmd-allow_send'] = array(
            'id'            => 'tpmd-allow_send',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'label'         => __( 'Send to TrustedPMDealers', 'tr_pmd_locale' ),
            'description'   => __( 'Allow send product to TrustedPMDealers.', 'tr_pmd_locale' ),
            'default'       => $state,
        );
        return $product_type_options;
    }

    public function general_product_data( Product $product, $attr, $err_fields ){
        if ( ! $attr ) {
            return;
        }
	    $specPriceCurr = Option_Db_Dao::get_instance()->get_api_currency();
        $this->err_fields = $err_fields;
        require TR_PMD_DIR . 'includes/views/product_tab/product-tab-general.php';
    }

    public function inventory_product_data( Product $product, $attr, $err_fields ){
        if ( ! $attr ) {
            return;
        }
        $this->err_fields = $err_fields;
        require TR_PMD_DIR . 'includes/views/product_tab/product-tab-inventory.php';
    }

    public function create_product_panel( Product $product, $attr, $err_fields ) {
        if ( ! $attr ) {
            require TR_PMD_DIR . 'includes/views/product_tab/product-tab-setup.php';
            return;
        }
        $this->err_fields = $err_fields;
        require TR_PMD_DIR . 'includes/views/product_tab/product-tab.php';
    }

    private function create_select( $label, $filed_name, Product $product, $attr, $required = false ) {
        require TR_PMD_DIR . 'includes/views/product_tab/ui/ui-select.php';
    }

    private function create_input( $label, $filed_name, Product $product, $input_class = '', $maxlenght = 255, $required = false ) {
        require TR_PMD_DIR . 'includes/views/product_tab/ui/ui-input.php';
    }

    private function create_js_obj($array_name, $array_item, $attr ) {
        $result = '';
        if ( isset( $attr ) ) {
            $purity = $attr[ $array_name ][ $array_item ];
            foreach ( $purity as $key => $value ) {
                $result .= ( '["'.$key.'", "' . $value . '"], ' );
            }
            $result = substr( $result, 0, -2 );
        }
        return $result;
    }
}