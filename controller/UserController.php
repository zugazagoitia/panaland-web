<?php
require_once "controller/Controller.php";

class UserController implements Controller
{

    function procesar($GET, $POST, Core $core): string
    {
        $user = $_GET["user"] ?? "";
        $params = ["user" => $user];

        $params['usuario'] = Database::getInstance()->getPlayerByName($user);

        return $core->render('usuario.twig', $params, "usuario");
    }
}
