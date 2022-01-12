<?php
require_once "core/Database.php";
require_once "core/Twig.php";
require_once "core/Core.php";

require_once "controller/Portada.php";
require_once "controller/UserController.php";

$core = new Core($twig);

$listaPaginas = [
    "index" => new Portada(),
    "perfil" => new UserController(),
];

$paginaSolicitada = $_GET["pag"] ?? "index"; //por defecto se carga el index


// busco la pagina solicitada en mi array de controllers y layouts
if (array_key_exists($paginaSolicitada, $listaPaginas)) {
    $controller = $listaPaginas[$paginaSolicitada];

    // Se ejecute el procesar
    echo $controller->procesar($_GET, $_POST, $core);

} else {
    // Si no existe esa pagina, se carga el index
    echo $core->render('portada.twig', [], "index");
}

?>
