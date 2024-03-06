<?php

class FirstPersonText extends BaseClass
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getText(): string
    {
        // Récupére les coordonnées actuelles du joueur
        $currentX = $this->getCurrentX();
        $currentY = $this->getCurrentY();
        $currentAngle = $this->getCurrentAngle();
        $statusAction = $this->getStatusAction();

        // Vérifie si les coordonnées actuelles sont définies
        if ($currentX !== null && $currentY !== null && $currentAngle !== null && $statusAction !== null) {
    
            $selectText = "SELECT `text` 
                      FROM `text` 
                      JOIN `map` ON map.id = text.map_id 
                      WHERE `coordx` = :currentX 
                      AND `coordy` = :currentY 
                      AND `direction` = :currentAngle
                      AND text.status_action = :statusAction";

            try {

                $stmt = $this->getDbh()->prepare($selectText);
                $stmt->bindParam(':currentX', $currentX, PDO::PARAM_INT);
                $stmt->bindParam(':currentY', $currentY, PDO::PARAM_INT);
                $stmt->bindParam(':currentAngle', $currentAngle, PDO::PARAM_INT);
                $stmt->bindParam(':statusAction', $statusAction, PDO::PARAM_INT);
                $stmt->execute();

                $resultSelectText = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si un résultat est trouvé, retourner le text de l'image. 
                if (!empty($resultSelectText) && isset($resultSelectText['text'])) {
                    return $resultSelectText["text"]; 
                } else {
                    return ""; 
                }

            } catch (PDOException $e) {
                // Gérer l'erreur de la base de données
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Si les coordonnées actuelles ne sont pas définies, retourner le chemin de l'image par défaut
        return error_log("txt : error"); 
    }
}

