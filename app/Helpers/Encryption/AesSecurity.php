<?php
/**
 * Created by PhpStorm.
 * User: FelipeD
 * Date: 1/30/18
 * Time: 02:48
 */

namespace App\Helpers\Encryption;


/**
 * Class AesSecurity
 *
 * @package App\Helpers\Encryption
 */
class AesSecurity
{

	/**
	 * @param $password
	 * @param $plainText
	 *
	 * @return bool|string
	 */
	public static function encrypt($password, $plainText)
	{
		if(empty($password) || empty($plainText))
		{
			return false;
		}

		//generate a random salt
		$Salt = openssl_random_pseudo_bytes(8);
		if($Salt === false){
			return false;
		}

		//generate a random initialization vector
		$IV = openssl_random_pseudo_bytes(
			openssl_cipher_iv_length('AES-256-CBC'));
		if($IV === false){
			return false;
		}

		//generate aes key
		$pwd = substr(hash('sha256', $password), 0, 32);
		$Key = openssl_pbkdf2($pwd, $Salt, 32, 5);
		if($Key === false){
			return false;
		}

		//encrypt message
		$cipherText = openssl_encrypt($plainText, 'AES-256-CBC', $Key, true, $IV);

		//check if encryption failed
		if($cipherText === false){
			return false;
		}

		//create something safer than the following code
		//this is just a demonstration
		$IV64 = base64_encode($IV);
		$Salt64 = base64_encode($Salt);
		$Cipher64 = base64_encode($cipherText);

		if($IV64 === false || $Salt64 === false || $Cipher64 === false)
		{
			return false;
		}

		return base64_encode($IV64.'^^'.$Cipher64.'**'.$Salt64);
	}

	/**
	 * @param $password
	 * @param $cipherText
	 *
	 * @return bool|string
	 */
	public static function decrypt($password, $cipherText)
	{
		if(empty($password) || empty($cipherText))
		{
			return false;
		}

		$decoded = base64_decode($cipherText);
		if($decoded === false){
			return false;
		}

		//locate iv value
		$IV = base64_decode(substr($decoded, 0,
			strpos($decoded, '^^')));
		if($IV === false){
			return false;
		}

		//locate salt value
		$encodedSalt = substr($decoded,
			strpos($decoded, '**') + 2, strlen($decoded));
		$Salt = base64_decode($encodedSalt);
		if($Salt === false){
			return false;
		}

		//locate cipher text
		$ciphertext = base64_decode(substr($decoded,
			strpos($decoded, '^^') + 2,
			-(strlen($encodedSalt)+2)));
		if($ciphertext === false){
			return false;
		}

		//generate aes key
		$pwd = substr(hash('sha256', $password), 0, 32);
		$Key = openssl_pbkdf2($pwd, $Salt, 32, 5);
		if($Key === false){
			return false;
		}

		return openssl_decrypt($ciphertext, 'AES-256-CBC', $Key, true, $IV);
	}


}