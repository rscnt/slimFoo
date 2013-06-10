<?php

use Symfony\Component\HttpFoundation\Response;

$api = $app['controllers_factory'];

$api->get('/{tabla}', function($tabla) use ($app) {

    $sql    = 'SELECT id, nombre as text FROM '.$tabla;

    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    $json_a = json_encode($registros);
    $response = new Response();
    $response->headers->set('Content-type', 'application/json');
    $response->setContent($json_a);
    return $response;
});


$api->get('/{tabla}/{id}', function($tabla, $id) use ($app) {

    $sql       = 
        'SELECT *  FROM '.$tabla.' WHERE id = :id';
    $registros = array();
    $indice    = 0;

    $stmt      = $app['db']->prepare($sql);

    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    $json_a = json_encode($registros);
    $response = new Response();
    $response->headers->set('Content-type', 'application/json');
    $response->setContent($json_a);
    return $response;
});


return $api;
?>
