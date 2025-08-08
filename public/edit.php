<?php
require_once __DIR__ . '/../assets/config/database.php';
function db(): PDO
{
    return dbConnexion();
}
function esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Récupère la tâche
$pdo = db();
$stmt = $pdo->prepare("SELECT id, title, description, status, priority FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();
if (!$task) {
    header('Location: index.php');
    exit;
}

$errors = [];
$title = $task['title'];
$description = $task['description'];
$status = $task['status'];
$priority = $task['priority'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'à faire';
    $priority = $_POST['priority'] ?? 'moyenne';

    if ($title === '')
        $errors[] = "Le titre est obligatoire.";
    if (!in_array($status, ['à faire', 'en cours', 'terminée'], true))
        $errors[] = "Status invalide.";
    if (!in_array($priority, ['basse', 'moyenne', 'haute'], true))
        $errors[] = "Priorité invalide.";

    if (!$errors) {
        $up = $pdo->prepare("UPDATE tasks SET title=?, description=?, status=?, priority=? WHERE id=?");
        $up->execute([$title, $description, $status, $priority, $id]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Éditer la tâche</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="wrapper">
        <h1>Éditer la tâche #<?= (int) $id ?></h1>

        <?php foreach ($errors as $e): ?>
            <div class="error"><?= esc($e) ?></div>
        <?php endforeach; ?>

        <form class="form" method="POST">
            <label>Titre</label>
            <input type="text" name="title" value="<?= esc($title) ?>" required>

            <label>Description</label>
            <textarea name="description" rows="5"><?= esc($description) ?></textarea>

            <div class="row">
                <div>
                    <label>Statut</label>
                    <select name="status">
                        <option value="à faire" <?= $status === 'à faire' ? 'selected' : '' ?>>à faire</option>
                        <option value="en cours" <?= $status === 'en cours' ? 'selected' : '' ?>>en cours</option>
                        <option value="terminée" <?= $status === 'terminée' ? 'selected' : '' ?>>terminée</option>
                    </select>
                </div>
                <div>
                    <label>Priorité</label>
                    <select name="priority">
                        <option value="basse" <?= $priority === 'basse' ? 'selected' : '' ?>>basse</option>
                        <option value="moyenne" <?= $priority === 'moyenne' ? 'selected' : '' ?>>moyenne</option>
                        <option value="haute" <?= $priority === 'haute' ? 'selected' : '' ?>>haute</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button class="btn" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="index.php">Annuler</a>
            </div>
        </form>
    </div>
</body>

</html>