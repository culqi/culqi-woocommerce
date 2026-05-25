<?php
if (!defined('ABSPATH')) {
    exit;
}
function culqi_encrypt_data_with_rsa(string $jsonData, string $publicKeyString = ''): ?string {
    $logger = Culqi_Logger::get_instance();
    $logger->debug('Token', 'RSA encryption started');

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

        $logger->debug('Token', 'RSA encryption completed successfully');
        return base64_encode($encrypted);
    } catch (Exception $e) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $logger = wc_get_logger();
            $logger->error('RSA Encryption Error: ' . $e->getMessage(), array('source' => 'culqi'));
        }
        $logger->error('Token', 'RSA encryption failed', [
            'error' => $e->getMessage()
        ]);
        return null;
    }
}


function culqi_generate_token($is_admin = false)
{
    $logger = Culqi_Logger::get_instance();
    $logger->debug('Token', 'Token generation started', ['is_admin' => $is_admin]);

    try {
        $minutes = EXPIRATION_TIME;
        $expirationTimeInSeconds = $minutes * 60;
        $exp = time() + $expirationTimeInSeconds;

        $config = culqi_get_config();
        if(!isset($config->env)) {
            if(!$is_admin) {
                $logger->warning('Token', 'Config not set, cannot generate token');
                wc_add_notice(__('Debes configurar tu llave pública.', 'culqi'), 'error');
                return;
            }
        } else {
            $data = [
                "pk" => $config->env,
                "exp" => $exp
            ];

            $encryptedData = culqi_encrypt_data_with_rsa(wp_json_encode($data), $config->rsa_pk_culqi ?? '');
            $logger->debug('Token', 'Token generated successfully', ['exp' => $exp]);
            return $encryptedData;
        }
    } catch(Exception $e) {
        $logger->error('Token', 'Token generation failed', ['error' => $e->getMessage()]);
        return '';
    }
}

function culqi_verify_jwt_token($token)
{
    $logger = Culqi_Logger::get_instance();
    $logger->debug('Token', 'Token verification started');

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
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            throw new Exception('Token has expired.');
        }
        $logger->debug('Token', 'Token verified successfully', $payload);
        return $payload;
    } catch (Exception $e) {
        // throw new Exception('Token validation failed: ' . $e->getMessage());
        $logger->warning('Token', 'Token verification failed', ['error' => $e->getMessage()]);
        return false;
    }
}

