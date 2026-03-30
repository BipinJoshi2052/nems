<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Log;

class JwtService
{
    protected string $secret;
    protected int $expiry;

    public function __construct()
    {
        $this->secret = config('app.key');
        $this->expiry = 3600; // 1 hour for access token
    }

    /**
     * Create a JWT token.
     */
    public function createToken(array $payload, int $ttl = null): string
    {
        $ttl = $ttl ?? $this->expiry;
        
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;
        
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Validate and decode a JWT token.
     */
    public function decodeToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        
        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;
        
        $signature = $this->base64UrlEncode(hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true));
        
        if ($signature !== $base64UrlSignature) {
            return null;
        }
        
        $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);
        
        if (!isset($payload['exp']) || $payload['exp'] < time()) {
            return null;
        }
        
        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
