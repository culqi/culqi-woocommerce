<?php

use Firebase\JWT\Key;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;
use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;

function encrypt_data_with_rsa($data, $publicKey) {
    $rsa = PublicKeyLoader::load($publicKey)
        ->withPadding(RSA::ENCRYPTION_OAEP)
        ->withHash('sha256')
        ->withMGFHash('sha256');

    return base64_encode($rsa->encrypt($data));
}

function generate_token()
{
    $minutes = EXPIRATION_TIME;
    $expirationTimeInSeconds = $minutes * 60;
    $exp = time() + $expirationTimeInSeconds;

    $config = culqi_get_config();
    if(!$config->rsa_pk) {
        wc_add_notice(__('Debes configurar tu llave pública.', 'culqi-payment'), 'error');
        return;
    }
    $data = [
        "pk" => $config->public_key,
        "exp" => $exp
    ];
    $encryptedData = encrypt_data_with_rsa(json_encode($data), $config->rsa_pk);
    return $encryptedData;
}

function verify_jwt_token($token)
{
    $kid = getKidFromJwt($token);
    $config = culqi_get_config();
    $publicKey = $config->rsa_pk;
    $headers = new stdClass();
    $headers->alg = 'RS256';
    $key_data = [
        $kid => new Key($publicKey, 'RS256')
    ];

    try {
        $decoded = JWT::decode($token, $key_data, $headers);
        return (isset($decoded->exp) && time() < $decoded->exp);
    } catch (Exception $e) {
        echo "Error decoding token: " . $e->getMessage();
    }
}

function getKidFromJwt($jwt) {
    // Split the JWT into its three parts
    $tks = explode('.', $jwt);
    
    // Ensure the JWT has the correct number of segments
    if (count($tks) !== 3) {
        throw new UnexpectedValueException('Wrong number of segments');
    }
    
    // Get the header part and decode it
    $headerB64 = $tks[0];
    $headerJson = base64UrlDecode($headerB64);
    
    // Decode the JSON string to a PHP array
    $header = json_decode($headerJson);
    
    // Check if the header contains the 'kid' field
    if (isset($header->kid)) {
        return $header->kid;
    }

    return null; // Return null if 'kid' is not set
}

// Function to Base64 URL decode
function base64UrlDecode($data) {
    // Replace URL-safe characters and add padding
    $data .= str_repeat('=', (4 - strlen($data) % 4) % 4);
    return base64_decode(strtr($data, '-_', '+/'));
}
