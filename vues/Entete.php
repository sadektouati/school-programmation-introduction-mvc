<html>

<head>
    <meta charset='utf-8'>
    <title><?php if (isset($donnees["titre"])) echo $donnees["titre"]; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav>
        <a href="index.php" class="<?= $commande === 'Accueil' ? 'active' : "" ?>">Accueil</a>

        <a href="index.php?commande=ListeEquipes" class="<?= $commande === 'ListeEquipes' ? 'active' : "" ?>">Liste des équipes</a>

        <a href="index.php?commande=ListeTousJoueurs" class="<?= $commande === 'ListeTousJoueurs' ? 'active' : "" ?>">Liste des joueurs</a>

        <a href='?commande=FormAjoutEquipe' class="<?= $commande === 'FormAjoutEquipe' ? 'active' : "" ?>">Ajouter une équipe</a>

        <a href='index.php?commande=FormAjoutJoueur' class="<?= $commande === 'FormAjoutJoueur' ? 'active' : "" ?>">Ajouter un joueur</a>

        <a href='index.php?commande=RechercherJoueurs' class="<?= $commande === 'RechercherJoueurs' ? 'active' : "" ?>">Rechercher joueurs</a>
    </nav>