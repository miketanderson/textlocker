<?php

function pkcs7_pad($data, $blocksize){
  $pad = $blocksize - (strlen($data) % $blocksize);
  $char = pack('C', $pad);
  $padded_data = $data . str_repeat($char, $pad); 
  return $padded_data; 
}

function pkcs7_unpad($data) {
  $pad_length_raw = substr(rtrim($data, "\0"), -1,1);
  $pad_length = unpack('C',$pad_length_raw)[1];
  return substr($data, 0, strlen($data) - $pad_length);
}

function aes_encrypt($key, $data) {
  $cipher_name = MCRYPT_RIJNDAEL_128;
  $cipher = mcrypt_module_open($cipher_name, '', MCRYPT_MODE_CBC, '');

  $blocksize = mcrypt_get_block_size($cipher_name,'cbc'); 
  $padded_data = pkcs7_pad($data, $blocksize);

  $iv_size = mcrypt_enc_get_iv_size($cipher);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 

  $init = mcrypt_generic_init($cipher,$key,$iv);
  if ($init  < 0 ) {
     return false;
  }

  $crypted = mcrypt_generic($cipher, $padded_data);

  mcrypt_generic_deinit($cipher);
  mcrypt_module_close($cipher);

  $result = $iv . $crypted;
  return $result;
}

function aes_decrypt($key, $data) {
  $cipher_name = MCRYPT_RIJNDAEL_128;
  $cipher = mcrypt_module_open($cipher_name, '', MCRYPT_MODE_CBC, '');

  $iv_size = mcrypt_enc_get_iv_size($cipher);
  $iv = substr($data,0,$iv_size);

  $init = mcrypt_generic_init($cipher, $key, $iv);
  if ($init < 0) {
    return false;
  }

  $crypted = substr($data, $iv_size, strlen($data) - $iv_size);
  $plaintext = mdecrypt_generic($cipher, $crypted);
  
  mcrypt_generic_deinit($cipher);
  mcrypt_module_close($cipher);
 
  $unpad = pkcs7_unpad($plaintext);
  return $unpad;

}

function hash_password($pass, $salt,$iterations=100000) {
  return hash_pbkdf2('sha256',$pass,$salt,$iterations);
}

function tests() {
  $test = "test string 123456789012";
  
  $cipher_name = MCRYPT_RIJNDAEL_128;
  $blocksize = mcrypt_get_block_size($cipher_name,'cbc'); 
  $padded = pkcs7_pad($test, $blocksize);
  $unpad = pkcs7_unpad($padded);
  #print "Original: " . $test . ".\r\n";
  #print "Padded: " . $padded . ".\r\n";
  #print "Unpadded: " . $unpad . ".\r\n";
  if ($test === $unpad) {
    print "Padding Test Success!\r\n";
  }
  
  $testpass = "mypassword";
  $salt = '6VtI5s7HLDC6P0KVcARZ8Q'; #generated w/ openssl rand -base64 16
  $hash = hash_password($testpass, $salt);
  $key = hex2bin($hash);
  print "Password Hash: " . $hash . " Length: " . strlen($hash) . "\r\n";
  print "Length of binary hash: " . strlen($key) . "\r\n";
  
  $encrypted= aes_encrypt($key, $test);
  $decrypted = aes_decrypt($key, $encrypted);
  
  if ($test === $decrypted) {
    print "Decryption Success!\r\n";
  }
}

#tests();

?>
