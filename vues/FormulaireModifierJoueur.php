<h1>Formulaire de modification d'un joueur</h1>
<?php
if (isset($donnees["messageErreur"]))
    echo "<p class=error>" . $donnees["messageErreur"] . "</p>";
?>
<form method="POST" action="index.php">
    <fieldset><label for="">Prénom du joueur :</label> <input type="text" name="prenom" value="<?= $donnees["Joueur"]["prenom"] ?? "" ?>" /><br></fieldset>

    <fieldset><label for="">Nom du joueur :</label> <input type="text" name="nom" value="<?= $donnees["Joueur"]["nom"] ?? "" ?>" /><br></fieldset>
    <fieldset><label for="">Équipe :</label> <select name="idEquipe">
            <?php
            if (empty($donnees["equipes"])) {
                echo "<option disabled>erreur</option>";
            } else {
                $equipes = $donnees["equipes"];

                while ($e = mysqli_fetch_assoc($equipes)) {
                    echo "<option value='" . $e["id"] . "' " . (($donnees["Joueur"]["idEquipe"] ?? "") == $e["id"] ? 'selected' : '') . ">" . htmlspecialchars($e["nom"] . " de " . $e["ville"]) . "</option>";
                }
            }
            ?>
        </select>
    </fieldset>
    <fieldset><label for="">Salaire :</label> <input type="number" name="salaire" value="<?= $donnees["Joueur"]["salaire"] ?? "" ?>" /><br></fieldset>
    <fieldset>
        <label for="">Pays :</label> <input type="text" name="pays" value="<?= $donnees["Joueur"]["pays"] ?? "" ?>" /><br>
        <input type="hidden" name="joueurId" value="<?= $donnees["Joueur"]["id"] ?? "" ?>" />
    </fieldset>
    <fieldset>
        <input type="hidden" name="commande" value="ModifierJoueur" />
        <input type="submit" value="Sauvegarder" />
    </fieldset>
</form>