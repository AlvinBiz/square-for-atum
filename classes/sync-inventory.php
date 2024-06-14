<?php

class SyncInventory {

  protected $product_obj;
  protected $api_key;
  protected $product_sku;
  protected $ordered_quantity;

  public function __construct($product_obj, string $api_key, $product_sku, $ordered_quantity) {
    $this->product_obj = $product_obj;
    $this->api_key = $api_key;
    $this->product_sku = $product_sku;
    $this->ordered_quantity = $ordered_quantity;
  }

  public function decrease_stock() {

    $_product = $this->product_obj;
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
    $atum_unsynced_stock = $query->getATUMUnsyncedStock($this->product_obj->get_id());

    $squareInventoryObj = json_decode($request->getInventory($square_id, $location));

    $squareCurrentStock;


    foreach($squareInventoryObj->counts as $count) {
      if($count->state == 'IN_STOCK') {
        $squareCurrentStock = $count->quantity;
      }
    }

    $locationData = json_decode($request->getLocation($location));

    $timezone = $locationData->location->timezone;
    $time = current_datetime()->setTimezone(new DateTimeZone($timezone))->modify('+4 hour')->format('Y-m-d H:i:s');
    $timeString = date("c", strtotime($time));

    if($atum_unsynced_stock == 0) {

      $prev_atum_stock = $current_quantity - $squareCurrentStock;

      $stock_to_remove = $this->ordered_quantity - $prev_atum_stock;


      $update->updateInventory($square_id, $from_state, $to_state, $location, $stock_to_remove, $timeString);

    } else {

      return;

    }
  }

  public function send_stock() {
    $_product = $this->product_obj;
    $sku = $_product->get_sku();
    $current_quantity = $_product->get_stock_quantity();

    $request = new CurlRequest($this->api_key, $sku);
    $productItem = json_decode($request->getItem());

    if(is_object($productItem) && !is_null($productItem->items)) {
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

      $locationData = json_decode($request->getLocation($location));
      $timezone = $locationData->location->timezone;
      $time = current_datetime()->setTimezone(new DateTimeZone($timezone))->modify('+4 hour')->format('Y-m-d H:i:s');
      $timeString = date("c", strtotime($time));

      $update->sendInventory($square_id, $from_state, NULL, $location, $current_quantity, $timeString);

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
