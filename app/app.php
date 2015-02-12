<?php
use Silex\Application;
use Silex\Provider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('DS', DIRECTORY_SEPARATOR);
define('DOCROOT', realpath(__DIR__ .'/../public') . DS);
define('APPROOT', __DIR__ . DS);
require_once APPROOT . 'bootstrap.php';

# application configuration
$app = new Application();

$app['debug'] = true;
# Provider Registration
$app->register(new Provider\SessionServiceProvider(), []);
$app->register(new Provider\UrlGeneratorServiceProvider(), []);
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
        'password' => 'spamspamsham',
        'charset' => 'utf8',
    ],
]);

Request::enableHttpMethodParameterOverride();

$app->get('/', function () use ($app) {
    $viewdata = [
        'title' => 'Home',
        'headline' => 'ホーム',
    ];
    return $app['twig']->render('main.twig', $viewdata);
})->bind('home');

$app->get('/receipt/new', function () use ($app) {
    $app['session']->start();
    $options = [
        'paidfrom' => $app['db']->fetchAll('SELECT `id`, `name` FROM `paidfrom` ORDER BY `id`'),
            'category' => $app['db']->fetchAll('SELECT `id`, `name`, `parent_unique_id` FROM `category` ORDER BY `unique_id`'),
        ];
    $defaults = [
        'purchased_date' => time(),
            'purchased_time' => "00:00",
            'total' => 0,
        ];
    $viewdata = [
        'title' => 'New Receipt',
        'headline' => 'レシート追加',
        'options' => $options,
        'values' => $defaults,
    ];
    return $app['twig']->render('receipt/new.twig', $viewdata);
})->bind('receipt-new');
$app->put('/receipt/new', function (Request $request) use ($app) {
    $app['session']->start();
    $post = $request->request->all();
    return print_r($post, true);
});
$app->get('/template', function () use ($app) {
});

return $app;
