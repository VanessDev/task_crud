<!DOCTYPE html> <!-- 📄 Indique que le document est en HTML5 -->
<html lang="fr"> <!-- 🌍 Définit la langue principale de la page comme étant le français -->

<head>
    <meta charset="UTF-8">
    <!-- 🈚 Définit l’encodage des caractères en UTF-8 (pour afficher les accents correctement) -->
    <title>Éditer la tâche</title> <!-- 🏷️ Titre affiché dans l’onglet du navigateur -->
    <link rel="stylesheet" href="../assets/style/style.css"> <!-- 🎨 Lien vers le fichier CSS pour la mise en forme -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 📱 Permet au site de s’adapter sur mobile -->
</head>

<body>
    <div class="wrapper"> <!-- 📦 Conteneur principal qui englobe le contenu -->

        <!-- 📝 Titre de la page avec l'ID de la tâche récupéré en PHP -->
        <h1>Éditer la tâche #<?= (int) $id ?></h1>

        <!-- 🚨 Boucle sur le tableau $errors pour afficher chaque message d’erreur -->
        <?php foreach ($errors as $e): ?>
            <!-- 🛡 Sécurise et affiche le message d'erreur -->
            <div class="error"><?= esc($e) ?></div>
        <?php endforeach; ?>

        <!-- 🖊 Formulaire d’édition de tâche, en méthode POST -->
        <form class="form" method="POST">

            <!-- 📌 Champ texte pour le titre -->
            <label>Titre</label>
            <!-- 🛡 On sécurise l’affichage de la valeur avec esc() -->
            <input type="text" name="title" value="<?= esc($title) ?>" required>

            <!-- 📝 Champ texte long pour la description -->
            <label>Description</label>
            <!-- rows définit la hauteur de la zone -->
            <textarea name="description" rows="5"><?= esc($description) ?></textarea>

            <!-- 📂 Ligne qui contient 2 colonnes : Statut et Priorité -->
            <div class="row">

                <!-- 📌 Menu déroulant pour choisir le statut -->
                <div>
                    <label>Statut</label>
                    <select name="status">
                        <!-- Chaque option est sélectionnée si $status correspond -->
                        <option value="à faire" <?= $status === 'à faire' ? 'selected' : '' ?>>à faire</option>
                        <option value="en cours" <?= $status === 'en cours' ? 'selected' : '' ?>>en cours</option>
                        <option value="terminée" <?= $status === 'terminée' ? 'selected' : '' ?>>terminée</option>
                    </select>
                </div>

                <!-- 📌 Menu déroulant pour choisir la priorité -->
                <div>
                    <label>Priorité</label>
                    <select name="priority">
                        <!-- Même principe que pour le statut -->
                        <option value="basse" <?= $priority === 'basse' ? 'selected' : '' ?>>basse</option>
                        <option value="moyenne" <?= $priority === 'moyenne' ? 'selected' : '' ?>>moyenne</option>
                        <option value="haute" <?= $priority === 'haute' ? 'selected' : '' ?>>haute</option>
                    </select>
                </div>
            </div>

            <!-- 🔘 Zone des boutons -->
            <div class="actions">
                <!-- 💾 Bouton pour sauvegarder la modification -->
                <button class="btn" type="submit">Enregistrer</button>
                <!-- ❌ Lien pour annuler et revenir à l’index -->
                <a class="btn btn-secondary" href="index.php">Annuler</a>
            </div>
        </form>
    </div>
</body>

</html>