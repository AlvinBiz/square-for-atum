<?php

class Inventory extends API {

  public $dateTime;

  public function updateInventory($catalogObjID = NULL, $from_state = NULL, $to_state = NULL, $locID = NULL, $count = NULL, $time = NULL) {

    $curl = new Curl(
      'https://connect.squareup.com/v2/inventory/changes/batch-create',
      "{\"idempotency_key\": \"" . rand(111111111111,999999999999) . "\",\"changes\": [{\"type\": \"ADJUSTMENT\",\"adjustment\": {\"catalog_object_id\": \"" . $catalogObjID . "\",\"from_state\": \"" . $from_state . "\",\"to_state\": \"" . $to_state . "\",\"location_id\": \"" . $locID . "\",\"quantity\": \"".$count."\",\"occurred_at\": \"" . $time . "\"}}],\"ignore_unchanged_counts\": false}",
      $this->accToken
      );
    return $curl->curlCommand();
  }

  public function sendInventory($catalogObjID = NULL, $from_state = NULL, $to_state = NULL, $locID = NULL, $count = NULL, $time = NULL) {

    $curl = new Curl(
      'https://connect.squareup.com/v2/inventory/changes/batch-create',
      "{\"idempotency_key\": \"" . rand(111111111111,999999999999) . "\",\"changes\": [{\"type\": \"PHYSICAL_COUNT\",\"physical_count\": {\"catalog_object_id\": \"" . $catalogObjID . "\",\"state\": \"" . $from_state . "\",\"location_id\": \"" . $locID . "\",\"quantity\": \"".$count."\",\"occurred_at\": \"" . $time . "\"}}],\"ignore_unchanged_counts\": true}",
      $this->accToken
      );
    return $curl->curlCommand();
  }

}
