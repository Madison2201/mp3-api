<?php
namespace api\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $key = 'Kw6Fb3QmZmLAKd4HFTS2lmI_cm3Id5xQ';
    private string $issuer = 'http://your-domain.com';
    private int $expiration = 3600;

    public function createToken(array $payload): string
    {
        $time = time();

        $token = array_merge($payload, [
            'iss' => $this->issuer,
            'iat' => $time,
            'exp' => $time + $this->expiration,
        ]);

        return JWT::encode($token, $this->key, 'HS256');
    }
    public function encode(array $data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // Токен действует 1 час
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        return JWT::encode($payload, $this->key);
    }

    public function validateToken(string $token): array
    {
        return (array)JWT::decode($token, new Key($this->key, 'HS256'));
    }

    public function decode($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new \yii\web\UnauthorizedHttpException('Invalid token.');
        }
    }
}
