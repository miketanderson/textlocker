<?php

function load_json($file_name) {
  $data = file_get_contents('../data/' . $file_name);
  echo $data;
  

  $data = '{"a":1,"b":2,"c":3}';
  $json = json_decode($data, true, 512, JSON_BIGINT_AS_STRING);
var_dump($json);
  echo '<pre>' . print_r($json, true) . '</pre>';
foreach ($json['daily']['data'] as $key => $value) {
 echo '<p>' . $key . ' : ' . $value . '</p>';
}
  #echo print_r($json, true);
  return $json;
}

function parse_json($json_data) {
  echo '<pre>' . print_r($json, true) . '</pre>';
}

?>
