<?php

class Curl {

  private $curlOptURL;
  private $curlOptPostFields;
  private $authBearer;


  public function __construct(string $curlOptURL, string $curlOptPostFields, string $authBearer) {
    $this->curlOptURL = $curlOptURL;
    $this->curlOptPostFields = $curlOptPostFields;
    $this->authBearer = $authBearer;
  }

  public function curlCommand() {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $this->curlOptURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->curlOptPostFields);

    $headers = array();
    $headers[] = 'Square-Version: 2024-04-15';
    $headers[] = 'Authorization: Bearer '.$this->authBearer;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return $result;

  }

}
