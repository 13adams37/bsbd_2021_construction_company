<?php

  
// Сохраняем строку в переменную, которая
// нужно зашифровать
function encrypt ($text)
{

	// Показать оригинальную строку
	// Сохраняем метод шифрования
	$ciphering = "AES-128-CTR";
	// Используем метод шифрования OpenSSl
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	// Ненулевой вектор инициализации для шифрования
	$encryption_iv = '1234567891011121';
	// Сохраняем ключ шифрования
	$encryption_key = "qwfskfde3sa1sw";
	// Используем функцию openssl_encrypt () для шифрования данных
	$encryption = openssl_encrypt($text, $ciphering,

				$encryption_key, $options, $encryption_iv);
	// Показать зашифрованную строку
	
	return $encryption;

} 
function decrypt ($text)
{
	$options = 0;
	$ciphering = "AES-128-CTR";
	// Ненулевой вектор инициализации для дешифрования
	$decryption_iv = '1234567891011121';
	// Сохраняем ключ дешифрования
	$decryption_key = "qwfskfde3sa1sw";
	// Используем функцию openssl_decrypt () для расшифровки данных
	$decryption=openssl_decrypt ($text, $ciphering, 
			$decryption_key, $options, $decryption_iv);

	// Показать расшифрованную строку
	return $decryption;

}
?>