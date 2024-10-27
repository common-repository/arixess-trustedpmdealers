<?php namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Column_Value_Hook extends Abstract_Hook
{
    private $column_name;

    const SUCCESS = 'success';
    const ERROR = 'error';
    const NOT_LOADED = 'not-loaded';

    public function __construct() {
        $this->column_name = __( 'TPMD', 'tr_pmd_locale' );
    }

    public function add_column_to_product_table( $columns ) {
        $columns[ $this->column_name ] = $this->column_name;
        return $columns;
    }

    public function add_column_value_to_product_table( $column, $post_id, $status ) {
        if ( $column == $this->column_name ) {
            if ( $status == self::SUCCESS ) {
                $file_name = self::SUCCESS;
            } else if ( $status == self::ERROR ) {
                $file_name = self::ERROR;
            } else {
                $file_name = self::NOT_LOADED;
            }
            $this->render_hook( $file_name, $post_id );
        }
    }

    public function render_hook( $file_name, $post_id )
    {
        require TR_PMD_DIR . "includes/views/product_column/{$file_name}.php";
    }
}