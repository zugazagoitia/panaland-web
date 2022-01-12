<?php
require_once "controller/Controller.php";

class Portada implements Controller
{

    function procesar($GET, $POST, Core $core): string
    {
        $params = array();
        $params['players'] = Database::getInstance()->getAllPlayers();
        $params['dailyStats'] = Database::getInstance()->getDailyStats();

        return $core->render('portada.twig', $params, "index");
    }
}
