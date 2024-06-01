<?php

function sample_admin_notice__error() {

$class = 'notice notice-error';
$message = __('Please install WooCommerce (8.8.0 or later), ATUM Inventory Management for WooCommerce (1.9.0 or later) and ATUM Multi-Inventory (1.8.0 or later) before activating the Square for ATUM plugin, otherwise Square will not sync with ATUM.', 'sample-text-domain');

printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

if ( ! is_plugin_active('woocommerce/woocommerce.php') ) {

	add_action( 'admin_notices', 'sample_admin_notice__error' );

}

if ( ! is_plugin_active('atum-stock-manager-for-woocommerce/atum-stock-manager-for-woocommerce.php') ) {
	
	add_action( 'admin_notices', 'sample_admin_notice__error' );

}

// if ( ! is_plugin_active('atum-multi-inventory/atum-multi-inventory.php') ) {
	
// 	add_action( 'admin_notices', 'sample_admin_notice__error' );

// }

if ( ! is_plugin_active('atum-multi-inventory-trial/atum-multi-inventory-trial.php') ) {
	
	add_action( 'admin_notices', 'sample_admin_notice__error' );

}