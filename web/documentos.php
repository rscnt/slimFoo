<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$proyecto = $app['controllers_factory'];


$proyecto->get('/', function() use ($app) {

    $sql    = 'SELECT t.id, t.nombre, t.descripcion, t.directorio, p.id as pid, p.nombre as pnombre FROM documentos t JOIN proyecto p ON p.id = t.proyecto_id';
    $result = $app['db']->query($sql);

    $registros = array();
    $indice = 0;
    while($r = $result->fetchArray(SQLITE3_ASSOC)) {
        $registros[] = $r;
        $indice++;
    }

    return $app['twig']->render('documentos.html', array(
        'title' => "Documentos administración",
        'items' => $registros,
    ));

});

$proyecto->post('/', function(Request $req) use ($app) {

    if (!($_FILES["file"]["error"] > 0)) {
        if (file_exists("/assets/uploads/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "/home/_r/devs/php/TercerProyecto/php/silex/web/assets/uploads/" . $_FILES["file"]["name"]);
        }
    }

    $sql = "INSERT INTO documentos (nombre, descripcion, usuario_creador_id, fecha_creacion, fecha_limite, proyecto_id, directorio) 
        VALUES (:nombre, :descripcion, 1, 05/25/2013, 05/25/2013, :proId, :directorio)";

    $stmt = $app['db']->prepare($sql);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);
    $stmt->bindValue(':directorio', "/assets/uploads/".$_FILES["file"]["name"], SQLITE3_TEXT);
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

    return $app->redirect('/documentos');

});

$proyecto->put('/{id}', function($id, Request $req) use ($app) {

    $sql = 
        "UPDATE documentos SET nombre = :nombre, descripcion= :descripcion 
        WHERE id = :id"; 

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':descripcion', $req->get('descripcion'), SQLITE3_TEXT);

    $result = $stmt->execute();

    $sql = "SELECT * FROM documentos WHERE id = :id";

    $stmtS = $app['db']->prepare($sql);
    $stmtS->bindValue(':id', $id, SQLITE3_INTEGER);
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
        "DELETE FROM documentos WHERE id = :id";

    $stmt = $app["db"]->prepare($sql);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    return new Response('eliminado', 200);
});

return $proyecto;
?>
