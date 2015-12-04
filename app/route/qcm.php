<?php

use FrametekLight\Validator\InputValidator;
use FrametekLight\Errors\ErrorHandler;

$app->get('/qcm', function() use ($app){
    
    $groupes = $app->db->query("
        SELECT
        *
        FROM groupe_sanguin
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    
    $app->render('qcm.twig', [
        'groupes'   => $groupes,
        'config'    => $app->config
    ]);
    
})->name('qcm.get');



$app->post('/qcm', function() use ($app){
    
    $validator = new InputValidator( new ErrorHandler() );
    
    $validator->check(
        $app->request->post(),
        [
            'user_mail'  => [
                'preserve'  => true,
                'required'  => true,
                'minlength' => 5,
                'email'     => true
            ],
            'user_nom'  => [
                'preserve'  => true,
                'required'  => true,
                'minlength' => 2
            ],
            'user_prenom'  => [
                'preserve'  => true,
                'required'  => true,
                'minlength' => 2
            ],
            'user_telephone'  => [
                'numeric' => true,
                'required'  => true,
                'minlength' => 10
            ]
        ]
    );
    
    // Nom aliment
    $aliment_nom_alim = array();
    if( $app->request->post( 'aliment_nom_alim' ) !== null )
    {
        $aliment_nom_alim = $app->request->post( 'aliment_nom_alim' );
    }
    foreach( $aliment_nom_alim as $aliment_nom_alim_id  => $value )
    {
        $validator->check(
            $aliment_nom_alim,
            [
                $aliment_nom_alim_id  => [
                    'required'  => true,
                    'minlength' => 2
                ],
            ]
        );
    }
    
    // Quantité aliment
    $aliment_qte = array();
    if( $app->request->post( 'aliment_qte' ) !== null )
    {
        $aliment_qte = $app->request->post( 'aliment_qte' );
    }
    foreach( $aliment_qte as $aliment_qte_id  => $value )
    {
        $validator->check(
            $aliment_qte,
            [
                $aliment_qte_id  => [
                    'required'  => true,
                    'numeric' => true
                ],
            ]
        );
    }
    /*
    if( $validator->fails() )
    {
        $errors = $validator->errorHandler()->all();
        $app->response->redirect(
            $app->urlFor('qcm.get', array(
                'errors' => $errors,
            ))
        );
        //Redirection vers le formulaire avec le tableau de message d'erreurs
    }*/
    
    
    $post_user = $app->db->prepare("
        INSERT INTO user(mail, nom, prenom, telephone, id_grp_sanguin)
        VALUES(:mail, :nom, :prenom, :telephone, :id_grp_sanguin);       
    ");
    
    $post_user->execute(array(
        "mail" => $app->request->post('user_mail'),
        "nom" => $app->request->post('user_nom'),
        "prenom" => $app->request->post('user_prenom'),
        "telephone" => $app->request->post('user_telephone'),
        "id_grp_sanguin" => $app->request->post('user_grp_sanguin')
    ));
    
    $user_id = $app->db->query("
        SELECT MAX(id) FROM user 
    ")->fetch()[0];
    
    if( !empty($app->request->post('aliment_qte')[0]) ){
        //var_dump($app->request->post('aliment_qte'));exit;
        for($i = 0, $c = count($app->request->post('aliment_qte')); $i<$c; $i++){
            $post_aliment = $app->db->prepare("
                INSERT INTO aliment(nom, qte, id_user)
                VALUES(:nom$i, :qte$i, :id_user)
            ");
            
            $post_aliment->execute(array(
                "nom$i" => $app->request->post('aliment_nom_alim')[$i],
                "qte$i" => $app->request->post('aliment_qte')[$i],
                "id_user" => $user_id
            ));
        }
    }
    
    if( !empty($app->request->post('epi_qte')[0]) ){
        //var_dump($app->request->post('epi_qte'));exit;
        for($i = 0, $c = count($app->request->post('epi_qte')); $i<$c; $i++){
            $post_epi = $app->db->prepare("
                INSERT INTO epi(nom, qte, id_user)
                VALUES(:nom$i, :qte$i, :id_user)
            ");
            
            $post_epi->execute(array(
                "nom$i" => $app->request->post('epi_nom')[$i],
                "qte$i" => $app->request->post('epi_qte')[$i],
                "id_user" => $user_id
            ));
        }
    }
    
    if( !empty($app->request->post('soins_type')[0]) ){
        //var_dump($app->request->post('soins_type'));exit;
        for($i = 0, $c = count($app->request->post('soins_type')); $i<$c; $i++){
            $post_soins = $app->db->prepare("
                INSERT INTO soins(type, id_user)
                VALUES(:type$i, :id_user)
            ");
            
            
            $post_soins->execute(array(
                "type$i" => $app->request->post('soins_type')[$i],
                "id_user" => $user_id
            ));
        }
    }
    
    if( !empty($app->request->post('abri_adresse')[0]) ){
        for($i = 0, $c = count($app->request->post('abri_adresse')); $i<$c; $i++){
            $post_abri = $app->db->prepare("
                INSERT INTO abri(adresse, nb_places, id_user)
                VALUES(:adresse$i, :nb_places$i, :id_user)
            ");
            
            $post_abri->execute(array(
                "adresse$i" => $app->request->post('abri_adresse')[$i],
                "nb_places$i" => $app->request->post('abri_nb_place')[$i],
                "id_user" => $user_id
            ));
        }
    }
    
    
    
})->name('qcm.post');