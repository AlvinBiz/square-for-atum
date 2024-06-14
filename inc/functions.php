<?php

function send_in_store_inventory_on_save($product_obj) {
  $product_id;
  $product_sku;
  $api_key = get_option('api_key');

  $product_id = $product_obj->get_id();
  $product_sku = $product_obj->get_sku();

  if( $product_obj->get_children() ) {

    $variations = $product_obj->get_children();
    foreach($variations as $variation_id) {

      $variation_obj = wc_get_product($variation_id);
      $variation_sku = $variation_obj->get_sku();

      $sync = new SyncInventory($variation_obj, $api_key, $variation_sku, NULL);
      $sync->send_stock();

    }
  } else {
    $sync = new SyncInventory($product_obj, $api_key, $product_sku, NULL);
    $sync->send_stock();

  }
}

function sync_in_store_inventory() {

    $product_id;
    $product_sku;
    $api_key = get_option('api_key');

    $product_obj = wc_get_product();

    $product_id = $product_obj->get_id();
    $product_sku = $product_obj->get_sku();

    if( $product_obj->get_children() ) {
      $variations = $product_obj->get_children();
      foreach($variations as $variation_id) {

        $variation_obj = wc_get_product($variation_id);
        $variation_sku = $variation_obj->get_sku();

        $sync = new SyncInventory($variation_obj, $api_key, $variation_sku, NULL);
        $squareStock = $sync->get_square_stock();

        if (!is_null($squareStock)) {
          update_post_meta( $variation_id, '_stock', $squareStock );

          if($variation_obj->get_stock_quantity() != $squareStock) {
            wp_redirect($_SERVER['HTTP_REFERER']);
          }
        }

      }
    } else {
      $sync = new SyncInventory($product_obj, $api_key, $product_sku, NULL);
      $squareStock = $sync->get_square_stock();

      // $file_path = WP_PLUGIN_DIR . '/square-for-atum/error_log.txt';
      // $myfile = fopen($file_path, "a") or die("Unable to open file!");
      // $txt = ' Square Quantity: ' . $squareStock;
      // fwrite($myfile, $txt);
      // fclose($myfile);

      if (!is_null($squareStock)) {
        update_post_meta( $product_id, '_stock', $squareStock );

        if($product_obj->get_stock_quantity() != $squareStock) {
          wp_redirect($_SERVER['HTTP_REFERER']);
        }
      }

      // $file_path = WP_PLUGIN_DIR . '/square-for-atum/error_log.txt';
      // $myfile = fopen($file_path, "a") or die("Unable to open file!");
      // $txt = ' New stock: ' . $product_obj->get_stock_quantity();
      // fwrite($myfile, $txt);
      // fclose($myfile);

    }

}

function sync_in_store_inventory_on_add_to_cart($cart_id, $product, $request_quantity, $variation_id) {

    $product_id;
    $product_sku;
    $api_key = get_option('api_key');

    $product_id = $product;
    $product_obj = wc_get_product($product_id);
    $product_sku = $product_obj->get_sku();

    if ($variation_id) {
      $variation_obj = wc_get_product($variation_id);
      $variation_sku = $variation_obj->get_sku();

      $sync = new SyncInventory($variation_obj, $api_key, $variation_sku, NULL);
      $squareStock = $sync->get_square_stock();

      if (!is_null($squareStock)) {
        update_post_meta( $variation_id, '_stock', $squareStock );
      }

    } else {

      $sync = new SyncInventory($product_obj, $api_key, $product_sku, NULL);
      $squareStock = $sync->get_square_stock();

      if (!is_null($squareStock)) {

        update_post_meta( $product_id, '_stock', $squareStock );
      }

    }

}

function process_checkout_creating_order( $order, $data ) {
    $wooorder = json_decode($order);

    foreach ($wooorder->line_items as $item) {

      $ordered_product_id = $item->legacy_values->product_id;
      $variation_id = $item->legacy_values->variation_id;

      $ordered_quantity = $item->legacy_values->quantity;
      $api_key = get_option('api_key');

      if($variation_id) {

        $variation_obj = wc_get_product($variation_id);
        $sku = $variation_obj->get_sku();

        $sync = new SyncInventory($variation_obj, $api_key, $sku, $ordered_quantity);
        $squareStock = $sync->get_square_stock();

        if ($squareStock < $ordered_quantity) {

          if (!is_null($squareStock)) {
            $_product->set_stock($squareStock);
          }

          throw new Exception( __("It looks like there are not enough items in stock. Please go back to cart and adjust the quantity.") );

          }

        } else {

        $product_obj = wc_get_product($ordered_product_id);
        $sku = $product_obj->get_sku();

        $sync = new SyncInventory($product_obj, $api_key, $sku, $ordered_quantity);
        $squareStock = $sync->get_square_stock();

        if ($squareStock < $ordered_quantity) {

          if (!is_null($squareStock)) {
            update_post_meta( $ordered_product_id, '_stock', $squareStock );
          }

          throw new Exception( __("It looks like there are not enough items in stock. Please go back to cart and adjust the quantity.") );

        }
      }

  }
}

function decrease_stock($order_id) {

    $wooorder = wc_get_order($order_id);

    foreach ($wooorder->get_items() as $items_id => $item) {
      $item_data = $item->get_data();

      $ordered_product_id = $item_data['product_id'];

      $ordered_quantity = $item_data['quantity'];
      $sku = $item_data['sku'];
      $api_key = get_option('api_key');

      if($item_data['variation_id']) {
        $sync = new SyncInventory(wc_get_product($item_data['variation_id']), $api_key, $sku, $ordered_quantity);
        $sync->decrease_stock();
        $squareStock = $sync->get_square_stock();

        $_product = new WC_Product($item_data['variation_id']);

        if (!is_null($squareStock)) {
          $_product->set_stock($squareStock);
        }

      } else {
        $sync = new SyncInventory(wc_get_product($ordered_product_id), $api_key, $sku, $ordered_quantity);
        $sync->decrease_stock();
        $squareStock = $sync->get_square_stock();

        if (!is_null($squareStock)) {
          update_post_meta( $ordered_product_id, '_stock', $squareStock );
        }
      }

    }
}


function load_admin_scripts() {
    wp_enqueue_script( 'square_atum_ajax', plugins_url()  . '/square-for-atum/admin/js/ajax.js', 'jQuery', filemtime(plugins_url()  . '/square-for-atum/admin/js/ajax.js') );
    wp_enqueue_style( 'square_atum_css', plugins_url()  . '/square-for-atum/admin/css/admin-styles.css', false, filemtime(plugins_url()  . '/square-for-atum/admin/css/admin-styles.css') );
}


add_action( 'woocommerce_admin_process_product_object', 'send_in_store_inventory_on_save', 10 , 1 );

add_action( 'woocommerce_product_options_general_product_data', 'sync_in_store_inventory', 0 );

add_action( 'woocommerce_add_to_cart', 'sync_in_store_inventory_on_add_to_cart', 11, 6 );

add_action( 'woocommerce_checkout_create_order', 'process_checkout_creating_order',  10, 2  );

add_action( 'woocommerce_payment_complete', 'decrease_stock', 12, 1 );

add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );
