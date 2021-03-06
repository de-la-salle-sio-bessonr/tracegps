<?php
// Projet TraceGPS
// fichier : modele/DAO.test1.php
// Rôle : test de la classe DAO.class.php
// Dernière mise à jour : xxxxxxxxxxxxxxxxx par xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

// Le code des tests restant à développer va être réparti entre les membres de l'équipe de développement.
// Afin de limiter les conflits avec GitHub, il est décidé d'attribuer un fichier de test à chaque développeur.
// Développeur 1 : fichier DAO.test1.php
// Développeur 2 : fichier DAO.test2.php
// Développeur 3 : fichier DAO.test3.php
// Développeur 4 : fichier DAO.test4.php

// Quelques conseils pour le travail collaboratif :
// avant d'attaquer un cycle de développement (début de séance, nouvelle méthode, ...), faites un Pull pour récupérer
// la dernière version du fichier.
// Après avoir testé et validé une méthode, faites un commit et un push pour transmettre cette version aux autres développeurs.
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de la classe DAO</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
// connexion du serveur web à la base MySQL
include_once ('DAO.class.php');
$dao = new DAO();


// // test de la méthode getLesUtilisateursAutorisant ------------------------------------------------
// // modifié par Jim le 13/8/2018
// echo "<h3>Test de getLesUtilisateursAutorisant(idUtilisateur) : </h3>";
// $lesUtilisateurs = $dao->getLesUtilisateursAutorisant(4);
// $nbReponses = sizeof($lesUtilisateurs);
// echo "<p>Nombre d'utilisateurs autorisant l'utilisateur 4 à voir leurs parcours : " . $nbReponses . "</p>";
// // affichage des utilisateurs
// foreach ($lesUtilisateurs as $unUtilisateur)
// {   echo ($unUtilisateur->toString());
// echo ('<br>');
// }



// // test de la méthode getLesUtilisateursAutorises -------------------------------------------------
// // modifié par Jim le 13/8/2018
// echo "<h3>Test de getLesUtilisateursAutorises(idUtilisateur) : </h3>";
// $lesUtilisateurs = $dao->getLesUtilisateursAutorises(2);
// $nbReponses = sizeof($lesUtilisateurs);
// echo "<p>Nombre d'utilisateurs autorisés par l'utilisateur 2 : " . $nbReponses . "</p>";
// // affichage des utilisateurs
// foreach ($lesUtilisateurs as $unUtilisateur)
// {	echo ($unUtilisateur->toString());
//     echo ('<br>');
// }




// // test de la méthode autoriseAConsulter ----------------------------------------------------------
// // modifié par Jim le 13/8/2018
// echo "<h3>Test de autoriseAConsulter : </h3>";
// if ($dao->autoriseAConsulter(2, 3)) $autorise = "oui"; else $autorise = "non";
// echo "<p>L'utilisateur 2 autorise l'utilisateur 3 : <b>" . $autorise . "</b><br>";

// if ($dao->autoriseAConsulter(3, 2)) $autorise = "oui"; else $autorise = "non";
// echo "<p>L'utilisateur 3 autorise l'utilisateur 2 : <b>" . $autorise . "</b><br>";

// // test de la méthode creerUneAutorisation ---------------------------------------------------------
// // modifié par Jim le 13/8/2018
// echo "<h3>Test de creerUneAutorisation : </h3>";
// if ($dao->creerUneAutorisation(2, 1)) $ok = "oui"; else $ok = "non";
// echo "<p>La création de l'autorisation de l'utilisateur 2 vers l'utilisateur 1 a réussi : <b>" . $ok . "</b><br>";
// // la même autorisation ne peut pas être enregistrée 2 fois
// if ($dao->creerUneAutorisation(2, 1)) $ok = "oui"; else $ok = "non";
// echo "<p>La création de l'autorisation de l'utilisateur 2 vers l'utilisateur 1 a réussi : <b>" . $ok . "</b><br>";

// // test de la méthode supprimerUneAutorisation ----------------------------------------------------
// // modifié par Jim le 13/8/2018
// echo "<h3>Test de supprimerUneAutorisation : </h3>";
// // on crée une autorisation
// if ($dao->creerUneAutorisation(2, 1)) $ok = "oui"; else $ok = "non";
// echo "<p>La création de l'autorisation de l'utilisateur 2 vers l'utilisateur 1 a réussi : <b>" . $ok . "</b><br>";
// // puis on la supprime
// if ($dao->supprimerUneAutorisation(2, 1)) $ok = "oui"; else $ok = "non";
// echo "<p>La suppression de l'autorisation de l'utilisateur 2 vers l'utilisateur 1 a réussi : <b>" . $ok . "</b><br>";

// // test de la méthode existeAdrMailUtilisateur ----------------------------------------------------
// // modifié par Jim le 12/8/2018
// echo "<h3>Test de existeAdrMailUtilisateur : </h3>";
// if ($dao->existeAdrMailUtilisateur("admin@gmail.com")) $existe = "oui"; else $existe = "non";
// echo "<p>Existence de l'utilisateur 'admin@gmail.com' : <b>" . $existe . "</b><br>";
// if ($dao->existeAdrMailUtilisateur("delasalle.sio.besson.r@gmail.com")) $existe = "oui"; else $existe = "non";
// echo "Existence de l'utilisateur 'delasalle.sio.besson.r@gmail.com' : <b>" . $existe . "</b></br>";
// // ferme la connexion à MySQL :
// unset($dao);

// // test de la méthode creerUneTrace ----------------------------------------------------------
// // modifié par Jim le 14/8/2018
// echo "<h3>Test de creerUneTrace : </h3>";
// $trace1 = new Trace(0, "2017-12-18 14:00:00", "2017-12-18 14:10:00", true, 3);
// $ok = $dao->creerUneTrace($trace1);
// if ($ok) {
//     echo "<p>Trace bien enregistrée !</p>";
//     echo $trace1->toString();
// }
// else {
//     echo "<p>Echec lors de l'enregistrement de la trace !</p>";
// }
// $trace2 = new Trace(0, date('Y-m-d H:i:s', time()), null, false, 3);
// $ok = $dao->creerUneTrace($trace2);
// if ($ok) {
//     echo "<p>Trace bien enregistrée !</p>";
//     echo $trace2->toString();
// }
// else {
//     echo "<p>Echec lors de l'enregistrement de la trace !</p>";
// }


// // test de la méthode supprimerUneTrace -----------------------------------------------------------
// // modifié par Jim le 15/8/2018
// echo "<h3>Test de supprimerUneTrace : </h3>";
// $ok = $dao->supprimerUneTrace(22);
// if ($ok) {
//     echo "<p>Trace bien supprimée !</p>";
// }
// else {
//     echo "<p>Echec lors de la suppression de la trace !</p>";
// }



// test des méthodes creerUnPointDeTrace et terminerUneTrace --------------------------------------
// modifié par Jim le 15/8/2018
echo "<h3>Test de terminerUneTrace : </h3>";
// on choisit une trace non terminée
$unIdTrace = 3;
// on l'affiche
$laTrace = $dao->getUneTrace($unIdTrace);
echo "<h4>l'objet laTrace avant l'appel de la méthode terminerUneTrace : </h4>";
echo ($laTrace->toString());
echo ('<br>');
// on la termine
$dao->terminerUneTrace($unIdTrace);
// et on l'affiche à nouveau
$laTrace = $dao->getUneTrace($unIdTrace);
echo "<h4>l'objet laTrace après l'appel de la méthode terminerUneTrace : </h4>";
echo ($laTrace->toString());
echo ('<br>');




?>

</body>
</html>