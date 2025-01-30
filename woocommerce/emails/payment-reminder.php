<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( __( 'Hello %s,', 'woo-payment-reminder' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<p>
    <?php printf( 
        __( 'We noticed that your order <strong>#%s</strong> is still awaiting payment.', 'woo-payment-reminder' ), 
        esc_html( $order->get_order_number() ) 
    ); ?>
</p>

<p><?php _e( 'To complete your purchase, please proceed with the payment at your earliest convenience.', 'woo-payment-reminder' ); ?></p>

<p>
    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" 
       style="background-color: #0071a1; color: #ffffff; padding: 10px 15px; text-decoration: none; font-weight: bold;">
        <?php _e( 'Pay Now', 'woo-payment-reminder' ); ?>
    </a>
</p>

<p><?php _e( 'If you have already completed the payment, please ignore this message.', 'woo-payment-reminder' ); ?></p>

<p><?php _e( 'Thank you for shopping with us.', 'woo-payment-reminder' ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
