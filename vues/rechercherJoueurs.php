<h1>Rechercher des joueurs</h1>
<form action="">
    <input type="hidden" name="commande" value="RechercherJoueurs">
    <label for="">Prénom / Nom :</label> <input type="text" name="motCle" value="<?= $motCle ?? "" ?>" />
    <input type="submit" value="Rechercher" />
    <?php
    if (isset($donnees["messageErreur"]))
        echo "<p class=error>" . $donnees["messageErreur"] . "</p>";
    ?>
</form>
<?php

if (empty($listeJoueurs) == false) { ?>
    <h2>Résultat de recherche</h2>
    <ul>
        <?php
        foreach ($listeJoueurs as $rangee) {
            //à chaque tour de boucle, on affiche un joueur dans $rangee
            echo "<li>";
            echo $rangee["nomJoueur"] . " " . $rangee["prenomJoueur"] . " " . $rangee["nomEquipe"] . " " . $rangee["ville"];
            echo " <a href='index.php?commande=FormModifierJoueur&joueurId={$rangee["idJoueur"]}'>modifier</a>";
            echo "</li>";
        }
        ?>
    </ul>
<?php } ?>