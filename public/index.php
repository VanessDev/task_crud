<?php
// 📦 Inclut le fichier de connexion à la base de données
require_once __DIR__ . '/../assets/config/database.php';

// 💡 Petite fonction utilitaire qui renvoie un objet PDO connecté
function db(): PDO
{
  return dbConnexion(); // Appelle la fonction dbConnexion() définie dans database.php
}

// 📥 Connexion à la base de données
$pdo = db();

// 📤 Exécute une requête SQL pour récupérer toutes les tâches (colonnes sélectionnées) triées par id décroissant
$stmt = $pdo->query("SELECT id, title, description, status, priority FROM tasks ORDER BY id DESC");

// 📦 Récupère toutes les lignes du résultat dans un tableau associatif
$tasks = $stmt->fetchAll();

// 🛡️ Fonction pour sécuriser l'affichage du texte (contre les failles XSS)
function esc(string $s): string
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// 🎨 Fonction qui retourne une classe CSS en fonction du statut de la tâche
function statusClass(string $s): string
{
  return match ($s) {
    'à faire' => 's-afaire',
    'en cours' => 's-encours',
    'terminée' => 's-terminee',
    default => 's-afaire' // Valeur par défaut
  };
}

// 🎨 Fonction qui retourne une classe CSS en fonction de la priorité de la tâche
function prioBadge(string $p): string
{
  return match ($p) {
    'haute' => 'badge-haute',
    'moyenne' => 'badge-moyenne',
    'basse' => 'badge-basse',
    default => 'badge-moyenne' // Valeur par défaut
  };
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8"> <!-- Déclare l'encodage UTF-8 -->
  <title>Tasks - CRUD</title> <!-- Titre affiché dans l'onglet -->
  <link rel="stylesheet" href="../assets/style/style.css"> <!-- Lien vers ton fichier CSS -->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Responsive mobile -->
</head>

<body>
  <div class="wrapper"> <!-- Conteneur principal -->
    <h1>Ma To-Do List (MySQL)</h1>

    <!-- Barre en haut avec le bouton "Nouvelle tâche" -->
    <div class="topbar">
      <a class="btn" href="create.php">➕ Nouvelle tâche</a>
    </div>

    <!-- Carte contenant le tableau des tâches -->
    <div class="card" style="margin-top:14px;">
      <table>
        <thead>
          <tr>
            <th style="width:20%">Titre</th>
            <th style="width:48%">Description</th>
            <th style="width:16%">Statut</th>
            <th style="width:16%">Priorité</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$tasks): ?> <!-- Si le tableau $tasks est vide -->
            <tr>
              <td colspan="5">Aucune tâche.</td>
            </tr>
          <?php else: ?> <!-- Sinon, on boucle sur les tâches -->
            <?php foreach ($tasks as $t): ?>
              <tr>
                <!-- Affiche le titre en le sécurisant -->
                <td><?= esc($t['title']) ?></td>

                <!-- Affiche la description avec sauts de ligne -->
                <td><?= nl2br(esc($t['description'] ?? '')) ?></td>

                <!-- Affiche le statut avec un style CSS selon statusClass() -->
                <td><span class="pill <?= statusClass($t['status']) ?>"><?= esc($t['status']) ?></span></td>

                <!-- Affiche la priorité avec un badge CSS selon prioBadge() -->
                <td><span class="badge <?= prioBadge($t['priority']) ?>"><?= esc(ucfirst($t['priority'])) ?></span></td>

                <!-- Actions : éditer ou supprimer -->
                <td>
                  <!-- Bouton vers la page d’édition -->
                  <a class="btn" href="edit.php?id=<?= (int) $t['id'] ?>">✏️ Éditer</a>

                  <!-- Formulaire pour suppression avec confirmation JS -->
                  <form action="delete.php" method="POST" style="display:inline"
                    onsubmit="return confirm('Supprimer cette tâche ?');">
                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                    <button class="btn btn-secondary" type="submit">🗑️ Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>