<?php

class DB {

  protected $wpdb;
  protected $query;

  public function __construct()
  {
      global $wpdb;
      $this->wpdb = $wpdb;
  }


  public function getATUMUnsyncedStock($product_id, $inventory_name) {

      $this->query = $this->wpdb->prepare("SELECT id FROM {$this->wpdb->prefix}atum_inventories WHERE product_id = '{$product_id}' AND name = '{$inventory_name}'");


      $inventory = $this->wpdb->get_results($this->query);

      $inventory_id = $inventory[0]->id;
      $this->query = $this->wpdb->prepare("SELECT stock_quantity FROM {$this->wpdb->prefix}atum_inventory_meta WHERE inventory_id = '{$inventory_id }'");
      $result = $this->wpdb->get_results($this->query);

      $stock = $result[0]->stock_quantity;

      return (int)$stock;

  }


}
