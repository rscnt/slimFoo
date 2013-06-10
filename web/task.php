<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$proyecto = $app['controllers_factory'];


$proyecto->get('/', function() use ($app) {

    $sql    = 'SELECT t.id, t.nombre, t.descripcion, t.urgencia, p.id as pid, p.nombre as pnombre FROM tarea t JOIN proyecto p ON p.id = t.proyecto_id';
    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    return $app['twig']->render('task_index.html', array(
        'title' => "Tasks administracion",
        'items' => $registros,
    ));

});

$proyecto->post('/', function(Request $req) use ($app) {

    $sql = "INSERT INTO tarea (nombre, descripcion, usuario_creador_id, fecha_creacion, fecha_limite, proyecto_id, urgencia) 
        VALUES (:nombre, :descripcion, 1, 05/25/2013, 05/25/2013, :proId, :urgencia)";

    $stmt = $app['db']->prepare($sql);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);
    $stmt->bindValue(':urgencia', $req->get('urgencia'), SQLITE3_INTEGER);
    $stmt->bindValue(':proId', $req->get('pro-parent'), SQLITE3_INTEGER);
    $result = $stmt->execute();

    $sql = "SELECT * FROM tarea WHERE id = 
        ( SELECT last_insert_rowid() )";

    $stmtS = $app['db']->prepare($sql);
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

$proyecto->put('/{id}', function($id, Request $req) use ($app) {
    
    $sql = 
        "UPDATE tarea SET nombre = :nombre, descripcion= :descripcion 
        WHERE id = :id"; 

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);

    $result = $stmt->execute();

    $sql = "SELECT * FROM tarea WHERE id = :id";

    $stmtS = $app['db']->prepare($sql);
    $stmtS->bindValue(':id', $req->get('proid'), SQLITE3_INTEGER);
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

$proyecto->delete('/d/{id}', function($id, Request $req) use ($app) {
    $sql = 
        "DELETE FROM tarea WHERE id = :id";

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    return new Response('eliminado', 200);
});

return $proyecto;
?>
