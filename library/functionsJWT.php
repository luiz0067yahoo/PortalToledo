<?php

class functionsJWT {

    private const ALG = 'HS512';

    public static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode($data) {
        $padding = strlen($data) % 4;
        if ($padding) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function encrypt($data, $key) {
        $cipher = 'AES-256-CBC';
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return self::base64url_encode($iv . $encrypted);
    }

    public static function decrypt($data, $key) {
        $cipher = 'AES-256-CBC';
        $data = self::base64url_decode($data);
        $ivSize = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivSize);
        $encrypted = substr($data, $ivSize);
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }

    public static function generate(array $payload) {
        $header = self::base64url_encode(json_encode([
            'alg' => self::ALG,
            'typ' => 'JWT'
        ]));

        $payload = self::base64url_encode(json_encode($payload));

        $signature = hash_hmac(
            'sha512',
            "$header.$payload",
            JWT_SECRET_KEY,
            true
        );

        return "$header.$payload." . self::base64url_encode($signature);
    }

    public static function validate($jwt) {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) return false;
        
        [$header, $payload, $signature] = $parts;
        
        $check = self::base64url_encode(
            hash_hmac('sha512', "$header.$payload", JWT_SECRET_KEY, true)
        );
        
        if (!hash_equals($signature, $check)) {
            return false;
        }
        
        return json_decode(self::base64url_decode($payload), true);
    }

    public static function controlAcess() {
        $jwt = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];
        $validate = self::validate($jwt);
        if ($validate && $validate['exp'] > time()) {
            return true;
        }
        http_response_code(401);
        echo json_encode(["mensagem_erro" => "Acesso negado."]);
        return false;
    }

    public static function getPayload() {
        // Verifica se o header Authorization existe e está no formato Bearer
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || 
            !str_starts_with($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ')) {
            return null;
        }

        // Extrai o token JWT
        $jwt = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];

        // Valida o token (assumindo que seu método validate retorna o payload decodificado ou false)
        $payload = self::validate($jwt);

        // Verifica se a validação foi bem-sucedida e se o token não expirou
        if ($payload && isset($payload['exp']) && $payload['exp'] > time()) {
            return $payload; // Retorna o payload completo (array associativo)
        }

        // Token inválido ou expirado
        return null;
    }
    
    public static function getToken() {
        // Verifica se o header Authorization existe e está no formato Bearer
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || 
            !str_starts_with($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ')) {
            return null;
        }

        // Extrai o token JWT
        $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];

        return $token;
    }

    public static function getUserId()
    {
        // Obtém o payload completo (já validado e com expiração verificada)
        $payload = self::getPayload();

        if (!$payload) {
            return false;
        }

        // Verifica se o campo user_id existe no payload
        if (!isset($payload['user_id'])) {
            return false;
        }

        // Descriptografa o user_id usando a mesma chave secreta usada na criação
        $user_id_encrypted = $payload['user_id'];

        // Assumindo que functionsJWT::decrypt() existe e funciona com a mesma chave
        $user_id = functionsJWT::decrypt($user_id_encrypted, JWT_SECRET_KEY_2);

        // Opcional: valida se o resultado é um número inteiro válido
        if ($user_id === false || !is_numeric($user_id)) {
            return false;
        }

        return (int) $user_id;
    }

    public static function sessionCount()
    {
        $payload = self::getPayload();
        if ($payload&&isset($payload['exp'])) {
            return date("H:i:s",$payload['exp']-time());
        }
        return false;
    }
}

?>