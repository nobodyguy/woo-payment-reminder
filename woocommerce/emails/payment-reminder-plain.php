<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php printf( __( 'Hello %s,', 'woo-payment-reminder' ), esc_html( $order->get_billing_first_name() ) ); ?>

<?php printf( __( 'We noticed that your order #%s is still awaiting payment.', 'woo-payment-reminder' ), esc_html( $order->get_order_number() ) ); ?>


<?php _e( 'To complete your purchase, please proceed with the payment at your earliest convenience.', 'woo-payment-reminder' ); ?>


<?php _e( 'Payment link:', 'woo-payment-reminder' ); ?>
<?php echo esc_url( $order->get_checkout_payment_url() ); ?>


<?php _e( 'If you have already completed the payment, please ignore this message.', 'woo-payment-reminder' ); ?>


<?php _e( 'Thank you for shopping with us.', 'woo-payment-reminder' ); ?>
