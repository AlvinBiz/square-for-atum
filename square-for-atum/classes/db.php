<?php

class DB {

  protected $wpdb;
  protected $query;

  public function __construct()
  {
      global $wpdb;
      $this->wpdb = $wpdb;
  }


  public function getATUMUnsyncedStock($product_id) {

      $this->query = $this->wpdb->prepare("SELECT id FROM {$this->wpdb->prefix}atum_inventories WHERE product_id = '{$product_id}' AND is_main = '0'");


      $inventories = $this->wpdb->get_results($this->query);

      $inventory_ids = array();
      $stock;

      foreach($inventories as $inventory) {
        array_push($inventory_ids, $inventory->id);
      }

      $id_list = implode(",", $inventory_ids);

      $this->query = $this->wpdb->prepare("SELECT stock_quantity FROM {$this->wpdb->prefix}atum_inventory_meta WHERE inventory_id IN (" . $id_list . ")" );

      $result = $this->wpdb->get_results($this->query);

      foreach($result as $quantity) {
        $stock = $stock + $quantity->stock_quantity;
      }

      return (int)$stock;

  }


}
