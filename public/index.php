<?php
use Silex\Application;
use Silex\Provider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('DS', DIRECTORY_SEPARATOR);
define('DOCROOT', __DIR__ . DS);
define('APPROOT', realpath(__DIR__ .'/../app') . DS);
require_once APPROOT . 'bootstrap.php';

# application configuration
$app = new Application();

$app['debug'] = true;
# Provider Registration
$app->register(new Provider\SessionServiceProvider(), []);
$app->register(new Provider\TwigServiceProvider(), [
    'twig.path' => APPROOT .'views',
    'twig.options' => [
        'cache' => APPROOT .'cache',
        'strict_varialbes' => true,
        'debug' => true,
        'autoescape' => true,
        ],
]);
$app->register(new Provider\DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'ham',
        'host' => 'localhost',
        'user' => 'ham',
        'password' => 'ham-manager',
        'charset' => 'utf8',
    ],
]);

$app->get('/', function () use ($app) {
    return 'Index page';
});
$app->get('/receipt/new', function () use ($app) {
    $app['session']->start();
    $viewdata = [
        'title' => 'New Receipt',
        'headline' => 'レシート追加',
    ];
    return $app['twig']->render('receipt/new.twig', $viewdata);
});
$app->put('/receipt/new', function () use ($app) {
});
$app->get('/template', function () use ($app) {
});

$app->run();
