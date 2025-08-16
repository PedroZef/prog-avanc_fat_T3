<?php

require_once "globals.php";
require_once "db.php";
require_once "models/Movie.php";
require_once "models/Message.php";
require_once "dao/UserDAO.php";
require_once "dao/MovieDAO.php";

// FILTER_SANITIZE_STRING foi removido no PHP 8. Esta é uma garantia de compatibilidade.
if (!defined('FILTER_SANITIZE_STRING')) {
    define('FILTER_SANITIZE_STRING', 513);
}

// Função auxiliar para processar o upload da imagem do filme
function processMovieImage($imageFile, Message $message)
{
    if (isset($imageFile) && !empty($imageFile["tmp_name"])) {
        $image = $imageFile;
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];

        // Verificando o tipo da imagem
        if (in_array($image["type"], $imageTypes)) {
            if ($image["type"] === "image/png") {
                $gdImage = imagecreatefrompng($image["tmp_name"]);
            } else {
                $gdImage = imagecreatefromjpeg($image["tmp_name"]);
            }

            // Gerando nome da imagem
            $movie = new Movie();
            $imageName = $movie->imageGenerateName();

            imagejpeg($gdImage, "./img/movies/" . $imageName, 100);

            return $imageName;
        } else {
            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            return false;
        }
    }
    return null; // Nenhuma imagem foi enviada
}

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_SPECIAL_CHARS);

// Resgata dados do usuário
$userData = $userDao->verifyToken();

if ($type === "create" || $type === "update") {

    // Dados comuns para criar e atualizar
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    // Validação mínima de dados
    if (empty($title) || empty($description) || empty($category)) {
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
        exit();
    }
    if ($type === "create") {
        $movie = new Movie();
        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;
        // Processa o upload da imagem
        $imageName = processMovieImage($_FILES["image"], $message);
        if ($imageName === false) { // Erro durante o upload
            exit();
        }
        $movie->image = $imageName;
        $movieDao->create($movie);
    } elseif ($type === "update") {
        $id = filter_input(INPUT_POST, "id");
        $movieData = $movieDao->findById($id);
        if ($movieData) {
            // Verifica se o filme pertence ao usuário
            if ($movieData->users_id === $userData->id) {
                // Atualiza os dados do filme
                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;
                // Processa o upload da imagem
                $imageName = processMovieImage($_FILES["image"], $message);
                if ($imageName === false) { // Erro durante o upload
                    exit();
                }
                // Atualiza a imagem apenas se uma nova foi enviada
                if ($imageName !== null) {
                    $movieData->image = $imageName;
                }
                $movieDao->update($movieData);
            } else {
                $message->setMessage("Informações inválidas! Você não tem permissão para editar este filme.", "error", "index.php");
            }
        } else {
            $message->setMessage("Informações inválidas! Filme não encontrado.", "error", "index.php");
        }
    }
} elseif ($type === "delete") {
    // Pega os dados do formulário
    $id = filter_input(INPUT_POST, "id");
    $movie = $movieDao->findById($id);
    if ($movie) {
        // Verifica se o filme pertence ao usuário
        if ($movie->users_id === $userData->id) {
            $movieDao->destroy($movie->id);
        } else {
            $message->setMessage("Informações inválidas! Você não tem permissão para deletar este filme.", "error", "index.php");
        }
    } else {
        $message->setMessage("Informações inválidas! Filme não encontrado.", "error", "index.php");
    }
} else {
    $message->setMessage("Ação inválida!", "error", "index.php");
}