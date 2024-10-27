<?php namespace TrustedPMDealers;

/*
 * Plugin Name:       TrustedPMDealers
 * Description:       The plugin is to make it easy uploading precious metal products to TrustedPMDealers catalogues with all required information
 * Version:           1.1
 * Author:            TrustedPMDealers
 * Author URI:        https://trustedpmdealers.com/
 * Text Domain:       tr_pmd_locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    exit;
}



class TrustedPMDealers {

    private static $_instance;

    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function install() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/config/class-install.php';
        Install::on_activation();
    }

    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
	    if ( get_option('tpmd_db_version') != '1.1' ) {
		    TrustedPMDealers::install();
		    $this->update_db();
	    }

    }

    private function define_constants() {
        $this->define( 'TR_PMD_DIR', plugin_dir_path( __FILE__ ) );
        $this->define( 'TR_PMD_URL', plugin_dir_url( __FILE__ ) ) ;
        $this->define( 'TR_PMD_NS', __NAMESPACE__ );
        $this->define( 'TR_PMD_API_KEY', 'tpmd-api-key' );
        $this->define( 'TR_PMD_API_CUR', 'tpmd-api-cur' );
        $this->define( 'TR_PMD_API_ATTR', 'tpmd-api-attr' );
        $this->define( 'TR_PMD_API_CLIENT', 'WP_1.1' );
        $this->define( 'TR_PMD_API_URL', 'https://business.trustedpmdealers.com/api/v2/' );
    }

    private function includes() {
        require_once TR_PMD_DIR . 'includes/config/class-auto-loader.php';
    }

    private function init_hooks() {
        $tr_pmd_manager = new Main_Manager();
        $tr_pmd_manager->run();
    }

    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    private function update_db(){
	    global $wpdb;
	    $table_name = $wpdb->prefix . 'tpmd_product';

	    $attr_rest = Attributes_Rest_Dao::get_instance();
	    $opt_db = Option_Db_Dao::get_instance();
	    $product_db_dao = Product_Db_Dao::get_instance();
	    $new_attr = $attr_rest->get_all_attributes( $opt_db->get_api_key() );
	    if ( ! $opt_db->set_all_option( $opt_db->get_api_key(), $opt_db->get_api_key(), $new_attr ) ){
		    throw new \Exception( __( 'Save unsuccessful, check and try again', 'tr_pmd_locale' ) );
	    }
		$ids = $wpdb->get_results("SELECT id FROM $table_name", ARRAY_A);
	    foreach ($ids as $p){
		    $product = $product_db_dao->get_product_by_id( $p['id'] );
		    $product->set_field('priceType', array_search($product->get_field('priceType'), $new_attr['priceType']));
		    $product->set_field('premiumType', array_search($product->get_field('premiumType'), $new_attr['premiumType']));
		    $product->set_field('status', array_search($product->get_field('status'), $new_attr['status']));
		    $product->set_field('format', array_search($product->get_field('format'), $new_attr['format']));
		    $product->set_field('volume', array_search($product->get_field('volume'), $new_attr['volume']));
		    $product->set_field('condition', array_search($product->get_field('condition'), $new_attr['condition']));
		    $product->set_field('metal', array_search($product->get_field('metal'), $new_attr['metal']));

		    if(isset($new_attr['purity'][$product->get_field('metal')])){
			    $product->set_field('purity', array_search($product->get_field('purity'), $new_attr['purity'][$product->get_field('metal')]));
		    }

		    $product->set_field('weightMeasurement', array_search($product->get_field('weightMeasurement'), $new_attr['weightMeasurement']));
		    $product->set_field('preciousMetalWeightMeasurement', array_search($product->get_field('preciousMetalWeightMeasurement'), $new_attr['preciousMetalWeightMeasurement']));
		    $product->set_field('yearType', array_search($product->get_field('yearType'), $new_attr['yearType']));
		    $product->set_field('productionType', array_search($product->get_field('productionType'), $new_attr['productionType']));

		    $product->set_field('strikeType', array_search($product->get_field('strikeType'), $new_attr['strikeType']));
		    $product->set_field('mint', array_search($product->get_field('mint'), $new_attr['mint']));
		    $product->set_field('specialFeature', array_search($product->get_field('specialFeature'), $new_attr['specialFeature']));
		    $product->set_field('damage', array_search($product->get_field('damage'), $new_attr['damage']));

		    $product->set_field('orientation', array_search($product->get_field('orientation'), $new_attr['orientation']));
		    $product->set_field('shape', array_search($product->get_field('shape'), $new_attr['shape']));
		    $product->set_field('edge', array_search($product->get_field('edge'), $new_attr['edge']));
		    $product->set_field('rim', array_search($product->get_field('rim'), $new_attr['rim']));

		    $product->set_field('mintCountry', array_search($product->get_field('mintCountry'), $new_attr['mintCountry']));

		    if(isset($new_attr['mintState'][$product->get_field('mintCountry')])){
				$product->set_field('mintState', array_search($product->get_field('mintState'), $new_attr['mintState'][$product->get_field('mintCountry')]));
			}
		    $product->set_field('gradingService', array_search($product->get_field('gradingService'), $new_attr['gradingService']));

			if(isset($new_attr['grade'][$product->get_field('gradingService')])){
			    $product->set_field('grade', array_search($product->get_field('grade'), $new_attr['grade'][$product->get_field('gradingService')]));
		    }
			$cats = array(1=>"Ancient Coins (0 – 700 BC)", 2=>"Bullion (Investment Metals)",
			              97=>"Bullion Coins", 98=>"Bullion Bars", 101=>"Bullion Rounds", 4=>"Commemorative Coins",
			              12=>"UK Coins", 72=>"Sovereigns", 73=>"Type coins", 74=>"Pound", 75=>"Pence", 76=>"Shillings",
			              44=>"USA Coins", 77=>"Territorial coins", 78=>"Type coins", 82=>"Cent", 83=>"Dime", 84=>"Dollar",
			              85=>"Double Eagles", 86=>"Eagles", 87=>"Gold Dollars", 88=>"Half Cent", 89=>"Half Dime", 90=>"Half Dollar",
			              91=>"Half Eagles", 92=>"Large Cent", 93=>"Nickel", 94=>"Quarter", 95=>"Quarter Eagles",
			              96=>"Small Cent", 79=>"First Spouse Coins", 99=>"Collectible Coins", 100=>"Numismatic Coins",
			              102=>"Hand Crafted Art Collectibles", 103=>"Holidays", 104=>"Christmas");
			$new_cats= [];
			foreach ($product->get_field('categories') as $cat){
				$new_cats[] = array_search($cat, $cats);
			}
		    $product->set_field('categories', $new_cats);
	    }

	    if($product){
		    $product_db_dao->update_product($product);
	    }


	    update_option( "tpmd_db_version", "1.1" );
    }

}

function trpmd_log($message){
    $date = date('Y-m-d H:i:s');
    if ( is_array( $message ) || is_object( $message ) ) {
        $message = print_r($message, true);
    }
    error_log('[ ' . $date . " ] $message\r\n", 3, TR_PMD_DIR . '/error.log' );
}

if ( ! function_exists('write_log')) {
    function write_log ( $log )  {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}

add_action( 'admin_init', 'TrustedPMDealers\init' );
add_action( 'admin_menu', 'TrustedPMDealers\init' );

function init() {
    TrustedPMDealers::get_instance();
}