<?php
/*
        index.php est le CONTRÔLEUR de notre application de type MVC (modulaire).
        
        Toutes les requêtes de notre application sans aucune exception devront passer par ce fichier.

        Le coeur du contrôleur sera sa structure décisionnelle qui traite un paramètre que l'on va nommer commande.
        C'est la valeur de ce paramètre commande qui déterminera les actions posées par le contrôleur.

        IMPORTANT : LE CONTRÔLEUR NE CONTIENT NI REQUÊTE SQL, NI HTML/CSS/JS, seulement du PHP.

        Le SQL va dans le modèle et strictement dans le modèle. 
        Le HTML va dans les vues et strictement dans les vues.

*/
//réception du paaramètre commande, qui peut arriver en GET ou en POST 
//(et donc nous utiliserons $_REQUEST)

if (isset($_REQUEST["commande"])) {
    $commande = $_REQUEST["commande"];
} else {
    //assigner une commande par défaut -- typiquement la commande qui mène à votre page d'accueil
    $commande = "Accueil";
}

//inclure le modele
require_once("modele.php");
//création du tableau $donnees qui sera utilisé aussi dans les vues
$donnees = array();
//structure décisionnelle du contrôleur
switch ($commande) {
    case "Accueil":
        //afficher la page d'accueil
        //notre page d'accueil est statique, cela se fait donc seulement en
        //incluant le HTML de notre page d'accueil avec entête et pied de page
        $donnees["titre"] = "Accueil";
        require_once("vues/Entete.php");
        require_once("vues/Accueil.html");
        require_once("vues/PiedDePage.php");
        break;
    case "ListeEquipes":
        //afficher la liste des équipes
        //obtenir le modèle dont j'ai besoin (les équipes)
        $ordre = "";
        if (isset($_REQUEST["ordre"])) {
            if (in_array($_REQUEST["ordre"], ["nom", "ville"])) {
                $ordre = $_REQUEST["ordre"];
            } else {
                $donnees["messageErreur"] = "ne modifiez pas les parametres dans l'adresse";
            }
        }
        $donnees["equipes"] = obtenir_equipes($ordre);
        $donnees["titre"] = "Liste des équipes";

        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/ListeEquipes.php");
        require_once("vues/PiedDePage.php");

        break;
    case "ListeTousJoueurs":
        //afficher la liste de tous les joueurs
        //obtenir tous les joueurs dans la table joueurs
        $donnees["joueurs"] = obtenir_tous_joueurs();
        $donnees["titre"] = "Liste de tous les joueurs";

        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/ListeJoueurs.php");
        require_once("vues/PiedDePage.php");
        break;
    case "ListeJoueursParEquipe":
        if (isset($_REQUEST["idEquipe"]) && is_numeric($_REQUEST["idEquipe"])) {
            //obtenir du modèle la liste des joueurs appartenant à l'équipe spécifiée dans le paramètre
            $donnees["joueurs"] = obtenir_joueurs_par_equipe($_REQUEST["idEquipe"]);
            $donnees["equipe"] = obtenir_equipe_par_id($_REQUEST["idEquipe"]);
            $donnees["titre"] = "Liste des joueurs";

            //afficher la ou les vues qu'on veut afficher
            require_once("vues/Entete.php");
            require_once("vues/ListeJoueurs.php");
            require_once("vues/PiedDePage.php");
        } else {
            header("Location: index.php");
            die();
        }
        break;
    case "FormAjoutEquipe":
        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/FormulaireAjoutEquipe.php");
        require_once("vues/PiedDePage.php");
        break;
    case "AjouteEquipe":
        if (isset($_REQUEST["nom"], $_REQUEST["ville"])) {
            //ici, mettre la validation nécessaire en fonction des champs du formulaire
            $nom = trim($_REQUEST["nom"]);
            $ville = trim($_REQUEST["ville"]);
            if ($nom != "" && $ville != "") {
                //procéder à l'insertion
                $test = ajoute_equipe($nom, $ville);

                if ($test)
                    header("Location: index.php?commande=ListeEquipes");
            } else {
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
                //afficher la ou les vues qu'on veut afficher
                require_once("vues/Entete.php");
                require_once("vues/FormulaireAjoutEquipe.php");
                require_once("vues/PiedDePage.php");
            }
        } else
            header("Location: index.php");

        break;
    case "FormAjoutJoueur":
        //obtenir les équipes dans le modèle
        $donnees["equipes"] = obtenir_equipes("");
        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/FormulaireAjoutJoueur.php");
        require_once("vues/PiedDePage.php");
        break;
    case "AjouteJoueur":
        if (isset($_REQUEST["nom"], $_REQUEST["prenom"], $_REQUEST["idEquipe"], $_REQUEST["salaire"], $_REQUEST["pays"])) {
            //ici, mettre la validation nécessaire en fonction des champs du formulaire
            $nom = trim($_REQUEST["nom"]);
            $prenom = trim($_REQUEST["prenom"]);
            $idEquipe = $_REQUEST["idEquipe"];
            $salaire = $_REQUEST["salaire"];
            $pays = trim($_REQUEST["pays"]);

            if ($nom != "" && $prenom != "" && $pays != "" && is_numeric($idEquipe) && is_numeric($salaire)) {
                //procéder à l'insertion
                $test = ajoute_joueur($nom, $prenom, $pays, $idEquipe, $salaire);

                if ($test)
                    header("Location: index.php?commande=ListeTousJoueurs");
            } else {
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
                //afficher la ou les vues qu'on veut afficher
                require_once("vues/Entete.php");
                require_once("vues/FormulaireAjoutJoueur.php");
                require_once("vues/PiedDePage.php");
            }
        }
        break;

    case "FormModifierJoueur":
        //obtenir les équipes dans le modèle
        $donnees["equipes"] = obtenir_equipes("");
        //obtenir les données de Joueur dans le modele
        if (isset($_REQUEST["joueurId"]) and is_numeric($_REQUEST["joueurId"])) {
            $joueurId = $_REQUEST["joueurId"];
            $donnees["Joueur"] = obtenir_joueur($joueurId);
            if (empty($donnees["Joueur"])) {
                $donnees["messageErreur"] = "L'identifiant de Joueur n'existe pas dans la base de données";
            } else {
                $donnees["Joueur"] = obtenir_joueur($joueurId);
                foreach ($donnees["Joueur"] as $cle => $valeur) $donnees["Joueur"][$cle] = htmlspecialchars($valeur);
            }
        } else {
            $donnees["messageErreur"] = "L'identifiant de Joueur est obligatoire et doit être entier.";
        }

        $donnees["titre"] = "Modifier joueur";
        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/FormulaireModifierJoueur.php");
        require_once("vues/PiedDePage.php");
        break;

    case "ModifierJoueur":
        if (isset($_REQUEST["joueurId"], $_REQUEST["nom"], $_REQUEST["prenom"], $_REQUEST["idEquipe"], $_REQUEST["salaire"], $_REQUEST["pays"])) {
            //ici, mettre la validation nécessaire en fonction des champs du formulaire
            $joueurId = trim($_REQUEST["joueurId"]);
            $nom = trim($_REQUEST["nom"]);
            $prenom = trim($_REQUEST["prenom"]);
            $idEquipe = $_REQUEST["idEquipe"];
            $salaire = $_REQUEST["salaire"];
            $pays = trim($_REQUEST["pays"]);

            if (is_numeric($joueurId) && $nom != "" && $prenom != "" && $pays != "" && is_numeric($idEquipe) && is_numeric($salaire)) {
                //procéder à l'insertion
                $modifier = modifier_joueur($joueurId, $nom, $prenom, $pays, $idEquipe, $salaire);
                if ($modifier) {
                    header("Location: index.php?commande=ListeTousJoueurs");
                    exit;
                } else {
                    $donnees["messageErreur"] = "Erreur lors de la modification.";
                }
            } else {
                $donnees["messageErreur"] = "Il faut entrer des valeurs dans tous les champs.";
            }
            $donnees["Joueur"] = ["id" => $joueurId, "nom" => $nom, "prenom" => $prenom, "idEquipe" => $idEquipe, "salaire" => $salaire, "pays" => $pays];
        } else {
            $donnees["messageErreur"] = "Il manque des champs. Contactez nous";
        }
        //obtenir les équipes dans le modèle
        $donnees["equipes"] = obtenir_equipes("");
        $donnees["titre"] = "Modifier joueur";
        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/FormulaireModifierJoueur.php");
        require_once("vues/PiedDePage.php");

        break;

    case "RechercherJoueurs":
        if (isset($_REQUEST["motCle"])) {
            //ici, mettre la validation nécessaire en fonction des champs du formulaire
            $motCle = trim($_REQUEST["motCle"]);

            if ($motCle != "") {
                $listeJoueurs = rechercher_joueurs($motCle);
                foreach ($listeJoueurs as $cle => $tableau) {
                    foreach ($tableau as $cleTab => $valTab) {
                        $listeJoueurs[$cle][$cleTab] = htmlspecialchars($valTab);
                    }
                }
                if (empty($listeJoueurs)) $donnees["messageErreur"] = "Pas de résultat";
            } else {
                $donnees["messageErreur"] = "Il faut entrer un nom ou prénom dans le champ.";
            }
        }

        $donnees["titre"] = "Rechercher joueur";
        //afficher la ou les vues qu'on veut afficher
        require_once("vues/Entete.php");
        require_once("vues/rechercherJoueurs.php");
        require_once("vues/PiedDePage.php");
        break;
    default:
        //action non traitée, commande invalide -- redirection
        header("Location: index.php");
}
