<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Abstract_Dao
{
    private static $_instances = array();

    public final static function get_instance() {
        $class = get_called_class();
        if ( ! isset( self::$_instances[ $class ] ) ) {
            self::$_instances[ $class ] = new $class();
        }
        return self::$_instances[ $class ];
    }
}