<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\Intl\IntlExtension;
use Twig\TwigFilter;

$ROOT_PATH = dirname(__DIR__);
require_once $ROOT_PATH . '/vendor/autoload.php';

$loader = new FilesystemLoader('view');
$twig = new Environment($loader,
    [
        'cache' => false,
        'debug' => true
    ]
);

$twig->addExtension(new IntlExtension());

$twig->addFilter(new TwigFilter('secondsToTime', function ($ss) {
    $s = $ss % 60;
    $m = floor(($ss % 3600) / 60);
    $h = floor(($ss % 86400) / 3600);
    $d = floor(($ss % 2592000) / 86400);
    $M = floor($ss / 2592000);

    $text = "";
    if ($M > 0) {
        if ($M == 1) {
            $text .= $M . "mes ";
        } else {
            $text .= $M . "meses ";
        }
    }
    if ($d > 0) {
        if ($d == 1) {
            $text .= $d . "día ";
        } else {
            $text .= $d . "días ";
        }
    }
    if ($h > 0) {
        $text .= $h . "h ";
    }
    if ($m > 0) {
        $text .= $m . "m ";
    }
    /*
    if ($s > 0) {
        $text .= $s . "seg";
    }
    */
    return $text;
}));


?>
