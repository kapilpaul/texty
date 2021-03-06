<?php

namespace Texty;

/**
 * Notification Class
 */
class Notifications {

    /**
     * Option key to hold the notifications
     */
    const OPTION_KEY = 'texty_notifications';

    /**
     * Notifications
     *
     * @var array
     */
    private $notifications = [];

    /**
     * Get a notification class
     *
     * @param string $key
     *
     * @return false|string
     */
    public function get( $key ) {
        $notifications = $this->all();

        if ( array_key_exists( $key, $notifications ) ) {
            return $notifications[ $key ];
        }

        return false;
    }

    /**
     * Get available notification classes
     *
     * @return array
     */
    public function all() {
        if ( $this->notifications ) {
            return $this->notifications;
        }

        $notifications = [
            'registration' => __NAMESPACE__ . '\Notifications\Registration',
            'comment'      => __NAMESPACE__ . '\Notifications\Comment',
        ];

        if ( class_exists( 'WooCommerce' ) ) {
            // WC Admin
            $notifications['order_admin_processing'] = __NAMESPACE__ . '\Notifications\OrderProcessingAdmin';
            $notifications['order_admin_complete']   = __NAMESPACE__ . '\Notifications\OrderCompleteAdmin';

            // WC Customers
            $notifications['order_customer_hold']       = __NAMESPACE__ . '\Notifications\OrderHoldCustomer';
            $notifications['order_customer_processing'] = __NAMESPACE__ . '\Notifications\OrderProcessingCustomer';
            $notifications['order_customer_complete']   = __NAMESPACE__ . '\Notifications\OrderCompleteCustomer';
        }

        $this->notifications = apply_filters( 'texty_available_notifications', $notifications );

        return $this->notifications;
    }

    /**
     * Get the name of the groups
     *
     * @return void
     */
    public function get_groups() {
        return apply_filters( 'texty_notification_groups', [ // phpcs:ignore
            'wp' => [
                'title'       => __( 'WordPress', 'texty' ),
                'description' => '',
                'available'   => true,
            ],
            'wc' => [
                'title'       => __( 'WooCommerce', 'texty' ),
                'description' => '',
                'available'   => class_exists( 'WooCommerce' ) ? true : false,
            ],
        ] ); // phpcs:ignore
    }

    /**
     * Retreive all the settings
     *
     * @return array
     */
    public function settings() {
        return get_option( self::OPTION_KEY, [] );
    }
}
