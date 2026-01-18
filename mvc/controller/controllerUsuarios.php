<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/usuariosDAO.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/controller/controller.php');

class controllerUsuarios extends controller
{

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
        $user=$this->model->userActive(functionsJWT::getToken());
        $user_id=$user[usuariosDAO::id];
        $user[usuariosDAO::id]=functionsJWT::encrypt($user_id, JWT_SECRET_KEY_2);
        echo json_encode($user);
    }
    
    
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

    public function logout()
    {
		
	
        $result = $this->model->logout();
        // Falha no login
        if (isset($result['mensagem_erro'])) {
			http_response_code(401);
            echo json_encode($result);
            return;
        }
        echo json_encode($result);
    }

    public function trocarSenha()
    {
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::email);
        $this->model->addField(usuariosDAO::senha);
        $id = $this->getAuthUserId();
        if (!$id) {
            http_response_code(401);
            echo json_encode(["mensagem_erro" => "Usuário não autenticado ou token inválido."]);
            return;
        }

        $params = [];
        if (arrayKeyExistsParameter(usuariosDAO::senhaAtual)) $params[usuariosDAO::senhaAtual] = getParameter(usuariosDAO::senhaAtual);
        if (arrayKeyExistsParameter(usuariosDAO::novaSenha)) $params[usuariosDAO::novaSenha] = getParameter(usuariosDAO::novaSenha);
        if (arrayKeyExistsParameter(usuariosDAO::repetirNovaSenha)) $params[usuariosDAO::repetirNovaSenha] = getParameter(usuariosDAO::repetirNovaSenha);

        echo json_encode($this->model->trocarSenha(
            $id,
            $params[usuariosDAO::senhaAtual] ?? null,
            $params[usuariosDAO::novaSenha] ?? null,
            $params[usuariosDAO::repetirNovaSenha] ?? null
        ));
    }

    public function renewToken()
    {
       echo json_encode($this->model->renewToken());
    }
    
    public function save() { 
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::email);

		$this->model->setParam(usuariosDAO::login, hash('sha512', $this->model->getParam(usuariosDAO::login)));
		$this->model->setParam(usuariosDAO::senha, hash('sha512', $this->model->getParam(usuariosDAO::senha)));
		$this->model->setParam(usuariosDAO::tentativas, 0);
		$this->model->setParam(usuariosDAO::code, null);
		$this->model->setParam(usuariosDAO::codeTime, null);

        $result=parent::save();
        $id_cript=functionsJWT::encrypt($result["elements"][0][usuariosDAO::id], JWT_SECRET_KEY_2);
        if (isset($result["elements"][0][usuariosDAO::id])) $result["elements"][0][usuariosDAO::id]=$id_cript;
        if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=$id_cript;            
        $result=parent::save();
        echo json_encode($result); 
    }
    
    public function create() { 
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::email);

        $this->model->setParam(usuariosDAO::login, hash('sha512', $this->model->getParam(usuariosDAO::login)));
        $this->model->setParam(usuariosDAO::senha, hash('sha512', $this->model->getParam(usuariosDAO::senha)));
        $this->model->setParam(usuariosDAO::tentativas, 0);
        $this->model->setParam(usuariosDAO::code, null);
        $this->model->setParam(usuariosDAO::codeTime, null);

        $result=parent::create();
        $id_cript=functionsJWT::encrypt($result["elements"][0][usuariosDAO::id], JWT_SECRET_KEY_2);
        if (isset($result["elements"][0][usuariosDAO::id])) $result["elements"][0][usuariosDAO::id]=$id_cript;
        if (isset($result["params"][usuariosDAO::id])) $result["params"][usuariosDAO::id]=$id_cript;
        echo json_encode($result); 
    }
    
    public function update($id_cript) { 
        $this->model->cleanFields();
        $this->model->addField(usuariosDAO::id);
        $this->model->addField(usuariosDAO::nome);
        $this->model->addField(usuariosDAO::email);

        if(!empty($this->model->getParam(usuariosDAO::login))) {
            $this->model->setParam(usuariosDAO::login, hash('sha512', $this->model->getParam(usuariosDAO::login)));
        }
        else {
            $this->model->unParam(usuariosDAO::login);
        }
        if(!empty($this->model->getParam(usuariosDAO::senha))) {
            $this->model->setParam(usuariosDAO::senha, hash('sha512', $this->model->getParam(usuariosDAO::senha)));
        }
        else {
            $this->model->unParam(usuariosDAO::senha);
        }

        $this->model->setParam(usuariosDAO::tentativas, 0);
        $this->model->setParam(usuariosDAO::code, null);
        $this->model->setParam(usuariosDAO::codeTime, null);

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
        $this->model->addField(usuariosDAO::email);
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
        $this->model->addField(usuariosDAO::email);
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
        if (arrayKeyExistsParameter(usuariosDAO::email)) $params[usuariosDAO::email] = getParameter(usuariosDAO::email);
        parent::__construct(new usuariosDAO($params));
    }
}
?>