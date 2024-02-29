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

        error_log("x for txt " . $currentX);
        error_log("Y for txt " . $currentY);
        error_log("° for txt " . $currentAngle); 

        // Vérifie si les coordonnées actuelles sont définies
        if ($currentX !== null && $currentY !== null && $currentAngle !== null) {
    
            $query = "SELECT `text` 
                      FROM `text` 
                      JOIN `map` ON map.id = text.map_id 
                      WHERE `coordx` = :currentX 
                      AND `coordy` = :currentY 
                      AND `direction` = :currentAngle";

            try {

                $stmt = $this->getDbh()->prepare($query);
                $stmt->bindParam(':currentX', $currentX, PDO::PARAM_INT);
                $stmt->bindParam(':currentY', $currentY, PDO::PARAM_INT);
                $stmt->bindParam(':currentAngle', $currentAngle, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                error_log("result txt:" .print_r($result, 1));

                // Si un résultat est trouvé, retourner le text de l'image. 
                if (!empty($result) && isset($result['text'])) {
                    return $result["text"]; 
                } else {
                    return ""; 
                }

            } catch (PDOException $e) {
                // Gérer l'erreur de la base de données
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Si les coordonnées actuelles ne sont pas définies, retourner le chemin de l'image par défaut
        return error_log("error text 2"); 
    }
}

