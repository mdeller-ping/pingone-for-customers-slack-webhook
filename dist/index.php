<?php

  // set content type to json as we pretend to be a REST API

  header('Content-Type: application/json');
  include ("httpful.phar");

  // receive payload from caller

  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  $input = file_get_contents('php://input');
  $response = json_decode($input);
  $base64 = base64_encode( $input );

  // my chance to see the payload

  $payload = $response[0]->result->description;

  // call slack hook

  $uri = 'https://hooks.slack.com/services/...';

  $data = json_encode(array('text' => $payload));

  // // // echo $data;

  // // // echo json_decode($data);

  $response = \Httpful\Request::post($uri)
    ->sendsJson()
    ->body($data)
    ->send();

  // echo json_decode($response);

?>
