<?php
define('FILTER_SANITIZE_STRING', 513);
require_once "globals.php";
require_once "db.php";
require_once "models/User.php";
require_once "models/Message.php";
require_once "dao/UserDAO.php";

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_SPECIAL_CHARS);

// Verifica se o usuário está autenticado
$userData = $userDao->verifyToken();

if ($type === "update") {
	// Receber dados do POST
	$name     = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
	$lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_SPECIAL_CHARS);
	$email    = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$bio      = filter_input(INPUT_POST, "bio", FILTER_SANITIZE_SPECIAL_CHARS);

	// Atualiza os dados
	$userData->name     = $name;
	$userData->lastname = $lastname;
	$userData->email    = $email;
	$userData->bio      = $bio;

	// Upload da imagem
	if (!empty($_FILES["image"]["tmp_name"])) {
		$image      = $_FILES["image"];
		$validTypes = ["image/jpeg", "image/jpg", "image/png"];

		if (in_array($image["type"], $validTypes)) {
			$imageFile = ($image["type"] === "image/png")
				? imagecreatefrompng($image["tmp_name"])
				: imagecreatefromjpeg($image["tmp_name"]);

			$user      = new User();
			$imageName = $user->imageGenerateName();

			imagejpeg($imageFile, "./img/users/" . $imageName, 100);
			$userData->image = $imageName;
		} else {
			$message->setMessage("Tipo inválido de imagem, insira PNG ou JPG!", "error", "back");
			exit;
		}
	}

	$userDao->update($userData);
	$message->setMessage("Dados atualizados com sucesso!", "success", "edit_profile.php");
} elseif ($type === "changepassword") {
	$password        = filter_input(INPUT_POST, "password");
	$confirmpassword = filter_input(INPUT_POST, "confirmpassword");

	if ($password && $password === $confirmpassword) {
		$user             = new User();
		$user->id         = $userData->id;
		$user->password   = $user->generatePassword($password);

		$userDao->changePassword($user);
		$message->setMessage("Senha alterada com sucesso!", "success", "edit_profile.php");
	} else {
		$message->setMessage("As senhas não são iguais!", "error", "back");
	}
} else {
	$message->setMessage("Informações inválidas!", "error", "index.php");
}