<?php

class API {

  protected $accToken;
  protected $sku;
  protected $dateTime;
  protected $idempotencyKey;

  function __construct($accToken = NULL, $sku = NULL) {
    $this->accToken = $accToken;
    $this->sku = $sku;
    $this->dateTime = date("c", strtotime(current_datetime()->format('Y-m-d H:i:s')));
    $this->idempotencyKey = rand(111111111111,999999999999);
  }

}
