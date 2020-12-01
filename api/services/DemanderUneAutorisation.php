<?php
// •	pseudo : le pseudo de l'utilisateur qui demande l'autorisation
// •	mdp : le mot de passe hashé en sha1 de l'utilisateur qui demande l'autorisation
// •	pseudoDestinataire : le pseudo de l'utilisateur à qui on demande l'autorisation
// •	texteMessage : le texte d'un message accompagnant la demande
// •	nomPrenom : le nom et le prénom du demandeur
// •	lang : le langage utilisé pour le flux de données ("xml" ou "json")

// ces variables globales sont définies dans le fichier modele/parametres.php
global $ADR_MAIL_EMETTEUR, $ADR_SERVICE_WEB;

// connexion du serveur web à la base MySQL
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$pseudoDestinataire = ( empty($this->request['pseudoDestinataire'])) ? "" : $this->request['pseudoDestinataire'];
$texteMessage = ( empty($this->request['texteMessage'])) ? "" : $this->request['texteMessage'];
$nomPrenom = ( empty($this->request['nomPrenom'])) ? "" : $this->request['nomPrenom'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    // Les paramètres doivent être présents et corrects
    if ( $pseudo == "" || $mdpSha1 == "" || $pseudoDestinataire == "" || $texteMessage == "" || $nomPrenom == "")
    {	$message = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {
    	if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) {
    	    $message = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
    	}
    	
    	else
    	{
    	    if (!$dao->existePseudoUtilisateur($pseudo))
    	    {  
    	        $message="Erreur : pseudo inexistant.";
    	       $code_reponse = 500;
    	    }
    	    
    	    else
    	    {
    	        $unDestinataire = $dao->getUnUtilisateur($pseudoDestinataire);
    	        if ($unDestinataire == null)
    	        {
    	            $message="Erreur : pseudo utilisateur inexistant.";
    	            $code_reponse=500;
    	        }
    	        else 
    	        {
    	            
    	            $mdpDesti = $unDestinataire->getMdpSha1();
    	            
        	        $unUtilisateur = $dao->getUnUtilisateur($pseudo);
        	        $msgMail ="Cher ou chère ".$pseudoDestinataire."\n\n"."Un utilisateur du système TraceGPS vous demande l'autorisation de suivre vos parcours"."\n\n"."Voici les données";
        	        $msgMail .= " le concernant :"."\n\n"."Son Pseudo : ".$pseudo."\n\n"."Son adresse mail : ".$unUtilisateur->getAdrMail()."\n\n";
        	        $msgMail .= "Son numéro de téléphone : ".$unUtilisateur->getNumTel()."\n\n";
        	        $msgMail .= "Son Nom et Prenom : ".$nomPrenom."\n\n"."Son message : ".$texteMessage."\n\n";
        	        $msgMail .= "Pour accepter la demande, cliquez sur ce lien : ";
        	        $msgMail .= "http://localhost/ws-php-romain2/tracegps/api/ValiderDemandeAutorisation?a=".$mdpDesti."&b=".$pseudoDestinataire."&c=".$pseudo."&d=1"."\n\n";
        	        $msgMail .= "Pour rejeter la demande, cliquez sur ce lien : ";
        	        $msgMail .= "http://localhost/ws-php-romain2/tracegps/api/ValiderDemandeAutorisation?a=".$mdpDesti."&b=".$pseudoDestinataire."&c=".$pseudo."&d=0";
    	        
    	        
    	        
    	        
    	            $adresseDestinataire = $unDestinataire->getAdrMail();
        	        $sujet = "Demande d'autorisation de la part d'un utilisateur du système TraceGps";
        	        $adresseEmetteur = $unUtilisateur->getAdrMail();
        	        $ok = Outils::envoyerMail($adresseDestinataire, $sujet, $msgMail, $adresseEmetteur);
        	        
        	        if(! $ok)
        	        {
        	            $message="Erreur : l'envoi du courriel de demande d'autorisation a rencontré un problème.";
        	            $code_reponse=500;
        	        }
        	        else 
        	        {
        	            $message= $pseudoDestinataire." va recevoir un courriel avec votre demande.";
        	            $code_reponse=200;
        	        }
    	       }
    	    }
    	}
    }
}
unset($dao);   // ferme la connexion à MySQL
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Validation TraceGPS</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>
	<p><?php echo $message; ?></p>
	<p><a href="Javascript:window.close();">Fermer</a></p>
</body>
</html>