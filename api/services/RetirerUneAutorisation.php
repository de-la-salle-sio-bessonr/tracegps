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
$pseudoAretirer = ( empty($this->request['pseudoARetirer'])) ? "" : $this->request['pseudoARetirer'];
$texteMessage = ( empty($this->request['texteMessage'])) ? "" : $this->request['texteMessage'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    // Les paramètres doivent être présents et corrects
    if ( $pseudo == "" || $mdpSha1 == "" || $pseudoAretirer == "" || $texteMessage == "")
    {	
        $message = "Erreur : données incomplètes.";
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
            if ( ! $dao->existePseudoUtilisateur($pseudo))
            {
                $message="Erreur : pseudo inexistant.";
                $code_reponse = 500;
            }
            
            else
            {
                $unDestinataire = $dao->getUnUtilisateur($pseudoAretirer);
                $unUtilisateur = $dao->getUnUtilisateur($pseudo);
                if ($unDestinataire == null)
                {
                    $message="Erreur : pseudo utilisateur inexistant.";
                    $code_reponse=500;
                }
                else
                {
                    $idAutorisant = $dao->getUnUtilisateur($pseudo)->getId();
                    $idAutorise = $dao->getUnUtilisateur($pseudoAretirer)->getId();
                    $ok = $dao->autoriseAConsulter($idAutorisant, $idAutorise);
                    
                    if ( ! $ok)
                    {
                        $message = "Erreur : l'autorisation n'était pas accordée.";
                        $code_reponse= 500;
                    }
                    else 
                    {
                        $ok = $dao->supprimerUneAutorisation($idAutorisant, $idAutorise);
                        if ( ! $ok)
                        {
                            $message = "Erreur : problème lors de la suppression de l'autorisation.";
                            $code_reponse = 500;
                        }
                        
                        $ADR_MAIL_EMETTEUR = $unUtilisateur->getAdrMail();
                        $adrMailDemandeur = $unDestinataire->getAdrMail();
                        
                        $sujetMail = "Supression d'autorisation de la part d'un utilisateur du système TraceGPS";
                        $contenuMail = "Cher ou chère " . $pseudoAretirer . "\n\n";
                        $contenuMail .= "L'utilisateur ".$pseudo." du système TraceGPS vous retire l'autorisation de suivre ses parcours"."\n\n";
                        $contenuMail .= "Son message : ".$texteMessage."\n\n";
                        $contenuMail .= "Cordialement.\n";
                        $contenuMail .= "L'administrateur du système TraceGPS";
                        $ok = Outils::envoyerMail($adrMailDemandeur, $sujetMail, $contenuMail, $ADR_MAIL_EMETTEUR);
                        if ( ! $ok ) {
                            $message = "Erreur : l'envoi du courriel au demandeur a rencontré un problème.";
                            $code_reponse = 500;
                        }
                        else {
                            $message = "Autorisation supprimée ; ".$pseudoAretirer." va recevoir un courriel de notification.";
                            $code_reponse = 200;
                        }
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