<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$proyecto = $app['controllers_factory'];


$proyecto->get('/', function() use ($app) {
    return $app['twig']->render('login.html', array(
        'title' => "Ingresar"
    ));

});

$proyecto->post('/', function(Request $req) use ($app) {

    $sql = "SELECT * FROM usuario WHERE passwd = :passwd AND nombre = :nombre";

    $stmt = $app['db']->prepare($sql);
    $stmt->bindValue(':nombre', $req->get('nombre'), SQLITE3_TEXT);
    $stmt->bindValue(':passwd', $req->get('passwd'), SQLITE3_TEXT);
    $result = $stmt->execute();

    $registros = $result->fetchArray();

    if(count($registros) == 8) {
        session_start();
        $_SESSION['usr'] = $registros["nombre"];
        $_SESSION['nivel'] = $registros["nivel"];
    }

    return $app->redirect('/dashboard');
});

return $proyecto;
?>
