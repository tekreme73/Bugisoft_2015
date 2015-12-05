<?php

session_start();

// Composer autoloader
require_once '../vendor/autoload.php';

use FrametekLight\Persistent\Config;

//TODO Initialize application

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig(),
));

$app->container->singleton('config', function() use ($app) {
    $config = new Config();
    if( date( 'Y' ) == date( 'Y', $config['app.begin_dev'] ) ) {
	    $year = date( 'Y' );
	} else {
	    $year = date( 'Y', $config['app.begin_dev'] ).'-'.date( 'Y' );
	}
	$config['app.year'] = $year;
    return $config;
});

// SwiftMailer
$app->container->set('mailer', function() {
    $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'ssl')
    ->setUsername('jesus')
    ->setPassword('godisalie');
    $mailer = Swift_Mailer::newInstance($transport);
    
    return $mailer;
});

// Exceptions
$whoops = new \Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

// Database
$app->container->singleton('db', function() use ($app) {
    $database = $app->config['database'];
    $pdo = new \PDO('mysql:host='.$database['host'].';'.'dbname='.$database['dbname'].';', $database['user'], $database['pwd']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec ( 'SET CHARACTER SET utf8' );
    return $pdo;
});

// Middlewares
$app->add(new Bugisoft\Middleware\CsrfMiddleware()); // CSRF

$view = $app->view();
$view->setTemplatesDirectory('../app/views');
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extension_Debug(),
);

require 'routes.php';