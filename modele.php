<?php
/*
        modele.php est le fichier qui représente notre MODÈLE dans notre architecture MVC. 
        C'est donc dans ce fichier que nous retrouverons TOUTES nos requêtes SQL sans AUCUNE EXCEPTION. 
        C'est aussi ici que se trouvera la connexion à la base de données et les informations nécessaires 
        à celle-ci (username, hostname, password, nom de la base, etc...)
    
    */
define("SERVER", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DBNAME", "ligue");

function connectDB()
{
    //se connecter à la base de données
    $c = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME);

    if (!$c)
        trigger_error("Erreur de connexion : " . mysqli_connect_error());

    //s'assurer que la connection traite du UTF8
    mysqli_query($c, "SET NAMES 'utf8'");

    return $c;
}

$connexion = connectDB();

function obtenir_equipes($ordre)
{
    global $connexion;
    $requete = "SELECT id, nom, ville FROM equipe " . (in_array($ordre, ["nom", "ville"]) ? " order by " . $ordre : "");
    //exécuter la requête avec mysqli... 
    $resultats = mysqli_query($connexion, $requete);
    //retourner le résultat, les rangées dans le cas d'un SELECT ou true ou false dans le cas d'un INSERT/DELETE/UPDATE
    return $resultats;
}

function obtenir_equipe_par_id($idEquipe)
{
    global $connexion;
    $requete = "SELECT id, nom, ville FROM equipe WHERE id = $idEquipe";
    //exécuter la requête avec mysqli... 
    $resultats = mysqli_query($connexion, $requete);
    //retourner le résultat, les rangées dans le cas d'un SELECT ou true ou false dans le cas d'un INSERT/DELETE/UPDATE
    return $resultats;
}

function obtenir_joueurs_par_equipe($idEquipe)
{
    global $connexion;
    $requete = "SELECT id, prenom, nom FROM joueur WHERE idEquipe = $idEquipe";
    $resultat = mysqli_query($connexion, $requete);
    return $resultat;
}

function obtenir_tous_joueurs()
{
    global $connexion;
    $requete = "SELECT id, nom, prenom, idEquipe, salaire, pays FROM joueur";
    //exécuter la requête avec mysqli... 
    $resultats = mysqli_query($connexion, $requete);
    //retourner le résultat, les rangées dans le cas d'un SELECT ou true ou false dans le cas d'un INSERT/DELETE/UPDATE
    return $resultats;
}

function ajoute_equipe($nom, $ville)
{
    global $connexion;

    $requete = "INSERT INTO equipe(nom, ville) VALUES (?, ?)";
    //on doit préparer la requête parce que $n et $v peuvent contenir n'importe quoi
    if ($reqPrep = mysqli_prepare($connexion, $requete)) {
        //lier les paramètres
        mysqli_stmt_bind_param($reqPrep, "ss", $nom, $ville);
        //exécuter la requête 
        mysqli_stmt_execute($reqPrep);

        //est-ce que l'insertion a fonctionné?
        if (mysqli_affected_rows($connexion) > 0)
            return true;
        else
            die("Erreur lors de l'insertion.");
    }
}

function ajoute_joueur($n, $p, $pa, $idE, $s)
{
    global $connexion;

    $requete = "INSERT INTO joueur(nom, prenom, pays, idEquipe, salaire) VALUES (?, ?, ?, ?, ?)";
    //on doit préparer la requête parce que $n et $v peuvent contenir n'importe quoi
    if ($reqPrep = mysqli_prepare($connexion, $requete)) {
        //lier les paramètres
        mysqli_stmt_bind_param($reqPrep, "sssii", $n, $p, $pa, $idE, $s);
        //exécuter la requête 
        mysqli_stmt_execute($reqPrep);

        //est-ce que l'insertion a fonctionné?
        if (mysqli_affected_rows($connexion) > 0)
            return true;
        else
            die("Erreur lors de l'insertion.");
    }
}



// mon travail 

function obtenir_joueur($id)
{
    global $connexion;
    $requete = "SELECT id, nom, prenom, idEquipe, salaire, pays FROM joueur where id = ?";

    if ($reqPrep = mysqli_prepare($connexion, $requete)) {
        mysqli_stmt_bind_param($reqPrep, "i", $id);

        mysqli_stmt_execute($reqPrep);
        $resultats = mysqli_stmt_get_result($reqPrep);

        return mysqli_fetch_assoc($resultats);
    } else {
        return false;
    }
}

function modifier_joueur($id, $n, $p, $pa, $idE, $s)
{
    global $connexion;

    $requete = "update joueur set nom =?, prenom = ?, pays = ?, idEquipe = ?, salaire = ? where id = ?";
    //on doit préparer la requête parce que $n et $v peuvent contenir n'importe quoi

    if ($reqPrep = mysqli_prepare($connexion, $requete)) {
        //lier les paramètres
        mysqli_stmt_bind_param($reqPrep, "sssiii", $n, $p, $pa, $idE, $s, $id);
        //exécuter la requête 
        mysqli_stmt_execute($reqPrep);

        //est-ce que l'insertion a fonctionné?

        // ça cause un probleme si on envoi le formulaire sans toucher les controles
        return mysqli_affected_rows($connexion) > 0;
    }
}


function rechercher_joueurs($motCle)
{
    global $connexion;
    $motCle = "%" . $motCle . "%";

    $requete = "SELECT joueur.id idJoueur, joueur.nom nomJoueur, joueur.prenom prenomJoueur, idEquipe, equipe.nom nomEquipe, ville FROM joueur left join equipe on equipe.id=idequipe where (joueur.nom like ? or joueur.prenom like ?)";

    if ($reqPrep = mysqli_prepare($connexion, $requete)) {
        mysqli_stmt_bind_param($reqPrep, "ss", $motCle, $motCle);

        mysqli_stmt_execute($reqPrep);
        $resultats = mysqli_stmt_get_result($reqPrep);

        return mysqli_fetch_all($resultats, MYSQLI_ASSOC);
    }
}
