<?php

/**
 * Interface Controller
 * Se debe implemenetar un metodo procesar, que recibe $_GET y $_POST como parametros, además de una instancia a la clase manejadora de Database y Sesiones
 */
interface Controller
{
    function procesar($GET, $POST, Core $core): string;
}