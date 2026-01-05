<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/model.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/loginDAO.php');
class usuarioDAO extends model
{

	const table = "usuarios";
	const nome = "nome";
	const login = "login";
	const senha = "senha";
	const e_mail = "e_mail";
	const tentativas = "tentativas";

	public function login($login, $senha)
	{
		$login = hash('sha512', $login_);
		$senha = hash('sha512', $senha);
		$mensagem_erro = "";
		$this->cleanParams();
		try {
			$this->setParam(self::login, $login);
			$result = $this->find();
			if ((isset($result)) && (isset($result["data"]))) {
				if (count($result["data"]) == 0)
					$mensagem_erro = "Usuário ou senha inválido";
				else {
					$record_id = resultDataFieldByTitle($result, "id", 0);
					$record_tentativas = resultDataFieldByTitle($result, "tentativas", 0);
					$record_login = resultDataFieldByTitle($result, "login", 0);
					$record_senha = resultDataFieldByTitle($result, "senha", 0);
					$record_nome = resultDataFieldByTitle($result, "nome", 0);
					if (($record_senha == $senha) && ($record_login == $login)) {
						if (!isset($_SESSION))
							session_start();
						$_SESSION["id"] = $record_id;
						$_SESSION["login"] = $login_;
						$_SESSION["usuario"] = $record_nome;
						$_SESSION["time"] = time();
						$this->cleanParams();
						$this->setParams([
							self::id => $record_id
							,
							self::code => ''
							,
							self::code_time => null
							,
							self::tentativas => 0
						]);
						$this->update();
						$login = new loginDAO([]);
						$login->setParams([
							"id_usuarios" => $record_id,
							"hora_inicio" => date("H:i:s"),
							"hora_fim" => date("H:i:s"),
							"data_inicio" => date("Y-m-d"),
							"data_fim" => date("Y-m-d")
						]);
						$login->create();

					} else if ($record_tentativas >= 5)
						$mensagem_erro = "Usuário bloqueado já fora usadas 5 tentativas tente recuperar a conta com link <b>Esqueceu a senha</b> acima";
					else {
						$this->cleanParams();
						$this->setParams(["id" => $record_id, "tentativas" => ($record_tentativas + 1)]);
						$this->update();
						$mensagem_erro = "Usuário ou senha inválido você possue ainda mais " . (5 - $record_tentativas) . " tentativas";
					}
					unset($record_id);
					unset($record_tentativas);
					unset($record_login);
					unset($record_senha);
					unset($record_nome);
				}
				unset($result);
			}
			unset($result);
		} catch (Exception $error) {
		}
		unset($login);
		unset($senha);
		return ["mensagem_erro" => $mensagem_erro];
	}

	public function __construct($model_attributes)
	{
		parent::__construct($model_attributes, self::table, [self::nome, self::login, self::senha, self::e_mail, self::tentativas]);
	}
	public function trocar_senha($id, $senha_atual, $nova_senha, $repetir_nova_senha)
	{
		$mensagem_erro = "";
		$mensagem_sucesso = "";
		if ($nova_senha != $repetir_nova_senha) {
			$mensagem_erro = "A nova senha e a repetição da nova senha não conferem";
		} else {
			$this->cleanParams();
			$this->setParam(self::id, $id);
			$user_data = $this->findById($id); // Assuming findById returns array like in login
			if (isset($user_data["elements"][0])) {
				$user = $user_data["elements"][0];
				$current_password_hash = hash('sha512', $senha_atual);
				if ($user["senha"] == $current_password_hash) {
					$new_password_hash = hash('sha512', $nova_senha);
					$this->cleanParams();
					$this->setParams([
						self::id => $id,
						self::senha => $new_password_hash
					]);
					$result = $this->update();
					if (isset($result["elements"]))
						$mensagem_sucesso = "Senha alterada com sucesso!";
					else
						$mensagem_erro = "Erro ao atualizar a senha no banco de dados.";
				} else {
					$mensagem_erro = "Senha atual incorreta.";
				}
			} else {
				$mensagem_erro = "Usuário não encontrado.";
			}
		}

		return ["mensagem_erro" => $mensagem_erro, "mensagem_sucesso" => $mensagem_sucesso];
	}

}

if (isset($_REQUEST['acao']) && !empty($_REQUEST['acao'])) {
	$GLOBALS["base_server_path_files"] = isset($GLOBALS["base_server_path_files"]) ? $GLOBALS["base_server_path_files"] : $_SERVER['DOCUMENT_ROOT'];

	$acao = $_REQUEST['acao'];
	$usuarioDAO = new usuarioDAO([]);

	if ($acao == 'trocar_senha') {
		$id = $_REQUEST['id'];
		$senha_atual = $_REQUEST['senha_atual'];
		$nova_senha = $_REQUEST['nova_senha'];
		$repetir_nova_senha = $_REQUEST['repetir_nova_senha'];

		$result = $usuarioDAO->trocar_senha($id, $senha_atual, $nova_senha, $repetir_nova_senha);
		echo json_encode($result);
	}
}
?>