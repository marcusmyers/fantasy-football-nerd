<?php

namespace MarkMyers\FFNerd;

use GuzzleHttp\Client;

class ApiRequest {
    const URL = 'https://www.fantasyfootballnerd.com/service';

    var $client;

    public function __construct() {
      $this->client = new Client();
    }

    public function uri() {
      return getenv('API_URL') ? getenv('API_URL') : self::URL;
    }

    public function serviceUrl($service, $api_key, $extra = []) {
      return implode("/", array_merge([self::uri(), $service, "json", $api_key], $extra));
    }

    public function requestService($service, $api_key, $extra = []) {
      return json_decode($this->client->request('GET', $this->serviceUrl($service, $api_key, $extra))->getBody(), true);
    }
}
