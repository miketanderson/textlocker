<?php
require_once('aes.php');

function load_text($file_name) {
  $data = file_get_contents('../data/' . $file_name);
  return $data;
}

function write_text($data, $file_name) {
  $file = fopen($file_name, "w") or die("Unable to open file!");
  fwrite($file, $data);
  fclose($file); 
}

#eventually, load_text and decrypt
#now, just load text
function get_text($file_name, $key) {
  $edata = load_text($file_name);
  $cdata = aes_decrypt($key, $edata); 
  return $cdata; 
}

#eventually, encrypt and write_text
#now, just write_text
function set_text($data, $file_name, $key) {
  $edata = aes_encrypt($key, $data);
  write_text($edata, $file_name);

}

?>
