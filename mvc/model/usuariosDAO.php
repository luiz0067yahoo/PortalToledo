<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/model.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/loginDAO.php');
class usuariosDAO extends model
{
    const table = "usuarios";

    const id = "id";
    const nome = "nome";
    const login = "login";
    const senha = "senha";
    const email = "e_mail";
    const tentativas = "tentativas";
    const code = "code";
    const codeTime = "code_time";

    const novaSenha = "nova_senha";
    const repetirNovaSenha = "repetir_nova_senha";

    
    public function login($login, $senha)
    {
        $login = hash('sha512', $login); // Fixed variable name from $login_ to $login
        $senha = hash('sha512', $senha);
        $mensagem_erro = "";
        $this->cleanParams();
        $this->cleanFields();
        $this->setFields([
            self::id,self::nome,self::login,self::senha,self::email, self::tentativas
        ]);
        $this->setParam(self::login, $login);
        try {
            $result = $this->find();
            if ((isset($result)) && (isset($result["elements"]))) {
                if (count($result["elements"]) == 0){
                    $mensagem_erro = "Usuário ou senha inválido";
                }
                else {
                    $user_id = $result["elements"][0]["id"];
                    $user_login = $result["elements"][0]["login"];
                    $user_senha = $result["elements"][0]["senha"];
                    $user_nome = $result["elements"][0]["nome"];
                    $user_tentativas = $result["elements"][0]["tentativas"];
                    $session_id = bin2hex(random_bytes(16)); // ou uniqid('sess_', true)
                    if (($user_senha == $senha) && ($user_login == $login)) {
                        // JWT Generation
                        $payload = [
                            'user_id'    => functionsJWT::encrypt($user_id, JWT_SECRET_KEY_2),
                            'session_id' => functionsJWT::encrypt($session_id, JWT_SECRET_KEY_2),
                            'iss'        => 'https://portaltoledo.com',
                            'aud'        => 'https://portaltoledo.com',
                            'iat'        => time(),
                            'exp'        => time() + (JWT_TIME) // 1 Hour expiration
                        ];
                        $token = functionsJWT::generate($payload);

                        $this->cleanParams();
                        $this->setParams([
                            self::code => '',
                            self::codeTime => null,
                            self::tentativas => 0
                        ]);
                        $this->update($user_id);
                        
                        // Maintain Audit Log
                        $login_log = new loginDAO([]);
                        $login_log->setParams([
                            loginDAO::idUsuario => $user_id,
                            loginDAO::horaInicio => date("H:i:s"),
                            loginDAO::dataInicio => date("Y-m-d"),
                            loginDAO::token => $token,
                        ]);
                        //return $login_log->createSQL();
                            $login_log->create();

                        return ["token" => $token];

                    } else if ($user_tentativas >= 5)
                        $mensagem_erro = "Usuário bloqueado já fora usadas 5 tentativas tente recuperar a conta com link <b>Esqueceu a senha</b> acima";
                    else {
                        $this->cleanParams();
                        $this->setParams([ "tentativas" => ($user_tentativas + 1)]);
                        $this->update($user_id);
                        $mensagem_erro = "Usuário ou senha inválido você possue ainda mais " . (5 - $user_tentativas) . " tentativas";
                    }
                }
            }
        } catch (Exception $error) {
            $mensagem_erro = $error->getMessage();
        }
        return ["mensagem_erro" => $mensagem_erro];
    }

   
    public function trocarSenha($id, $senha_atual, $nova_senha, $repetir)
    {
        if ($nova_senha !== $repetir) {
            return ["mensagem_erro" => "As senhas não conferem"];
        }

        $this->cleanParams();
        $user = $this->findById($id);

        if (!isset($user["elements"][0])) {
            return ["mensagem_erro" => "Usuário não encontrado"];
        }

        $user = $user["elements"][0];

        if ($user[self::senha] !== hash('sha512', $senha_atual)) {
            return ["mensagem_erro" => "Senha atual incorreta"];
        }

        $this->cleanParams();
        $this->setParams([
            self::id => $id,
            self::senha => hash('sha512', $nova_senha),
            self::tentativas => 0
        ]);

        $this->update($id);

        return ["mensagem_sucesso" => "Senha alterada com sucesso"];
    }

	public function refreshToken() {
        $user_id = $this->getAuthUserId();
        if (!$user_id) {
            return ["mensagem_erro" => "Token inválido ou expirado."];
        }

        // Reemitir token com novos dados
        $current_time = time();
        $session_id = uniqid('sess_', true);

        $jwt = $this->setDataToken([
            'user_id'     => $this->encryptData($user_id, JWT_SECRET_KEY_2),
            'session_id'  => $this->encryptData($session_id, JWT_SECRET_KEY_2),
            'iss'         => 'https://seusite.com',
            'aud'         => 'https://seusite.com',
            'iat'         => $current_time,
            'exp'         => $current_time + JWT_TIME,
        ]);

        return [
            'token' => $jwt,
            'expires_in' => JWT_TIME
        ];
    }
   
    	
	public function __construct($model_attributes)
	{
		parent::__construct($model_attributes, self::table, [self::nome, self::login, self::senha, self::email, self::tentativas]);
	}
	
	public function save()
    {
		$this->setParam(self::login, hash('sha512', $this->getParam(self::login)));
		$this->setParam(self::senha, hash('sha512', $this->getParam(self::senha)));
		$this->setParam(self::tentativas, 0);
		$this->setParam(self::code, null);
		$this->setParam(self::codeTime, null);
		return parent::save();
    }

    public function saveSQL()
    {
		$this->setParam(self::login, hash('sha512', $this->getParam(self::login)));
		$this->setParam(self::senha, hash('sha512', $this->getParam(self::senha)));
		$this->setParam(self::tentativas, 0);
		$this->setParam(self::code, null);
		$this->setParam(self::codeTime, null);
		return parent::saveSQL();
    }

    public function resetPasswordByCode($code, $nova_senha, $repetir_nova_senha)
    {
        if ($nova_senha != $repetir_nova_senha) {
            return ["mensagem_erro" => "As senhas não conferem."];
        }
        
        // Find user by code
        $this->cleanParams();
        $this->setParam('code', $code);
        $result = $this->find();
        
        if (isset($result['data']) && count($result['data']) > 0) {
             $id = resultDataFieldByTitle($result, "id", 0);
             $hash = hash('sha512', $nova_senha);
             
             // Update
             $this->cleanParams();
             $this->setParam(self::id, $id);
             $this->setParam(self::senha, $hash);
             $this->setParam(self::code, ''); // Clear code
             
             if ($this->update()) {
                 return ["mensagem_sucesso" => "Senha alterada com sucesso!"];
             } else {
                 return ["mensagem_erro" => "Erro ao atualizar senha."];
             }
        } else {
            return ["mensagem_erro" => "Código inválido ou expirado."];
        }
    }

    public function create()
    {
		$this->setParam(self::date_insert, date("Y-m-d H:i:s"));
        return parent::create();
    }

    public function createSQL()
    {
		$this->setParam(self::date_insert, date("Y-m-d H:i:s"));
        return parent::createSQL($id);
    }

    public function update($id)
    {
        $this->setParam(self::date_update, date("Y-m-d H:i:s"));
        return parent::update($id);
    }

    public function updateSQL($id)
    {
        $this->setParam(self::date_update, date("Y-m-d H:i:s"));
        return parent::updateSQL($id);
    }

    public function findById($id)
    {
        return parent::findById($id);
    }

    public function findByIdSQL($id)
    {
        return parent::findByIdSQL($id);
    }

    public function find()
    {
        return parent::find();
    }

    public function findSQL()
    {
        return parent::findSQL();
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    public function deleteSQL($id)
    {
        return parent::deleteSQL($id);
    }
    
    public function logout()
    {        
        $result=["mensagem_erro" => "Erro ao realizar logout."];
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = explode(" ", $_SERVER['HTTP_AUTHORIZATION'])[1];
            $validate_token = functionsJWT::validate($token);
            $validate_user = $this->userActive($token);
            $login_log = new loginDAO([]);
            $login=$login_log->logout($validate_user[self::id],$token);
            return ["mensagem_sucesso" => "Logout realizado com sucesso."];
        }
        return $result;
    }


    public function userActive($token)
    {
        $id = functionsJWT::getUserId();
        $this->cleanFields();
		$this->setFields([
			self::id,self::nome,self::email
		]);
        $user=self::findById($id)["elements"][0];

        $login_log = new loginDAO([]);
        $login=$login_log->findUserIdToken($user[self::id],$token);
        if($login["data_fim"]==null &&$login["hora_fim"]==null){
            return $user;
        }
        return false;
    }

    public function controlAcess() {
        $result=false;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $HTTP_AUTHORIZATION = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
            if(count($HTTP_AUTHORIZATION)==2){
                $token = $HTTP_AUTHORIZATION[1];
                $validate_token = functionsJWT::validate($token);
                $validate_user = $this->userActive($token);
                $result= (
                    ($validate_token && ($validate_token['exp'] > time()))
                    &&
                    ($validate_user && ($validate_user[self::id]==functionsJWT::getUserId())) 
                ); 
            }
        }
        if(!$result){
            http_response_code(401);
            echo json_encode(["mensagem_erro" => "Acesso negado."]);
        }
        return $result;
    }

   

}
?>