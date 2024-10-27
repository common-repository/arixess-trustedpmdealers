<?php

namespace TrustedPMDealers;

if ( ! defined ('ABSPATH') ) {
    exit;
}

class Notification
{
    const ERROR = 'notification_error';
    const SUCCESS = 'notification_success';

    public static function set_notification( $type ) {
        update_option( 'tpmd_admin_notice', $type );
    }

    public static function get_notification() {
        $type = get_option( 'tpmd_admin_notice', false );
        switch ( $type ) {
            case self::SUCCESS :
                require TR_PMD_DIR . 'includes/views/notification/success.php';
                break;
            case self::ERROR :
                require TR_PMD_DIR . 'includes/views/notification/error.php';
                break;
            default:
                break;
        }
    }

    public static function reset_notification() {
        update_option( 'tpmd_admin_notice', false );
    }
}