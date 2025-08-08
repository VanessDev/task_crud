<?php
require_once __DIR__ . '/../assets/config/database.php';
function db(): PDO
{
    return dbConnexion();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    $pdo = db();
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
