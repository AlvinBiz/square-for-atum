<?php

class Inventory extends API {

  public function updateInventory($catalogObjID = NULL, $from_state = NULL, $to_state = NULL, $locID = NULL, $count = NULL) {

	$file_path = WP_PLUGIN_DIR . '/square-for-atum/error_log.txt';
	$myfile = fopen($file_path, "a") or die("Unable to open file!");
	$txt = "{\"idempotency_key\": \"" . $this->idempotencyKey . "\",\"changes\": [{\"type\": \"ADJUSTMENT\",\"adjustment\": {\"catalog_object_id\": \"" . $catalogObjID . "\",\"from_state\": \"" . $from_state . "\",\"to_state\": \"" . $to_state . "\",\"location_id\": \"" . $locID . "\",\"quantity\": \"".$count."\",\"occurred_at\": \"" . $this->dateTime . "\"}}],\"ignore_unchanged_counts\": true}";
	fwrite($myfile, $txt);
	fclose($myfile);

    $curl = new Curl(
      'https://connect.squareup.com/v2/inventory/changes/batch-create',
      "{\"idempotency_key\": \"" . $this->idempotencyKey . "\",\"changes\": [{\"type\": \"ADJUSTMENT\",\"adjustment\": {\"catalog_object_id\": \"" . $catalogObjID . "\",\"from_state\": \"" . $from_state . "\",\"to_state\": \"" . $to_state . "\",\"location_id\": \"" . $locID . "\",\"quantity\": \"".$count."\",\"occurred_at\": \"" . $this->dateTime . "\"}}],\"ignore_unchanged_counts\": true}",
      $this->accToken
      );

    return $curl->curlCommand();
  }

}
