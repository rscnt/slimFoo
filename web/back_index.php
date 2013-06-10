<?php

require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();


$app->get('/xapi/{tabla}', function($tabla) {
    $bd_dir = __DIR__.'/res/db/base.db';
    $bd     = new SQLite3($bd_dir);
    $sql    = 'SELECT * FROM '.$tabla;

    $result = $bd->query($sql);

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


$app->get('/xapi/{tabla}/{id}', function($tabla, $id) use ($app) {
    $bd_dir    = __DIR__.'/res/db/base.db';
    $bd        = new SQLite3($bd_dir);
    $sql       = 
        'SELECT *  FROM '.$tabla.' WHERE id = :id';
    $registros = array();
    $indice    = 0;

    $stmt      = $bd->prepare($sql);

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

$app->run();
