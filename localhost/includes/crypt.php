<?php

function encrypt ($text)
{
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$encryption_iv = '1234567891011121';
	$encryption_key = "qwfskfde3sa1sw";
	$encryption = openssl_encrypt($text, $ciphering, $encryption_key, $options, $encryption_iv);
	return $encryption;
} 

function decrypt ($text)
{
	$options = 0;
	$ciphering = "AES-128-CTR";
	$decryption_iv = '1234567891011121';
	$decryption_key = "qwfskfde3sa1sw";
	$decryption=openssl_decrypt($text, $ciphering, $decryption_key, $options, $decryption_iv);
	return $decryption;
}

?>