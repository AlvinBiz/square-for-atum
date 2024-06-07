<?php

class API {

  protected $accToken;
  protected $sku;

  function __construct($accToken = NULL, $sku = NULL) {
    $this->accToken = $accToken;
    $this->sku = $sku;
  }

}
