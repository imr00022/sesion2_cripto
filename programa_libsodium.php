<?php

if(isset($_POST["submit"])) {
$password = $_POST['pass_encri'];//variable con la contraseña que intruduce el usuario
$target=basename($_FILES['subir_archivo']['name']);//nombre del archivo que introduce el usuario
move_uploaded_file($_FILES['subir_archivo']['tmp_name'],'/var/www/html/'.$target);//se copia el archivo al directorio del apache 
$inputFile= $target;//archivo sin encriptar
$encryptedFile = $target.'.enc';//renombrar el archivo que va a ser encriptado
$chunkSize = 4096;//tamaño en los que se divide el archivo a encriptar
$alg = SODIUM_CRYPTO_PWHASH_ALG_DEFAULT; //variables de la librería para la secretKey
$opsLimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE; //variables de la librería para la secretKey
$memLimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE; //variables de la librería para la secretKey
$salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES); //variables de la librería para la secretKey
//Realiza una operación intensiva de cálculo de una contraseña para obtener una clave secreta
$secretKey = sodium_crypto_pwhash(
	SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
	$password,
	$salt,
	$opsLimit,
	$memLimit,
	$alg
);
$fdIn = fopen($inputFile, 'rb') //Abrir el archivo
$fdOut = fopen($encryptedFile, 'wb') //Abrir el archivo
fwrite($fdOut, pack('C', $alg)); // Empaqueta los argumentos dados a una cadena binaria 
fwrite($fdOut, pack('P', $opsLimit));// Empaqueta los argumentos dados a una cadena binaria 
fwrite($fdOut, pack('P', $memLimit));// Empaqueta los argumentos dados a una cadena binaria 
fwrite($fdOut, $salt);// Empaqueta los argumentos dados a una cadena binaria 
[$stream, $header] = sodium_crypto_secretstream_xchacha20poly1305_init_push($secretKey);//encripta a partir de la key obtenida en sodium_crypto_pwhash
fwrite($fdOut, $header);
$tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
//Divide en trozos la información y encripta la información
do {
	$chunk = fread($fdIn, $chunkSize);
	if (feof($fdIn)) {
		$tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
	}
	$encryptedChunk = sodium_crypto_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
	fwrite($fdOut, $encryptedChunk);
} while ($tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);
fclose($fdOut);//cierra archivos 
fclose($fdIn);//cierra archivos 
}
?>
