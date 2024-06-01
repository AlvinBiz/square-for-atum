<?php

class SyncInventory {

  protected $product_id;
  protected $api_key;
  protected $product_sku;
  protected $ordered_quantity;

  public function __construct($product_id, string $api_key, $product_sku, $ordered_quantity) {
    $this->product_id = $product_id;
    $this->api_key = $api_key;
    $this->product_sku = $product_sku;
    $this->ordered_quantity = $ordered_quantity;
  }

  public function decrease_stock() {


    $_product = wc_get_product($this->product_id);
    $sku = $_product->get_sku();
    $current_quantity = $_product->get_stock_quantity();


    $request = new CurlRequest($this->api_key, $sku);
    $productItem = json_decode($request->getItem());
    $items = $productItem->items[0]->item_data->variations;
    $item;

    foreach($items as $variation) {
      if ($variation->item_variation_data->sku == $sku) {
        $item = $variation;
      }
    }

    $square_id = $item->id;
    $from_state = 'IN_STOCK';
    $to_state = 'SOLD';
    $location = $item->item_variation_data->location_overrides[0]->location_id;

    $update = new Inventory($this->api_key, NULL);
    $query = new DB;
    $atum_unsynced_stock = $query->getATUMUnsyncedStock($this->product_id);

    $squareInventoryObj = json_decode($request->getInventory($square_id, $location));

    $squareCurrentStock;


    foreach($squareInventoryObj->counts as $count) {
      if($count->state == 'IN_STOCK') {
        $squareCurrentStock = $count->quantity;
      }
    }

    

    if($atum_unsynced_stock == 0) {

      $prev_atum_stock = $current_quantity - $squareCurrentStock;

      $stock_to_remove = $this->ordered_quantity - $prev_atum_stock;


      $update->updateInventory($square_id, $from_state, $to_state, $location, $stock_to_remove);

    } else {

      return;

    }

  }

  public function get_square_stock() {

    $request = new CurlRequest($this->api_key, $this->product_sku);

    $productItem = json_decode($request->getItem());
    $items = $productItem->items[0]->item_data->variations;
    $item;

    foreach($items as $variation) {
      if ($variation->item_variation_data->sku == $this->product_sku) {
        $item = $variation;
      }
    }

    $square_id = $item->id;

    $location = $item->item_variation_data->location_overrides[0]->location_id;

    $squareInventoryObj = json_decode($request->getInventory($square_id, $location));

    $squareCurrentStock;


    foreach($squareInventoryObj->counts as $count) {
      if($count->state == 'IN_STOCK') {

        $squareCurrentStock = $count->quantity;

      }
    }


    return $squareCurrentStock;


  }

}
