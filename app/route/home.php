<?php

$app->get('/', function() use ($app){
    $app->render('home.twig', array(
        'config' => $app->config,
    ));
})->name('home');