<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$host = 'localhost:3307'; // Nom du service MySQL dans le fichier docker-compose.yml
$user = 'pma';
$password = 'plomkiplomki';
$db = 'projet';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les données du corps de la requête au format JSON
        $requestData = json_decode(file_get_contents('php://input'), true);

        // Vérifier si les clés nécessaires existent dans les données JSON
        if (isset($requestData['firstName'], $requestData['email'], $requestData['password'])) {
            // Récupérer les données du formulaire
            $firstName = $requestData['firstName'];
            $email = $requestData['email'];
            $password = password_hash($requestData['password'], PASSWORD_DEFAULT); // Hacher le mot de passe

            // Vérifier si le nom existe déjà dans la base de données
            $checkQuery = "SELECT * FROM users WHERE nom = ?";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute([$firstName]);
            $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                // Répondre avec une erreur si le nom existe déjà
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Le nom existe déjà. Choisissez un autre nom.']);
            } else {
                // Insérer les données dans la base de données si le nom n'existe pas encore
                $insertQuery = "INSERT INTO users (nom, password, email) VALUES (?, ?, ?)";
                $insertStmt = $pdo->prepare($insertQuery);
                $insertStmt->execute([$firstName, $password, $email]);

                // Répondre avec une confirmation sous forme d'objet JSON
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Inscription réussie']);
            }
        } else {
            // Répondre avec une erreur si les données nécessaires ne sont pas présentes
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Données manquantes dans la requête']);
        }
    } catch (PDOException $e) {
        // En cas d'erreur, renvoyer un message d'erreur sous forme d'objet JSON
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erreur lors de l\'inscription : ' . $e->getMessage()]);
    }
}
?>
