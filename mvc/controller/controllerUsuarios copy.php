<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/usuariosDAO.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/controller/controller.php');

class controllerUsuarios extends controller
{
    // ==================== AUTENTICAÇÃO JWT ====================

    private function getJWTFromHeader()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (preg_match('/Bearer\s+(\S+)/i', $auth, $matches)) {
            return $matches[1];
        }
        return null;
    }


    public function userActive(){
        $user=$this->model->userActive();
        $user_id=$user[usuariosDAO::id];
        $user[usuariosDAO::id]=functionsJWT::encrypt($user_id, JWT_SECRET_KEY_2);
        echo json_encode($user);
    }
    
    /**
     * Retorna o ID do usuário autenticado via JWT
     */
    public function getAuthUserId()
    {
        $jwt = $this->getJWTFromHeader();
        if (!$jwt) {
            return null;
        }

        $payload = functionsJWT::validate($jwt);
        if (!$payload) {
            return null;
        }

        // Verifica expiração
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null;
        }

        if (!isset($payload['user_id'])) {
            return null;
        }

        $decryptedId = functionsJWT::decrypt($payload['user_id'], JWT_SECRET_KEY_2);
        return is_numeric($decryptedId) ? (int)$decryptedId : null;
    }

    // ==================== MÉTODOS DA API ====================

    public function login()
    {
		
		if (!arrayKeyExistsParameter(usuariosDAO::login)) {
			echo json_encode(["mensagem_erro" => "Login não informado."]);
            return;
        }
        if (!arrayKeyExistsParameter(usuariosDAO::senha)) {
			echo json_encode(["mensagem_erro" => "Senha não informada."]);
            return;
        }
        $login = getParameter(usuariosDAO::login);
        $senha = getParameter(usuariosDAO::senha);
		
        $result = $this->model->login($login, $senha);
        // Falha no login
        if (!isset($result['token']) || isset($result['mensagem_erro'])) {
			http_response_code(401);
            echo json_encode($result ?? ["mensagem_erro" => "Credenciais inválidas."]);
            return;
        }
        echo json_encode($result);
    }

    public function trocarSenha()
    {
        $this->model->cleanFields();

        $id = $this->getAuthUserId();
        if (!$id) {
            http_response_code(401);
            echo json_encode(["mensagem_erro" => "Usuário não autenticado ou token inválido."]);
            return;
        }

        $params = [];
        if (arrayKeyExistsParameter(usuariosDAO::senha_atual)) $params[usuariosDAO::senha_atual] = getParameter(usuariosDAO::senha_atual);
        if (arrayKeyExistsParameter(usuariosDAO::novaSenha)) $params[usuariosDAO::novaSenha] = getParameter(usuariosDAO::novaSenha);
        if (arrayKeyExistsParameter(usuariosDAO::repetirNovaSenha)) $params[usuariosDAO::repetirNovaSenha] = getParameter(usuariosDAO::repetirNovaSenha);

        echo json_encode($this->model->trocarSenha(
            $id,
            $params[usuariosDAO::senha_atual] ?? null,
            $params[usuariosDAO::novaSenha] ?? null,
            $params[usuariosDAO::repetirNovaSenha] ?? null
        ));
    }

    public function refreshToken()
    {
        $user_id = $this->getAuthUserId();
        if (!$user_id) {
            http_response_code(401);
            echo json_encode(["mensagem_erro" => "Token inválido ou expirado."]);
            return;
        }

        $current_time = time();
        $session_id = bin2hex(random_bytes(16));

        $payload = [
            'user_id'    => functionsJWT::encrypt((string)$user_id, JWT_SECRET_KEY_2),
            'session_id' => functionsJWT::encrypt($session_id, JWT_SECRET_KEY_2),
            'iss'        => 'https://seusite.com.br',
            'aud'        => 'https://seusite.com.br',
            'iat'        => $current_time,
            'exp'        => $current_time + JWT_TIME,
        ];

        $token = functionsJWT::generate($payload);

        echo json_encode([
            'token'       => $token,
            'expires_in'  => JWT_TIME,
            'expires_at'  => date('Y-m-d H:i:s', $current_time + JWT_TIME)
        ]);
    }

    // ==================== OUTROS MÉTODOS (mantidos iguais) ====================

    public function save() { echo json_encode(parent::save()); }
    public function create() { 
        echo json_encode(parent::create()); 
    }
    public function update($id_cript) { 
        $id=functionsJWT::decrypt($id_cript, JWT_SECRET_KEY_2);
        $result=parent::update($id);
        if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=$id_cript;
        if (isset($result["elements"][0][usuariosDAO::id])) $result["elements"][0][usuariosDAO::id]=$id_cript;
        echo json_encode($result); 
    }
    public function del($id_cript) { 
        try {
            $id=functionsJWT::decrypt($id_cript, JWT_SECRET_KEY_2);
            $result=parent::del($id);
            if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=$id_cript;
            if (isset($result["elements"][0][usuariosDAO::id])) $result["elements"][0][usuariosDAO::id]=$id_cript;
            foreach ($this->settingsImagesBase64 as $key => $value){
                $file_name=resultDataFieldByTitle($result,$key,0);
                deleteUpload($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
            }
        }catch (Exception $e) {
        }
        echo json_encode($result); 
    }
    public function find()
    {
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::e_mail);
        $this->model->setOrders([$this->model::id => "DESC"]);
        try {
            $result=parent::find();
            if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=functionsJWT::encrypt($result["params"][usuariosDAO::id], JWT_SECRET_KEY_2);
            for ($i=0; $i < count($result["elements"]); $i++) { 
                $result["elements"][$i][usuariosDAO::id]=functionsJWT::encrypt($result["elements"][$i][usuariosDAO::id], JWT_SECRET_KEY_2);
            }            
        }catch (Exception $e) {
        }
        echo json_encode($result);
    }
    public function findById($id_cript)
    {
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::e_mail);
        $id=functionsJWT::decrypt($id_cript, JWT_SECRET_KEY_2);
        $result=$this->model->findById($id);
        if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=$id_cript;
        if (isset($result["elements"][0][usuariosDAO::id])) $result["elements"][0][usuariosDAO::id]=$id_cript;
        echo json_encode($result);
    }
    public function resetPassword()
    {
        $this->model->cleanFields();
        $code = getParameter("code");
        $nova_senha = getParameter("nova_senha");
        $repetir = getParameter("repetir_nova_senha");

        if (!$code) {
            echo json_encode(["mensagem_erro" => "Código inválido."]);
            return;
        }

        echo json_encode($this->model->resetPasswordByCode($code, $nova_senha, $repetir));
    }
    public function __construct()
    {
        $params = [];
        if (notEmptyParameter(usuariosDAO::id)) $params[usuariosDAO::id] = getParameter(usuariosDAO::id);
        if (arrayKeyExistsParameter(usuariosDAO::nome)) $params[usuariosDAO::nome] = trim(getParameter(usuariosDAO::nome));
        if (arrayKeyExistsParameter(usuariosDAO::login)) $params[usuariosDAO::login] = getParameter(usuariosDAO::login);
        if (arrayKeyExistsParameter(usuariosDAO::senha)) $params[usuariosDAO::senha] = getParameter(usuariosDAO::senha);
        if (arrayKeyExistsParameter(usuariosDAO::e_mail)) $params[usuariosDAO::e_mail] = getParameter(usuariosDAO::e_mail);
        parent::__construct(new usuariosDAO($params));
    }
}
?>