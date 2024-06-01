<?php

function decrease_stock($order_id) {

  $wooorder = wc_get_order($order_id);

  foreach ($wooorder->get_items() as $items_id => $item) {
    $item_data = $item->get_data();

    $ordered_product_id = $item_data['product_id'];

    $ordered_quantity = $item_data['quantity'];
    $api_key = get_option('api_key');

    if($item_data['variation_id']) {
      $sync = new SyncInventory($item_data['variation_id'], $api_key, NULL, $ordered_quantity);
      $sync->decrease_stock();
      $squareStock = $sync->get_square_stock();

      $_product = new WC_Product($product_id);

      if (!is_null($squareStock)) {
        $_product->set_stock($squareStock);
      }
      
    } else {
      $sync = new SyncInventory($ordered_product_id, $api_key, NULL, $ordered_quantity);
      $sync->decrease_stock();
      $squareStock = $sync->get_square_stock();

      if (!is_null($squareStock)) {
        update_post_meta( $ordered_product_id, '_stock', $squareStock );
      }   
    }

  }

}

function sync_in_store_inventory_on_save($product_obj, $data) {

      $product_id;
      $product_sku;
      $api_key = get_option('api_key');
      $unsynced_inventory_name = get_option('unsynced_inventory_name');
      $inventory_name = get_option('synced_inventory_name');

      $product_id = $product_obj->get_id();
      $product_sku = $product_obj->get_sku();

      if( $product_obj->get_children() ) {
        $variations = $product_obj->get_children();
        foreach($variations as $variation_id) {

          $variation_obj = wc_get_product($variation_id);
          $variation_sku = $variation_obj->get_sku();

          $sync = new SyncInventory($variation_id, $api_key, $variation_sku, NULL);
          $squareStock = $sync->get_square_stock();

          $file_path = WP_PLUGIN_DIR . '/square-for-atum/error_log.txt';
          $myfile = fopen($file_path, "a") or die("Unable to open file!");
          $txt = ' Stock: ' . $squareStock;
          fwrite($myfile, $txt);
          fclose($myfile);

          if (!is_null($squareStock)) {
            update_post_meta( $variation_id, '_stock', $squareStock );
          }

        }
      } else {
        $sync = new SyncInventory($product_id, $api_key, $product_sku, NULL);
        $squareStock = $sync->get_square_stock();

        if (!is_null($squareStock)) {
          update_post_meta( $product_id, '_stock', $squareStock );
        }

      }

}

function sync_in_store_inventory_on_add_to_cart($cart_id, $product, $request_quantity, $variation_id) {

      $product_id;
      $product_sku;
      $api_key = get_option('api_key');
      $inventory_name = get_option('inventory_name');

      $product_id = $product;
      $_product = wc_get_product($product_id);
      $product_sku = $_product->get_sku();

      if ($variation_id) {
        $variation_obj = wc_get_product($variation_id);
        $variation_sku = $variation_obj->get_sku();

        $sync = new SyncInventory($variation_id, $api_key, $variation_sku, NULL);
        $squareStock = $sync->get_square_stock();

        if (!is_null($squareStock)) {
          update_post_meta( $variation_id, '_stock', $squareStock );
        }

      } else {

        $sync = new SyncInventory($product_id, $api_key, $product_sku, NULL);
        $squareStock = $sync->get_square_stock();

        if (!is_null($squareStock)) {

          update_post_meta( $product_id, '_stock', $squareStock );
        }

      }

}


function load_admin_style() {
    wp_enqueue_style( 'square_atum_css', plugins_url()  . '/square-for-atum/admin/css/admin-styles.css', false, filemtime(plugins_url()  . '/square-for-atum/admin/css/admin-styles.css') );
}


add_action( 'woocommerce_payment_complete', 'decrease_stock', 12, 1 );

add_action( 'woocommerce_after_product_object_save', 'sync_in_store_inventory_on_save', 10 , 4 );
add_action( 'woocommerce_add_to_cart', 'sync_in_store_inventory_on_add_to_cart', 11, 6 );

add_action( 'admin_enqueue_scripts', 'load_admin_style' );
