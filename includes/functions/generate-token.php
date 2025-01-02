<?php

use Firebase\JWT\Key;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;
use \Firebase\JWT\JWT;
use \Firebase\JWT\JWK;

/*function encrypt_data_with_rsa($data, $publicKey) {
    $rsa = PublicKeyLoader::load($publicKey)
        ->withPadding(RSA::ENCRYPTION_OAEP)
        ->withHash('sha256')
        ->withMGFHash('sha256');

    return base64_encode($rsa->encrypt($data));
}*/
function encrypt_data_with_rsa(string $jsonData, string $publicKeyString): ?string {
    try {
        $publicKey = openssl_pkey_get_public($publicKeyString);
        if ($publicKey === false) {
            throw new Exception("Invalid public key: " . openssl_error_string());
        }

        $encrypted = '';
        $result = openssl_public_encrypt($jsonData, $encrypted, $publicKey, OPENSSL_PKCS1_OAEP_PADDING);

        openssl_free_key($publicKey);

        if ($result === false) {
            throw new Exception("Encryption failed: " . openssl_error_string());
        }

        return base64_encode($encrypted);
    } catch (Exception $e) {
        error_log("RSA Encryption Error: " . $e->getMessage());
        return null;
    }
}


function generate_token()
{
    $minutes = EXPIRATION_TIME;
    $expirationTimeInSeconds = $minutes * 60;
    $exp = time() + $expirationTimeInSeconds;

    $config = culqi_get_config();
    if(!$config->rsa_pk) {
        wc_add_notice(__('Debes configurar tu llave pública.', 'culqi'), 'error');
        return;
    }
    $data = [
        "pk" => $config->public_key,
        "exp" => $exp
    ];

    $encryptedData = encrypt_data_with_rsa(wp_json_encode($data), $config->rsa_pk);
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
        return false;
    }
}

function getKidFromJwt($jwt) {
    $tks = explode('.', $jwt);
    if (count($tks) !== 3) {
        throw new UnexpectedValueException('Wrong number of segments');
    }

    $headerB64 = $tks[0];
    $headerJson = base64UrlDecode($headerB64);

    $header = json_decode($headerJson);
    
    if (isset($header->kid)) {
        return $header->kid;
    }

    return null;
}

function base64UrlDecode($data) {
    $data .= str_repeat('=', (4 - strlen($data) % 4) % 4);
    return base64_decode(strtr($data, '-_', '+/'));
}