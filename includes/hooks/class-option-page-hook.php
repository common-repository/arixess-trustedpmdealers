<?php namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Option_Page_Hook extends Abstract_Hook
{
    /*
     * fields for admin menu
     */
    private $page_title;
    private $menu_title;
    private $capability;
    private $menu_slug;
    private $callback;
    private $icon_url;
    private $position;

    /*
     *  fields for setting panel
     */
    private $page_data = null;

    public function __construct() {
        $this->page_title = __( 'TrustedPMDealers','tr_pmd_locale' );
        $this->menu_title = __( 'TrustedPMDealers', 'tr_pmd_locale' );
        $this->capability = 'manage_options';
        $this->menu_slug = 'tpmd_option_page';
        $this->callback = array( $this, 'view' );
        $this->icon_url = 'dashicons-cart';
        $this->position = 62;
        $this->add_menu_page();
    }

    public function get_page_name() {
        return $this->menu_slug;
    }

    private function add_menu_page() {
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            $this->callback,
            $this->icon_url,
            $this->position
        );
    }

    public function set_page_data( Option $page_data ) {
        $this->page_data = $page_data;
    }

    public function view() {
        $data = $this->page_data;
        require TR_PMD_DIR . 'includes/views/option_page.php';
    }
}