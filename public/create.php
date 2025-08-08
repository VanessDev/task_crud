<!DOCTYPE html> <!-- 📄 Indique au navigateur qu’on utilise HTML5 -->
<html lang="fr"> <!-- 🌍 Langue principale du document : français -->

<head>
    <meta charset="UTF-8"> <!-- 🔠 Encodage des caractères en UTF-8 (gère les accents) -->
    <title>Tasks - CRUD</title> <!-- 🏷️ Titre affiché dans l’onglet du navigateur -->
    <link rel="stylesheet" href="../assets/style/style.css"> <!-- 🎨 Lien vers ton fichier CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- 📱 Responsive design -->
</head>

<body>
    <div class="wrapper"> <!-- 📦 Conteneur principal pour centrer et organiser le contenu -->

        <h1>Ma To-Do List (MySQL)</h1> <!-- 📝 Titre principal de la page -->

        <!-- 📌 Barre d’outils avec bouton pour ajouter une nouvelle tâche -->
        <div class="topbar">
            <a class="btn" href="create.php">➕ Nouvelle tâche</a> <!-- ➕ Lien vers la page d’ajout -->
        </div>

        <!-- 📋 Carte contenant le tableau de toutes les tâches -->
        <div class="card" style="margin-top:14px;">
            <table>
                <thead>
                    <tr> <!-- 🏷️ En-têtes des colonnes -->
                        <th style="width:20%">Titre</th> <!-- Nom de la tâche -->
                        <th style="width:48%">Description</th> <!-- Détails -->
                        <th style="width:16%">Statut</th> <!-- à faire / en cours / terminée -->
                        <th style="width:16%">Priorité</th> <!-- basse / moyenne / haute -->
                        <th>Actions</th> <!-- Éditer / Supprimer -->
                    </tr>
                </thead>
                <tbody>

                    <?php if (!$tasks): ?> <!-- ❌ Si le tableau $tasks est vide -->
                        <tr>
                            <td colspan="5">Aucune tâche.</td>
                        </tr> <!-- Affiche un message vide -->
                    <?php else: ?> <!-- ✅ Sinon, on affiche les tâches -->

                        <?php foreach ($tasks as $t): ?> <!-- 🔁 Boucle sur chaque tâche -->
                            <tr>
                                <!-- 📌 Affiche le titre sécurisé contre XSS -->
                                <td><?= esc($t['title']) ?></td>

                                <!-- 📝 Affiche la description avec retour à la ligne -->
                                <td><?= nl2br(esc($t['description'] ?? '')) ?></td>

                                <!-- 🏷️ Affiche le statut avec une classe CSS dynamique -->
                                <td>
                                    <span class="pill <?= statusClass($t['status']) ?>">
                                        <?= esc($t['status']) ?>
                                    </span>
                                </td>

                                <!-- 🎯 Affiche la priorité avec un badge coloré -->
                                <td>
                                    <span class="badge <?= prioBadge($t['priority']) ?>">
                                        <?= esc(ucfirst($t['priority'])) ?>
                                    </span>
                                </td>

                                <!-- 🛠️ Boutons d’édition et de suppression -->
                                <td>
                                    <!-- ✏️ Lien vers la page d’édition de la tâche -->
                                    <a class="btn" href="edit.php?id=<?= (int) $t['id'] ?>">✏️ Éditer</a>

                                    <!-- 🗑️ Formulaire de suppression avec confirmation -->
                                    <form action="delete.php" method="POST" style="display:inline"
                                        onsubmit="return confirm('Supprimer cette tâche ?');">
                                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>"> <!-- id caché -->
                                        <button class="btn btn-secondary" type="submit">🗑️ Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?> <!-- 🔚 Fin de la boucle -->
                    <?php endif; ?> <!-- 🔚 Fin du if -->

                </tbody>
            </table>
        </div>
    </div>
</body>

</html>