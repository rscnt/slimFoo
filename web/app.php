<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Dirty and stupid way...
$bd_dir = __DIR__.'/res/db/base.db';
$bd     = new SQLite3($bd_dir);
$app['db'] = $bd;

$app->getJSON = function ($url) {
    $HOST =  $_SERVER['HTTP_HOST'];
    $json_url = "http://".$HOST.$url;
    echo $json_url;
    $timeout = 10;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $json_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $content = curl_exec($ch);
    curl_close($ch);
    return json_decode($content);
};

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));


$app->mount('/login', include 'login.php');

$app->mount('/dashboard/', include 'descripcion.php');
$app->mount('/api', include 'api.php');
$app->mount('/admin/proyecto', include 'proyecto.php');
$app->mount('/admin/task', include 'task.php');
$app->mount('/admin/documentos', include 'documentos.php');

return $app;
?>
