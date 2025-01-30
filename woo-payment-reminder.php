<?php
/**
 * Plugin Name: Woo Payment Reminder
 * Plugin URI: https://github.com/nobodyguy/woo-payment-reminder
 * Description: Automatically sends payment reminder emails after a configurable number of days.
 * Version: 1.0.0
 * Author: Jan Gnip
 * Author URI: https://github.com/nobodyguy
 * Text Domain: woo-payment-reminder
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WPR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Load plugin translations
function wpr_load_textdomain() {
    load_plugin_textdomain( 'woo-payment-reminder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wpr_load_textdomain' );

// Register custom email template
function wpr_register_custom_email( $email_classes ) {
    require_once plugin_dir_path( __FILE__ ) . 'classes/class-wc-email-payment-reminder.php';
    $email_classes['WC_Email_Payment_Reminder'] = new WC_Email_Payment_Reminder();
    return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'wpr_register_custom_email' );

// Schedule cron job on activation
function wpr_schedule_cron() {
    if ( ! wp_next_scheduled( 'wpr_send_payment_reminders' ) ) {
        wp_schedule_event( time(), 'daily', 'wpr_send_payment_reminders' );
    }
}
register_activation_hook( __FILE__, 'wpr_schedule_cron' );

// Remove cron job on deactivation
function wpr_remove_cron() {
    wp_clear_scheduled_hook( 'wpr_send_payment_reminders' );
}
register_deactivation_hook( __FILE__, 'wpr_remove_cron' );

// Send payment reminders via cron
function wpr_send_payment_reminders() {
    $email = WC()->mailer()->emails['WC_Email_Payment_Reminder'];
    if ( ! $email || 'yes' !== $email->enabled ) {
        return;
    }

    $days_to_send_first = intval( $email->get_option( 'days_to_send_first', 7 ) );
    $days_to_send_second = intval( $email->get_option( 'days_to_send_second', 10 ) );
    $reminder_interval = 3;

    $args = [
        'status'       => ['pending', 'on-hold'],
        'date_created' => '<' . strtotime( "-{$days_to_send_first} days" ),
    ];

    $orders = wc_get_orders( $args );

    foreach ( $orders as $order ) {
        $order_id = $order->get_id();
        $last_reminder_sent = get_post_meta( $order_id, '_payment_reminder_sent', true );

        if ( $last_reminder_sent && ( time() - $last_reminder_sent ) < ( $reminder_interval * DAY_IN_SECONDS ) ) {
            continue;
        }

        do_action( 'send_payment_reminder_email', $order_id );
        update_post_meta( $order_id, '_payment_reminder_sent', time() );
    }
}
add_action( 'wpr_send_payment_reminders', 'wpr_send_payment_reminders' );
