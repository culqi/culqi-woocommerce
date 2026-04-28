<?php

if (!defined('ABSPATH')) {
    exit;
}

class Culqi_Logger
{
    private static $instance = null;
    private $logger;
    private $source = 'culqi';

    private $sensitive_fields = [
        'cardNumber',
        'card_number',
        'cvv',
        'token',
        'authorization',
        'password',
        'secret',
        'rsa_sk_plugin',
        'rsa_pk_culqi',
        'pk',
        'private_key',
        'public_key',
    ];

    private function __construct()
    {
        $this->logger = wc_get_logger();
    }

    public static function get_instance(): Culqi_Logger
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function debug(string $module, string $message, array $context = []): void
    {
        $this->log('debug', $module, $message, $context);
    }

    public function info(string $module, string $message, array $context = []): void
    {
        $this->log('info', $module, $message, $context);
    }

    public function notice(string $module, string $message, array $context = []): void
    {
        $this->log('notice', $module, $message, $context);
    }

    public function warning(string $module, string $message, array $context = []): void
    {
        $this->log('warning', $module, $message, $context);
    }

    public function error(string $module, string $message, array $context = []): void
    {
        $this->log('error', $module, $message, $context);
    }

    public function critical(string $module, string $message, array $context = []): void
    {
        $this->log('critical', $module, $message, $context);
    }

    public function alert(string $module, string $message, array $context = []): void
    {
        $this->log('alert', $module, $message, $context);
    }

    public function emergency(string $module, string $message, array $context = []): void
    {
        $this->log('emergency', $module, $message, $context);
    }

    private function log(string $level, string $module, string $message, array $context = []): void
    {
        if (!$this->should_log($level)) {
            return;
        }

        $formatted_context = $this->format_context($context);
        $formatted_message = $this->format_message($module, $message, $formatted_context);

        switch ($level) {
            case 'debug':
                $this->logger->debug($formatted_message, ['source' => $this->source]);
                break;
            case 'info':
                $this->logger->info($formatted_message, ['source' => $this->source]);
                break;
            case 'notice':
                $this->logger->notice($formatted_message, ['source' => $this->source]);
                break;
            case 'warning':
                $this->logger->warning($formatted_message, ['source' => $this->source]);
                break;
            case 'error':
                $this->logger->error($formatted_message, ['source' => $this->source]);
                break;
            case 'critical':
                $this->logger->critical($formatted_message, ['source' => $this->source]);
                break;
            case 'alert':
                $this->logger->alert($formatted_message, ['source' => $this->source]);
                break;
            case 'emergency':
                $this->logger->emergency($formatted_message, ['source' => $this->source]);
                break;
        }
    }

    private function is_debug_active(): bool
    {
        if ((defined('WP_DEBUG') && WP_DEBUG) || (defined('CULQI_DEBUG') && CULQI_DEBUG)) {
            return true;
        }

        $settings = get_option('woocommerce_culqi_settings', []);
        if (!empty($settings['debug_mode']) && $settings['debug_mode'] === 'yes') {
            return true;
        }

        return false;
    }

    private function should_log(string $level): bool
    {
        if ($level === 'debug' && !$this->is_debug_active()) {
            return false;
        }

        if (!$this->is_debug_active()) {
            $non_prod_levels = ['debug', 'info', 'notice'];
            if (in_array($level, $non_prod_levels)) {
                return false;
            }
        }

        return true;
    }

    private function should_sanitize(): bool
    {
        return !(defined('CULQI_DEBUG_SANITIZE') && CULQI_DEBUG_SANITIZE);
    }

    private function format_context(array $context): array
    {
        return $this->sanitize($context);
    }

    private function sanitize(array $data, int $depth = 0): array
    {
        if (!$this->should_sanitize()) {
            return $data;
        }

        if ($depth > 10) {
            return ['**MAX_DEPTH**'];
        }

        $sanitized = [];
        foreach ($data as $key => $value) {
            if ($this->is_sensitive_key($key)) {
                $sanitized[$key] = '***MASKED***';
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitize($value, $depth + 1);
            } elseif (is_string($value) && $this->looks_like_sensitive_string($value)) {
                $sanitized[$key] = $this->mask_string($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    private function is_sensitive_key(string $key): bool
    {
        $lower_key = strtolower($key);
        foreach ($this->sensitive_fields as $field) {
            if (strpos($lower_key, strtolower($field)) !== false) {
                return true;
            }
        }
        return false;
    }

    private function looks_like_sensitive_string(string $value): bool
    {
        if (strlen($value) > 50 && strpos($value, '.') !== false) {
            return true;
        }
        if (preg_match('/^[A-Za-z0-9\=\-_]{50,}$/', $value)) {
            return true;
        }
        return false;
    }

    private function mask_string(string $value): string
    {
        if (strlen($value) <= 8) {
            return '***';
        }
        return substr($value, 0, 8) . '***';
    }

    private function format_message(string $module, string $message, array $context): string
    {
        $prefix = '[' . $module . '] ' . $message;
        if (empty($context)) {
            return $prefix;
        }

        $encoded = wp_json_encode($context);
        if ($encoded === false) {
            return $prefix . ' | Context: [JSON_ENCODE_FAILED]';
        }

        return $prefix . ' | Context: ' . $encoded;
    }

    public function set_source(string $source): void
    {
        $this->source = $source;
    }
}
