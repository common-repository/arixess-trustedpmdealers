<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Auto_Loader {

    private $dirs = array();

    public function __construct() {
        spl_autoload_register( array( $this, 'loader' ) );
    }

    public function register() {
        $this->dirs = array(
            TR_PMD_DIR,
            TR_PMD_DIR . 'includes/',
            TR_PMD_DIR . 'includes/hooks/',
            TR_PMD_DIR . 'includes/views/',
            TR_PMD_DIR . 'includes/dao/',
            TR_PMD_DIR . 'includes/dao/db/',
            TR_PMD_DIR . 'includes/dao/db/wpdb_impl/',
            TR_PMD_DIR . 'includes/dao/rest/',
            TR_PMD_DIR . 'includes/dao/rest/rest_impl/',
            TR_PMD_DIR . 'includes/model/',
            TR_PMD_DIR . 'includes/notification/'
        );
    }

    public function loader( $classname ) {
        $len = strlen( TR_PMD_NS . '\\' );
        $classname = substr( $classname, $len );
        $classname = strtolower( $classname );
        $classname = str_replace('_','-', $classname);

        foreach ( $this->dirs as $dir ) {
            $file = "{$dir}class-{$classname}.php";
            if ( file_exists( $file ) ) {

                require_once $file;
                return;
            }
        }
    }
}
$auto_loader = new Auto_Loader();
$auto_loader->register();