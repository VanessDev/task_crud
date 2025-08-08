<?php
// 📦 Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../assets/config/database.php';

// 🔌 Fonction utilitaire qui retourne un objet PDO connecté à la base
function db(): PDO
{
    return dbConnexion(); // Appelle la fonction dbConnexion() du fichier inclus
}

// 🛡 Fonction pour sécuriser les sorties HTML (évite les failles XSS)
function esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// 📥 Récupère l'ID de la tâche passé dans l'URL (GET), ou 0 par défaut
$id = (int) ($_GET['id'] ?? 0);

// 🚫 Si l’ID est invalide ou absent, on redirige vers l’index
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// 📤 Connexion à la base et récupération de la tâche correspondante
$pdo = db();
$stmt = $pdo->prepare("SELECT id, title, description, status, priority FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

// 🚫 Si aucune tâche trouvée avec cet ID, on retourne à l’index
if (!$task) {
    header('Location: index.php');
    exit;
}

// 📦 Variables pré-remplies avec les données de la tâche pour le formulaire
$errors = [];
$title = $task['title'];
$description = $task['description'];
$status = $task['status'];
$priority = $task['priority'];

// 📩 Si le formulaire est soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🧹 Récupération et nettoyage des données envoyées
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'à faire';
    $priority = $_POST['priority'] ?? 'moyenne';

    // ✅ Validation du titre
    if ($title === '')
        $errors[] = "Le titre est obligatoire.";

    // ✅ Validation du statut
    if (!in_array($status, ['à faire', 'en cours', 'terminée'], true))
        $errors[] = "Status invalide.";

    // ✅ Validation de la priorité
    if (!in_array($priority, ['basse', 'moyenne', 'haute'], true))
        $errors[] = "Priorité invalide.";

    // 💾 Si pas d'erreurs, on met à jour la tâche
    if (!$errors) {
        $up = $pdo->prepare("UPDATE tasks SET title=?, description=?, status=?, priority=? WHERE id=?");
        $up->execute([$title, $description, $status, $priority, $id]);

        // 🔁 Redirection vers l'index après mise à jour
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html> <!-- 📄 Document HTML5 -->
<html lang="fr"> <!-- 🌍 Langue française -->

<head>
    <meta charset="UTF-8"> <!-- Encodage UTF-8 -->
    <title>Éditer la tâche</title> <!-- 🏷️ Titre de la page -->
    <link rel="stylesheet" href="../assets/style/style.css"> <!-- 🎨 Fichier CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- 📱 Responsive -->
</head>

<body>
    <div class="wrapper"> <!-- 📦 Conteneur principal -->
        <h1>Éditer la tâche #<?= (int) $id ?></h1> <!-- 📝 Titre avec ID de la tâche -->

        <!-- 🚨 Affichage des erreurs éventuelles -->
        <?php foreach ($errors as $e): ?>
            <div class="error"><?= esc($e) ?></div>
        <?php endforeach; ?>

        <!-- 🖊 Formulaire d’édition -->
        <form class="form" method="POST">
            <!-- Champ titre -->
            <label>Titre</label>
            <input type="text" name="title" value="<?= esc($title) ?>" required>

            <!-- Champ description -->
            <label>Description</label>
            <textarea name="description" rows="5"><?= esc($description) ?></textarea>

            <!-- Sélection statut -->
            <div class="row">
                <div>
                    <label>Statut</label>
                    <select name="status">
                        <option value="à faire" <?= $status === 'à faire' ? 'selected' : '' ?>>à faire</option>
                        <option value="en cours" <?= $status === 'en cours' ? 'selected' : '' ?>>en cours</option>
                        <option value="terminée" <?= $status === 'terminée' ? 'selected' : '' ?>>terminée</option>
                    </select>
                </div>

                <!-- Sélection priorité -->
                <div>
                    <label>Priorité</label>
                    <select name="priority">
                        <option value="basse" <?= $priority === 'basse' ? 'selected' : '' ?>>basse</option>
                        <option value="moyenne" <?= $priority === 'moyenne' ? 'selected' : '' ?>>moyenne</option>
                        <option value="haute" <?= $priority === 'haute' ? 'selected' : '' ?>>haute</option>
                    </select>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="actions">
                <button class="btn" type="submit">Enregistrer</button> <!-- 💾 Sauvegarde -->
                <a class="btn btn-secondary" href="index.php">Annuler</a> <!-- ❌ Retour -->
            </div>
        </form>
    </div>
</body>

</html>