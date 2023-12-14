<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-Type: application/json');

   


// Vérifiez que la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $data = file_get_contents('php://input');
    $formData = urldecode($data);
    parse_str($formData, $formDataArray);

    $pseudo = $formDataArray['pseudo'];
    $mail = $formDataArray['mail'];
    $password = $formDataArray['password'];

    try {
        // Connexion à la base de données en utilisant PDO (ajustez ces valeurs selon votre configuration)
        $pdo = new PDO("mysql:host=localhost;dbname=projet", "pma", "plomkiplomki");

        // Configurer PDO pour générer des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête d'insertion des données
        $sql = "INSERT INTO users (pseudo, mail, password) VALUES (:pseudo, :mail, :password)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Inscription réussie']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'inscription : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode de requête incorrecte']);
}
?>
