<?php

class Inventory extends API {

  public function updateInventory($catalogObjID = NULL, $from_state = NULL, $to_state = NULL, $locID = NULL, $count = NULL) {


    $curl = new Curl(
      'https://connect.squareup.com/v2/inventory/changes/batch-create',
      "{\"idempotency_key\": \"" . rand(111111111111,999999999999) . "\",\"changes\": [{\"type\": \"ADJUSTMENT\",\"adjustment\": {\"catalog_object_id\": \"" . $catalogObjID . "\",\"from_state\": \"" . $from_state . "\",\"to_state\": \"" . $to_state . "\",\"location_id\": \"" . $locID . "\",\"quantity\": \"".$count."\",\"occurred_at\": \"" . date("c", strtotime(current_datetime()->format('Y-m-d H:i:s'))) . "\"}}],\"ignore_unchanged_counts\": false}",
      $this->accToken
      );
    return $curl->curlCommand();
  }

}
