<?php

require_once "models/User.php";
require_once "models/Message.php";

class UserDAO implements UserDAOInterface
{
	private $conn;
	private $url;
	private $message;

	public function __construct(PDO $conn, $url)
	{
		$this->conn = $conn;
		$this->url = $url;
		$this->message = new Message($url);
	}

	private function executeSelect($query, $params)
	{
		$stmt = $this->conn->prepare($query);
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value);
		}
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			$data = $stmt->fetch();
			return $this->buildUser($data);
		}
		return false;
	}

	private function executeUpdate($query, $params)
	{
		$stmt = $this->conn->prepare($query);
		foreach ($params as $key => $value) {
			$stmt->bindValue($key, $value);
		}
		$stmt->execute();
	}

	public function buildUser($data)
	{
		$user = new User();
		foreach (['id', 'name', 'lastname', 'email', 'password', 'image', 'bio', 'token'] as $field) {
			if (isset($data[$field])) {
				$user->$field = $data[$field];
			}
		}
		return $user;
	}

	public function create(User $user, $authUser = false)
	{
		$query = "INSERT INTO users (name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)";
		$params = [
			':name' => $user->name,
			':lastname' => $user->lastname,
			':email' => $user->email,
			':password' => $user->password,
			':token' => $user->token
		];
		$this->executeUpdate($query, $params);

		if ($authUser) {
			$this->setTokenToSession($user->token);
		}
	}

	public function update(User $user, $redirect = true)
	{
		$query = "UPDATE users SET name = :name, lastname = :lastname, email = :email, image = :image, bio = :bio, token = :token WHERE id = :id";
		$params = [
			':name' => $user->name,
			':lastname' => $user->lastname,
			':email' => $user->email,
			':image' => $user->image,
			':bio' => $user->bio,
			':token' => $user->token,
			':id' => $user->id
		];
		$this->executeUpdate($query, $params);

		if ($redirect) {
			$this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
		}
	}

	public function verifyToken($protected = false)
	{
		if (!empty($_SESSION["token"])) {
			$user = $this->findByToken($_SESSION["token"]);
			if ($user) return $user;
		}
		if ($protected) {
			$this->message->setMessage("Faça a autenticação para acessar esta página!", "error", "index.php");
		}
	}

	public function setTokenToSession($token, $redirect = true)
	{
		$_SESSION["token"] = $token;
		if ($redirect) {
			$this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
		}
	}

	public function authenticateUser($email, $password)
	{
		$user = $this->findByEmail($email);
		if ($user && password_verify($password, $user->password)) {
			$token = $user->generateToken();
			$this->setTokenToSession($token, false);
			$user->token = $token;
			$this->update($user, false);
			return true;
		}
		return false;
	}

	public function findByEmail($email)
	{
		if ($email == "") return false;
		return $this->executeSelect("SELECT * FROM users WHERE email = :email", [':email' => $email]);
	}

	public function findById($id)
	{
		if ($id == "") return false;
		return $this->executeSelect("SELECT * FROM users WHERE id = :id", [':id' => $id]);
	}

	public function findByToken($token)
	{
		if ($token == "") return false;
		return $this->executeSelect("SELECT * FROM users WHERE token = :token", [':token' => $token]);
	}

	public function destroyToken()
	{
		$_SESSION["token"] = "";
		$this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
	}

	public function changePassword(User $user)
	{
		$query = "UPDATE users SET password = :password WHERE id = :id";
		$params = [
			':password' => $user->password,
			':id' => $user->id
		];
		$this->executeUpdate($query, $params);
		$this->message->setMessage("Senha alterada com sucesso!", "success", "editprofile.php");
	}
}