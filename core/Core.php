<?php

include_once "Database.php";
require_once "model/ResumePlayer.php";


class Core
{

    private /*Twig\Environment*/
        $twig;
    private /*Database*/
        $database;

    function __construct($twig)
    {
        $this->twig = $twig;

        if (!isset($_SESSION)) {
            session_start();
        }
    }


    // Funcion para renderizar un template de twig siempre enviando el usuario que esté iniciado sesión, si existe
    public function render($template, $parameters = [], $currentPage = ""): string
    {
        /*$loggedUserId = $this->get("loggedUserId");
        $loggedUser = Database::getInstance()->getUsuarioById($loggedUserId);

        $parameters["loggedUser"] = $loggedUser;
        $parameters["currentPage"] = $currentPage;*/
        $parameters["playerList"] = Database::getInstance()->getPlayerList();

        return $this->twig->render($template, $parameters);
    }
}

?>