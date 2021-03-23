<?php
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$idTrace = ( empty($this->request['idTrace'])) ? "" : $this->request['idTrace'];
$dateHeure = ( empty($this->request['dateHeure'])) ? "" : $this->request['dateHeure'];
$latitude = ( empty($this->request['latitude'])) ? "" : $this->request['latitude'];
$longitude = ( empty($this->request['longitude'])) ? "" : $this->request['longitude'];
$altitude = ( empty($this->request['altitude'])) ? "" : $this->request['altitude'];
$rythmeCardio = ( empty($this->request['rythmeCardio'])) ? "" : $this->request['rythmeCardio'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

$id = 0;

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{ $msg = "Erreur : méthode HTTP incorrecte.";
$code_reponse = 406;
}
else {
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == "" || $idTrace == "" || $dateHeure == "" || $latitude == "" || $longitude == "" || $altitude == "" || $rythmeCardio == "" )
    { $msg = "Erreur : données incomplètes.";
      $code_reponse = 400;
    }
    else
    { 
        if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) 
        {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
        else
        {  
            $idutilisateur = $dao->getUnUtilisateur($pseudo)->getId();
            $uneTrace = $dao->getUneTrace($idTrace);
            if ($uneTrace == null) 
            {  
                $msg = "Le numéro de trace n'existe pas.";
                $code_reponse = 401;
            }
            elseif ( $uneTrace->getIdUtilisateur() != $idutilisateur )
            {
                $msg = "Le numéro de trace ne correspond pas à cet utilisateur.";
                $code_reponse = 401;
            }
            elseif ($uneTrace->getTerminee() == true)
            {
                $msg = "La trace est déjà terminée";
                $code_reponse = 401;
            }
            else
            {
                $id = $uneTrace->getNombrePoints() + 1 ;
                $unPointDeTrace = new PointDeTrace($idTrace, $id, $latitude, $longitude, $altitude, $dateHeure, $rythmeCardio, 0, 0, 0);
            
                $ok = $dao->creerUnPointDeTrace($unPointDeTrace);
                if($ok == true)
                {
                    $msg = "Point créé.";
                    $code_reponse = 200;
                }
                else
                {
                    $msg = "Erreur : problème lors de l'enregistrement du point";
                    $code_reponse = 401;
                }
            }
        }
    }
}
// ferme la connexion à MySQL :
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg, $id);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg, $id);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg,$id)
{
    // crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'UTF-8';
    
    // crée un commentaire et l'encode en UTF-8
    $elt_commentaire = $doc->createComment('Service web EnvoyerPosition - BTS SIO - Lycée De La Salle - Rennes');
    // place ce commentaire à la racine du document XML
    $doc->appendChild($elt_commentaire);
    
    // crée l'élément 'data' à la racine du document XML
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    // place l'élément 'reponse' dans l'élément 'data'
    $elt_reponse = $doc->createElement('reponse', $msg);
    $elt_data->appendChild($elt_reponse);
    
    // traitement des utilisateurs
    // place l'élément 'donnees' dans l'élément 'data'
    $elt_donnees = $doc->createElement('donnees');
    $elt_data->appendChild($elt_donnees);
    
    $elt_id = $doc->createElement('id',$id);
    $elt_donnees->appendChild($elt_id);
    
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg,$id)
{
    
    if ($id == 0) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        
        $elt_id = ["id" => $id];
        
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg, "donnees" => $elt_id];
    }
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================
?>