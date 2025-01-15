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
        
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($publicKey);
        }

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
    if(!$config->rsa_pk_culqi) {
        wc_add_notice(__('Debes configurar tu llave pública.', 'culqi'), 'error');
        return;
    }
    $data = [
        "pk" => $config->public_key,
        "exp" => $exp
    ];

    $encryptedData = encrypt_data_with_rsa(wp_json_encode($data), $config->rsa_pk_culqi);
    return $encryptedData;
}

function verify_jwt_token($token)
{
    try {
        $config = culqi_get_config();
        $encryptedToken = base64_decode($token);
        if ($encryptedToken === false) {
            throw new Exception('Invalid Base64 token.');
        }
        $decrypted = '';
        $success = openssl_private_decrypt($encryptedToken, $decrypted, $config->rsa_sk_plugin, OPENSSL_PKCS1_OAEP_PADDING);
        if (!$success) {
            throw new Exception('Failed to decrypt the token.');
        }
        $payload = json_decode($decrypted, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid token payload format.');
        }
        // Validate the expiration time
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            throw new Exception('Token has expired.');
        }
        return $payload;
    } catch (Exception $e) {
        // throw new Exception('Token validation failed: ' . $e->getMessage());
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
