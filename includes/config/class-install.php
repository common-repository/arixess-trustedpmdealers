<?php namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Install
{
    public static function on_activation(){

        if ( get_option('tpmd_db_version') == '1.1' ) {
            //return;
        }

        global $wpdb;

        $table_name = $wpdb->prefix . 'tpmd_product';

        $sql = "CREATE TABLE " . $table_name . " (
                   `id` BIGINT(20) NOT NULL UNIQUE,
                   `productId` VARCHAR(255),
                   `newProductId` VARCHAR(255), 
                   `name` VARCHAR(255),   
                   `format` VARCHAR(55),
                   `volume` VARCHAR(55), 
                   `categories` TEXT,
                   `specialPrice` TEXT,
                   `priceTiers` TEXT,
                   `condition` VARCHAR(55), 
                   `metal` VARCHAR(55),
                   `composite` VARCHAR(255), 
                   `purity` INTEGER,
                   `weight` NUMERIC(7,4),
                   `weightMeasurement` VARCHAR(55),
                   `preciousMetalWeight` NUMERIC(7,4),
                   `preciousMetalWeightMeasurement` VARCHAR(55),
                   `yearType` VARCHAR(55),
                   `yearValue` TINYTEXT,    
                   `productionType` VARCHAR(55),
                   `strikeType` VARCHAR(55),
                   `mint` VARCHAR(55),
                   `mintCountry` VARCHAR(255),
                   `mintState` VARCHAR(255),
                   `mintMark` VARCHAR(255),
                   `gradingService` VARCHAR(255),
                   `grade` VARCHAR(255),
                   `specialFeature` VARCHAR(255),
                   `damage` VARCHAR(255),
                   `thickness` NUMERIC(7,2),
                   `diameter` NUMERIC(7,2),
                   `faceValue` NUMERIC(7,2),
                   `faceValueCurrencySign` VARCHAR(255),
                   `orientation` VARCHAR(55),
                   `shape` VARCHAR(255),
                   `edge` VARCHAR(255),
                   `rim` VARCHAR(255),
                   `priceType` VARCHAR(55),
                   `priceCurrency` VARCHAR(10),
                   `premiumType` VARCHAR(55),
                   `premium` NUMERIC(7,2),
                   `productUrl` VARCHAR(255),
                   `images` TEXT,    
                   `status` VARCHAR(255),
                   `rest_uploaded` BOOLEAN DEFAULT FALSE,
                   `allow_send` VARCHAR(5),
                   `rest_status` BOOLEAN DEFAULT FALSE,
                   `rest_error_fields` TEXT
                    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

    }
}