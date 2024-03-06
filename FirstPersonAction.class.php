<?php

class FirstPersonAction extends BaseClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function checkAction()
    {
        // Récupére les coordonnées actuelles du joueur
        $currentX = $this->getCurrentX();
        $currentY = $this->getCurrentY();
        $currentAngle = $this->getCurrentAngle();
        $statusAction = $this->getStatusAction();

        // Vérifie si les coordonnées actuelles sont définies
        if ($currentX !== null && $currentY !== null && $currentAngle !== null && $statusAction !== null) {

            // Vérifie l'état de l'inventaire
            $inventoryEmpty = $_SESSION['inventoryEmpty'];

            $actionTrue = "SELECT `action` 
               FROM `actions` 
               JOIN `map` ON map.id = actions.map_id 
               WHERE `coordx` = :currentX 
               AND `coordy` = :currentY 
               AND `direction` = :currentAngle
               AND actions.status = :statusAction ";

            try {

                $stmt = $this->getDbh()->prepare($actionTrue);
                $stmt->bindParam(':currentX', $currentX, PDO::PARAM_INT);
                $stmt->bindParam(':currentY', $currentY, PDO::PARAM_INT);
                $stmt->bindParam(':currentAngle', $currentAngle, PDO::PARAM_INT);
                $stmt->bindParam(':statusAction', $statusAction, PDO::PARAM_INT);
                $stmt->execute();

                $resultActionTrue = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($resultActionTrue) && $resultActionTrue['action'] === 'take') {
                    return $inventoryEmpty;
                } else if (!empty($resultActionTrue) && $resultActionTrue['action'] === 'use') {
                    return !$inventoryEmpty;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                // Gérer l'erreur de la base de données
                error_log("Database error: " . $e->getMessage());
            }
        }
        // Si les coordonnées actuelles ne sont pas définies, retourner false 
        error_log("CA : Error");
        return false;
    }
}
