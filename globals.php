<?php

session_start();
if (isset($_POST["change_theme"])) {
    $_SESSION["theme"] = ($_SESSION["theme"] === "dark") ? "light" : "dark";
}
if (!isset($_SESSION["theme"])) {
    $_SESSION["theme"] = "light"; // Default theme
}
$THEME = $_SESSION["theme"] === "dark" ? "bg-dark text-white" : "bg-light text-dark";

$BASE_URL = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"] . "?") . "/";

?>