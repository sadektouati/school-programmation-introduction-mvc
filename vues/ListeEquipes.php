<h1>Liste des équipes</h1>
<span>
    Ordre par :
    <span>
        <a href="?commande=ListeEquipes&ordre=nom" class="<?= $ordre === "nom" ? "choisi" : "" ?>">nom</a>
        <a href="?commande=ListeEquipes&ordre=ville" class="<?= $ordre === "ville" ? "choisi" : "" ?>">ville</a>
    </span>
</span>

<?php if (isset($donnees["messageErreur"])) echo "<p>" . $donnees["messageErreur"] . "</p>"; ?>

<ul>
    <?php

    //aller chercher dans $donnees ce qui nous intéresse
    $equipes = $donnees["equipes"];

    while ($rangee = mysqli_fetch_assoc($equipes)) {
        //à chaque tour de boucle, $rangee vaut la nouvelle équipe
        echo "<li><a href='index.php?commande=ListeJoueursParEquipe&idEquipe=" . $rangee["id"] . "'>";
        echo htmlspecialchars($rangee["nom"] . " de " . $rangee["ville"]);
        echo "</a></li>";
    }

    ?>
</ul>

<div><a href='?commande=FormAjoutEquipe'>Ajouter une équipe</a></div>