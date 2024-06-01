<?php

// class SquareAtumActivate {
//
//   private $woo_ext;
//   private $woo_version_to_check;
//
//   private $atum_ext;
//   private $atum_version_to_check;
//
//   private $atum_mi_ext;
//   private $atum_mi_version_to_check;
//
//   private $atum_mi_trial_ext;
//   private $atum_mi_trial_version_to_check;
//
//   const ACTIVATION_ERROR = "s4a-activation-error";
//
//    public function __construct(){
//        $this->woo_ext = 'woocommerce/woocommerce.php';
//        $this->woo_version_to_check = '8.8.0';
//
//        $this->atum_ext = 'atum-stock-manager-for-woocommerce/index.php';
//        $this->atum_version_to_check = '1.9.0';
//
//        $this->atum_mi_ext = 'atum-multi-inventory/index.php';
//        $this->atum_mi_version_to_check = '1.8.0';
//
//        $this->atum_mi_trial_ext = 'atum-multi-inventory-trial/index.php';
//        $this->atum_mi_trial_version_to_check = '1.8.0';
//
//        register_activation_hook( S4A_PLUGIN_URL, array( $this, 'install' ) );
//        if( is_admin() ){
//            add_action( "admin_init", array( $this, "init" ), 2 );            //priority 2 to load after the plugin
//        } else {
//            add_action( "init", array( $this, "init" ), 2 );
//        }
//    }
//
//    public function init(){
//
//        $has_to_be_deactivate = get_transient( self::ACTIVATION_ERROR );
//        if( is_admin() ) {
//            if( $has_to_be_deactivate ) {
//                add_action( 'admin_notices', array( $this, "display_warning_no_activation" ) );
//                deactivate_plugins( plugin_basename( S4A_PLUGIN_URL ) );
//            }
//        }
//    }
//
//    public function install() {
//
//        if(file_exists(WP_PLUGIN_DIR.'/'.$this->woo_ext)) {
//            $woo_ext_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->woo_ext);
//            set_transient( self::ACTIVATION_ERROR , !version_compare ( $woo_ext_data['Version'], $this->woo_version_to_check, '>=' ) );
//        }
//
//        if(file_exists(WP_PLUGIN_DIR.'/'.$this->atum_ext)) {
//            $atum_ext_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->atum_ext);
//            set_transient( self::ACTIVATION_ERROR , !version_compare ( $atum_ext_data['Version'], $this->atum_version_to_check, '>=' ) );
//        }
//
//        if(file_exists(WP_PLUGIN_DIR.'/'.$this->atum_mi_ext)) {
//            $atum_mi_ext_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->atum_mi_ext);
//            set_transient( self::ACTIVATION_ERROR , !version_compare ( $atum_mi_ext_data['Version'], $this->atum_mi_version_to_check, '>=' ) );
//        }
//
//        if(file_exists(WP_PLUGIN_DIR.'/'.$this->atum_mi_trial_ext)) {
//            $atum_mi_trial_ext_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->atum_mi_trial_ext);
//            set_transient( self::ACTIVATION_ERROR , !version_compare ( $atum_mi_trial_ext_data['Version'], $this->atum_mi_trial_version_to_check, '>=' ) );
//        }
//      }
//
//    public function display_warning_no_activation() {
//        if( get_transient( self::ACTIVATION_ERROR ) ){
//           $message = __('Please install WooCommerce (8.8.0 or later), ATUM Inventory Management for WooCommerce (1.9.0 or later) and ATUM Multi-Inventory (1.8.0 or later) before activating the Square for ATUM plugin', 'text-domain');
//           $class = 'notice notice-error';
//           printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
//           }
//           delete_transient( self::ACTIVATION_ERROR );
//
//     }
//
//   }
//
// new SquareAtumActivate();
