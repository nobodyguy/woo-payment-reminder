<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Email_Payment_Reminder extends WC_Email {

    public function __construct() {
        $this->id             = 'payment_reminder';
        $this->title          = __( 'Payment Reminder', 'woo-payment-reminder' );
        $this->description    = __( 'Sends a reminder email for unpaid orders after a configurable number of days.', 'woo-payment-reminder' );
        $this->heading        = __( 'Payment Reminder', 'woo-payment-reminder' );
        $this->subject        = __( 'Reminder: Complete your payment for order #{order_id}', 'woo-payment-reminder' );

        $this->template_html  = 'emails/payment-reminder.php';
        $this->template_plain = 'emails/payment-reminder-plain.php';

        $this->recipient = '';

        parent::__construct();

        add_action( 'send_payment_reminder_email', array( $this, 'trigger' ), 10, 1 );
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'woo-payment-reminder' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable this email', 'woo-payment-reminder' ),
                'default' => 'yes',
            ),
            'days_to_send_first' => array(
                'title'       => __( 'Days before first reminder', 'woo-payment-reminder' ),
                'type'        => 'number',
                'default'     => '7',
                'desc_tip'    => true,
            ),
            'days_to_send_second' => array(
                'title'       => __( 'Days before second reminder', 'woo-payment-reminder' ),
                'type'        => 'number',
                'default'     => '10',
                'desc_tip'    => true,
            ),
        );
    }

    public function trigger( $order_id ) {
        if ( ! $order_id ) return;

        $order = wc_get_order( $order_id );
        $this->recipient = $order->get_billing_email();

        $this->setup_locale();
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }
}
