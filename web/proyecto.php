<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$proyecto = $app['controllers_factory'];


$proyecto->get('/', function() use ($app) {


    $sql    = 'SELECT * FROM proyecto';
    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    return $app['twig']->render('proyecto_index.html', array(
        'title' => "Adminitración Proyectos",
        'items' => $registros,
    ));

    return $app->redirect('/login');


});

$proyecto->post('/', function(Request $req) use ($app) {

    $sql = "INSERT INTO proyecto 
        (nombre, descripcion)
        VALUES (:nombre, :descripcion)";

    $stmt = $app['db']->prepare($sql);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);
    $result = $stmt->execute();

    $sql = "SELECT * FROM proyecto WHERE id = 
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
        "UPDATE proyecto SET nombre = :nombre, descripcion= :descripcion 
        WHERE id = :id"; 

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $req->get('proid'), SQLITE3_INTEGER);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);

    $result = $stmt->execute();

    $sql = "SELECT * FROM proyecto WHERE id = :id";

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
        "DELETE FROM proyecto WHERE id = :id";

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    return new Response('proyecto eliminado', 200);
});

return $proyecto;
?>
