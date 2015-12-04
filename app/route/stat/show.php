<?php

$app->get('/stat/show', function() use ($app){

    $soins = $app->db->query("
        SELECT type as \"Type\", COUNT(type) as \"Nombre de personnes disponibles\"
        FROM soins
        GROUP BY type
    ")-> fetchAll(PDO::FETCH_ASSOC);
    
    $soins_view = array();
    foreach( $soins as $data )
    {
        foreach( array_keys($data) as $category )
        {
            $soins_view[ $category ] = array();
        }
        foreach( $data as $key => $value )
        {
            $soins_view[ $key ][] = $value;
        }
    }
    
    $abri = $app->db->query("
        SELECT adresse as \"Localisation de l'abri\", nb_places as \"Nombre de places disponibles\"
        FROM abri
    ")-> fetchAll(PDO::FETCH_ASSOC);
    
    $abri_view = array();
    foreach( $abri as $data )
    {
        foreach( array_keys($data) as $category )
        {
            $abri_view[ $category ] = array();
        }
        foreach( $data as $key => $value )
        {
            $abri_view[ $key ][] = $value;
        }
    }
    
    $aliment = $app->db->query("
        SELECT nom as \"Aliment\", SUM(qte) as \"Quantité disponible\", COUNT(qte) as \"Nombre de personnes concernées\"
        FROM aliment
        GROUP BY nom
    ")-> fetchAll(PDO::FETCH_ASSOC);
    
    $aliment_view = array();
    foreach( $aliment as $data )
    {
        foreach( array_keys($data) as $category )
        {
            $aliment_view[ $category ] = array();
        }
        foreach( $data as $key => $value )
        {
            $aliment_view[ $key ][] = $value;
        }
    }
    
    $epi = $app->db->query("
        SELECT e.nom as \"Nom d'équipement\", SUM(e.qte) as \"Quantité disponible\", COUNT(u.id) AS \"Nombre de personnes concernées\"
        FROM user u
        JOIN epi e ON e.id_user = u.id
        GROUP BY e.nom
    ")-> fetchAll(PDO::FETCH_ASSOC);
    
    $epi_view = array();
    foreach( $epi as $data )
    {
        foreach( array_keys($data) as $category )
        {
            $epi_view[ $category ] = array();
        }
        foreach( $data as $key => $value )
        {
            $epi_view[ $key ][] = $value;
        }
    }
    
    $sang = $app->db->query("
        SELECT g.groupe as \"Groupe sanguin\", COUNT(u.id) AS \"Nombre de personnes concernées\"
        FROM user u
        JOIN groupe_sanguin g ON u.id_grp_sanguin = g.id
        GROUP BY g.groupe
    ")-> fetchAll(PDO::FETCH_ASSOC);

    $sang_view = array();
    foreach( $sang as $data )
    {
        foreach( array_keys($data) as $category )
        {
            $sang_view[ $category ] = array();
        }
        foreach( $data as $key => $value )
        {
            $sang_view[ $key ][] = $value;
        }
    }
    
    $datas = [
        "Groupes sanguins"          => $sang,
        "Equipement Individuel"     => $epi,
        "Type d'aliments"           => $aliment,
        "Hébergements disponibles"  => $abri,
        "Types de soins"            => $soins,
    ];
    /*
    // soins + nbsoins
    SELECT type, COUNT(type) as "count"
    FROM soins
    GROUP BY type

    // abri + adresse + nbplace
    SELECT adresse, nb_places
    FROM abri
    
    // aliment + nbaliment + nbpers
    SELECT nom, SUM(qte) as "qte_totale", COUNT(qte) as "nb personnes proposant aliment"
    FROM aliment
    GROUP BY nom
    
    // gpsg + nbpers
    SELECT g.groupe, COUNT(user.id) AS "nb pers"
    FROM user
    JOIN groupe_sanguin g ON user.id_grp_sanguin = g.id
    GROUP BY groupe
    
    // epi + nbepi + nppers
    SELECT epi.id, epi.nom, COUNT(user.id) AS "nb pers", SUM(epi.qte) as "qte totale"
    FROM user
    JOIN epi ON epi.id_user = user.id
    GROUP BY epi.nom
    
    */
    
    $app->render('stat/show.twig', [
        'config'    => $app->config,
        'datas'      => $datas,
    ]);
    
})->name('stat.show');

