<?php

  // we're an API, so content type should be application/json

  header('Content-Type: application/json');

  // library that makes outbound REST calls easier

  include ("httpful.phar");

  // your slack webhook url

  $uri = 'https://hooks.slack.com/services/xxx';

  // receive payload from the PingOne for Customers caller

  $method = $_SERVER['REQUEST_METHOD'];
  $input = file_get_contents('php://input');
  $response = json_decode($input);
  $base64 = base64_encode( $input ); // for possible attaching whole payload to slack webhook.  helpful for debug

  // how big is the payload?

  $sizeOf = count($input);

  // sometimes the inbound webhook has multiple record

  for ($x = 0; $x < $sizeOf; $x++) {

    // parse out some human readable stuff

    $description = $response[$x]->result->description;
    $action = $response[$x]->action->type;

    // in case a value is null

    $description = isset($description) ? $description : 'null';
    $action = isset($action) ? $action : 'null';

    // form a sentence

    $payload = 'Action: ' . $action . '; Desc: ' . $description;

    // encode the payload for slack

    $data = json_encode(array('text' => $payload));

    // $data = json_encode(array('text' => $base64)); // helpful for debug
    
    $response = \Httpful\Request::post($uri)
      ->sendsJson()
      ->body($data)
      ->send();

  }

?>