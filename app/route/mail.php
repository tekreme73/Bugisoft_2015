<?php

$app->get('/mail/:userId', function($userId) use ($app){
    
    $user = $app->db->prepare("
       SELECT
       user.*
       FROM user
       WHERE user.id = :userId
    ");
    
    
    $user->execute(['userId' => $userId]);
    $user = $user->fetch(PDO::FETCH_ASSOC);
    
        var_dump($user);

    $message = Swift_Message::newInstance('Mise à jour de vos données')
        ->setFrom(array('bugisoft@gmail.com' => 'Bugisoft'))
        ->setTo(array($user['mail'] => $user['prenom'].' '.$user['nom']))
        ->setBody('Vos données ont atteint les un ans. Il serait temps de les mettre à jour ! Vous pouvez suivre ce lien pour plus d\'informations.')
    ;

    // Send the message
    $result = $app->mailer->send($message);
    
    
})->name('mail');