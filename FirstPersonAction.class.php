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

        error_log("x for checkAction" . $currentX);
        error_log("Y for checkAction" . $currentY);
        error_log("° for checkAction" . $currentAngle);

        // Vérifie si les coordonnées actuelles sont définies
        if ($currentX !== null && $currentY !== null && $currentAngle !== null) {

            $query = "SELECT * 
               FROM `actions` 
               JOIN `map` ON map.id = actions.map_id 
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
                error_log("result:" . print_r($result, 1));

                // Si un résultat est trouvé, retourner true. 
                if (!empty($result)) {
                    return true;
                } else {
                    // Sinon, retourner false.
                    return false;
                }
            } catch (PDOException $e) {
                // Gérer l'erreur de la base de données
                error_log("Database error: " . $e->getMessage());
            }
        }
        // Si les coordonnées actuelles ne sont pas définies, retourner false 
        return false;
    }

}
