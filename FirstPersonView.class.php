<?php

class FirstPersonView extends BaseClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getView(): string
    {
        // Récupére les coordonnées actuelles du joueur
        $currentX = $this->getCurrentX();
        $currentY = $this->getCurrentY();
        $currentAngle = $this->getCurrentAngle();
        $statusAction = $this->getStatusAction();

        // Vérifie si les coordonnées actuelles sont définies
        if ($currentX !== null && $currentY !== null && $currentAngle !== null && $statusAction !== null) {
    
            $query = "SELECT `path` 
                      FROM `images` 
                      JOIN `map` ON map.id = images.map_id 
                      WHERE `coordx` = :currentX 
                      AND `coordy` = :currentY 
                      AND `direction` = :currentAngle
                      AND images.status_action = :statusAction ";

            try {

                $stmt = $this->getDbh()->prepare($query);
                $stmt->bindParam(':currentX', $currentX, PDO::PARAM_INT);
                $stmt->bindParam(':currentY', $currentY, PDO::PARAM_INT);
                $stmt->bindParam(':currentAngle', $currentAngle, PDO::PARAM_INT);
                $stmt->bindParam(':statusAction', $statusAction, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si un résultat est trouvé, retourner le chemin de l'image. 
                if (!empty($result) && isset($result['path'])) {
                    return self::IMAGES_FOLDER . $result["path"]; 
                } else {
                    // Sinon, retourner le chemin de l'image par défault 
                return self::IMAGES_FOLDER . "doom-error.png"; 
                }

            } catch (PDOException $e) {
                // Gérer l'erreur de la base de données
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Si les coordonnées actuelles ne sont pas définies, retourner le chemin de l'image par défaut
        return self::IMAGES_FOLDER . "doom-error.png"; 
    }
}