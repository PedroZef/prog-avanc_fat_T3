<?php

class Message
{
	private string $url;

	public function __construct(string $url)
	{
		$this->url = rtrim($url, '/') . '/'; // Garante que a URL base termina com "/"
	}

	/**
	 * Define a mensagem e redireciona
	 */
	public function setMessage(string $msg, string $type, string $redirect = "index.php"): void
	{
		$_SESSION["msg"]  = $msg;
		$_SESSION["type"] = $type;

		// Evita redirecionamento malicioso
		if ($redirect === "back" && isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		} else {
			$safeRedirect = filter_var($redirect, FILTER_SANITIZE_URL);
			header("Location: " . $this->url . $safeRedirect);
		}

		exit; // Garante que o script pare após redirecionar
	}

	/**
	 * Retorna a mensagem atual
	 */
	public function getMessage(): array|false
	{
		if (!empty($_SESSION["msg"])) {
			return [
				"msg"  => $_SESSION["msg"],
				"type" => $_SESSION["type"]
			];
		}

		return false;
	}

	/**
	 * Limpa a mensagem da sessão
	 */
	public function clearMessage(): void
	{
		unset($_SESSION["msg"], $_SESSION["type"]);
	}
}