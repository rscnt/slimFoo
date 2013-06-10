<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$resultado = $app['controllers_factory'];


$resultado->get('/', function() use ($app) {

    $sql    = 'SELECT * FROM proyecto';
    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    return $app['twig']->render('descripcion.html', array(
        'title' => "/",
        'proyectos' => $registros
    ));

});

$resultado->get('/{id}', function($id) use ($app) {

    $sql    = 'SELECT * FROM tarea  WHERE proyecto_id = '.$id;
    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }


    $sql    = 'SELECT * FROM documentos  WHERE proyecto_id = '.$id;
    $result = $app['db']->query($sql);

    $registrosd = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registrosd[] = $r;
        $indice++;
    }

    // $sql    = 'SELECT * FROM proyecto WHERE id = '.$id;
    // $result = $app['db']->query($sql);

    // $registrosp = array();
    // $indice = 0;
    // while($r = $result->fetchArray(SQLITE3_ASSOC)) {
    //     $registrosp[] = $r;
    //     $indice++;
    // }

    return $app['twig']->render('descripcion_proyecto.html', array(
        'title' => "Modificando proyecto",
        'tareas' => $registros,
        'documentos' => $registrosd,
        'proid' => $id
    ));

});

$resultado->post('/{id}/t/{tid}', function($id, $tid, Request $req) use ($app) {

    $sql    = 'UPDATE tarea SET completado = :completado  WHERE id = :id';
    $stmt = $app['db']->prepare($sql);

    if($req->get("completado") == 0) {
        $stmt->bindValue(':completado', 1, SQLITE3_INTEGER);
    } else {
        $stmt->bindValue(':completado', 0, SQLITE3_INTEGER);
    }
    $stmt->bindValue(':id', $tid, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $sql = "SELECT * FROM tarea WHERE id = :id";

    $stmtS = $app['db']->prepare($sql);
    $stmtS->bindValue(':id', $tid, SQLITE3_INTEGER);
    $response_select = $stmtS->execute();

    $registros = array();
    $indice = 0;
    while($r = $response_select->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    $json_response = json_encode($registros);

    return new Response($json_response, 200);
});

return $resultado;
?>
