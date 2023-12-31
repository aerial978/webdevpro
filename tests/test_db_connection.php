<?php

namespace tests;

require_once 'src/core/Db.php'; // Inclure le fichier Db.php

$db = \src\core\Db::getInstance();

try {
    // Exécuter une requête SELECT de test
    $query = "SELECT 1";
    $result = $db->query($query);

    if ($result !== false) {
        // La connexion a réussi, vous pouvez afficher un message ou effectuer d'autres actions ici.
        printf("Connexion à la base de données réussie.");
    } else {
        // La requête SELECT a échoué, ce qui signifie généralement une erreur de connexion.
        printf("Erreur lors de la requête SELECT : " . $db->errorInfo()[2]);
    }
} catch (\PDOException $e) {
    // Capturer et afficher toute exception PDO.
    printf("Erreur PDO : " . $e->getMessage());
}
