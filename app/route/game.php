<?php

$app->get('/game', function() use ($app){
    

    $app->render('game.twig', array(
        'config'    => $app->config
    ));
})->name('game');