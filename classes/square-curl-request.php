<?php

class CurlRequest extends API {

  public function getItem() {

    $curl = new Curl(
      'https://connect.squareup.com/v2/catalog/search-catalog-items',
      "{\n    \"text_filter\": \"" . $this->sku . "\",\n    \"limit\": 1\n  }",
      $this->accToken
      );

    return $curl->curlCommand();

  }

  public function getInventory($catalogObjIDs, $location) {

    $curl = new Curl(
      'https://connect.squareup.com/v2/inventory/counts/batch-retrieve',
      "{\n    \"catalog_object_ids\": [\n      \"" . $catalogObjIDs . "\"\n    ],\n    \"location_ids\": [\n      \"" . $location . "\"\n    ]\n }",
      $this->accToken
      );

    return $curl->curlCommand();

  }

  public function getLocation($locationID) {

    $curl = new Curl(
      'https://connect.squareup.com/v2/locations/' . $locationID,
      '',
      $this->accToken
    );

    return $curl->curlCommandNoOpts();

  }
}
