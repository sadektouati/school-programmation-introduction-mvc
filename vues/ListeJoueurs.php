<?php
if (!isset($donnees["equipe"])) {
?>
    <h1>Liste de tous les joueurs</h1>
<?php
} else {
    $equipe = $donnees["equipe"];
    $rangee = mysqli_fetch_assoc($equipe);
    echo "<h1>Liste des joueurs de l'équipe " . htmlspecialchars($rangee["nom"] . " de " . $rangee["ville"]) . "</h1>";
}
?>
<ul>
    <?php
    $joueurs = $donnees["joueurs"];

    //afficher dynamiquement les joueurs présents dans le tableau $joueurs
    while ($rangee = mysqli_fetch_assoc($joueurs)) {
        //à chaque tour de boucle, on affiche un joueur dans $rangee
        echo "<li>";
        echo htmlspecialchars($rangee["prenom"] . " " . $rangee["nom"]);
        echo " <a href='index.php?commande=FormModifierJoueur&joueurId={$rangee["id"]}'>modifier</a></li>";
    }
    ?>
</ul>
<div><a href='index.php?commande=FormAjoutJoueur'>Ajouter un joueur</a></div>