<?php
// config/database.php
function dbConnexion(): PDO
{
    $host = "localhost";   // ou 127.0.0.1
    $port = 3306;          // port MySQL
    $dbname = "task_crud"; // <-- mets ici le nom de ta base
    $user = "root";        // identifiant MySQL (Laragon/XAMPP: souvent root)
    $pass = "";            // mot de passe (souvent vide en local)
    $charset = "utf8mb4";

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // En dev, utile de voir l’erreur:
        die("Connexion DB impossible : " . $e->getMessage());
    }
}
