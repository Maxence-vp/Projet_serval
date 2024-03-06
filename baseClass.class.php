<?php

class BaseClass
{
    private static ?BaseClass $instance = null; // Instance unique de la classe.
    private object $_dbh; // Déclare une propriété privée contenant un objet de connexion à la base de données.
    private ?int $_currentX = null; // Déclare une propriété privée contenant la coordonnée X actuelle, pouvant être nulle.
    private ?int $_currentY = null; // Déclare une propriété privée contenant la coordonnée Y actuelle, pouvant être nulle.
    private ?int $_currentAngle = null; // Déclare une propriété privée contenant l'angle actuel, pouvant être nul.
    private ?int $_statusAction = null; // Déclare une propriété privée contenant le status, pouvant être nul.

    const IMAGES_FOLDER = './images/';

    // Méthode statique pour récupérer l'instance unique de BaseClass
    public static function getInstance(): BaseClass
    {
        // Vérifie si l'instance n'a pas déjà été créée
        if (self::$instance === null) {
            self::$instance = new BaseClass(); // Crée une nouvelle instance si nécessaire
        }
        return self::$instance;
    }

    // Définit le constructeur de la classe.
    protected function __construct()
    {
        // Initialise la propriété _dbh avec une nouvelle instance de la classe DataBase.
        $this->_dbh = new DataBase;
    }

    // Empêche la duplication de l'instance de la classe (ne fait rien car la classe n'est pas clonable)
    private function __clone()
    {
        // Ne rien faire
    }

    // Empêche la désérialisation de l'instance de la classe (ne fait rien car la classe n'est pas désérialisable)
    private function __wakeup()
    {
        // Ne rien faire
    }

    // Définit une méthode pour définir la connexion à la base de données.
    public function setDbh(object $dbh)
    {
        // Affecte la connexion à la propriété _dbh.
        $this->_dbh = $dbh;
    }

    // Définit une méthode pour obtenir la connexion à la base de données.
    public function getDbh(): object
    {
        // Retourne la connexion à la base de données.
        return $this->_dbh;
    }

    // Les méthodes setCurrentX, getCurrentX, setCurrentY, getCurrentY, setCurrentAngle et getCurrentAngle font la gestion des coordonnées X, Y et de l'angle actuel.
    public function setCurrentX(?int $currentX): void
    {
        if (is_int($currentX) && $currentX >= 0 && $currentX <= 1) {
            $this->_currentX = $currentX;
        } else {
            throw new InvalidArgumentException("
            The coordination X must be a positive number or zero.");
        }
    }

    public function getCurrentX(): ?int
    {
        return $this->_currentX;
    }

    public function setCurrentY(?int $currentY): void
    {
        if (is_int($currentY) && $currentY >= 0 && $currentY <= 2) {
            $this->_currentY = $currentY;
        } else {
            throw new InvalidArgumentException("
            The coordination Y must be a positive number or zero.");
        }
    }

    public function getCurrentY(): ?int
    {
        return $this->_currentY;
    }

    public function setCurrentAngle(?int $currentAngle): void
    {
        if (is_int($currentAngle) && in_array($currentAngle, [0, 90, 180, 270])) {
            $this->_currentAngle = $currentAngle;
        } else {
            throw new InvalidArgumentException("
            The angle must be 0, 90, 180 or 270 degrees.");
        }
    }

    public function getCurrentAngle(): ?int
    {
        return $this->_currentAngle;
    }

    public function setStatusAction(?int $statusAction): void
    {
        if (is_int($statusAction) && ($statusAction === 0 || $statusAction === 1)) {
            $this->_statusAction = $statusAction;
        } else {
            throw new InvalidArgumentException("
            status is egal to 0 or 1");
        }
    }

    public function getStatusAction(): ?int
    {
        return $this->_statusAction;
    }


    // Méthode privée pour vérifier si un mouvement est possible.
    private function _checkMove(?int $currentX, ?int $currentY, ?int $currentAngle): bool
    {

        try {
            $checkMove = "SELECT * FROM `map` WHERE `coordx`=:currentX AND `coordy`=:currentY AND `direction`=:currentAngle";
            $stmt = $this->_dbh->prepare($checkMove);
            $stmt->bindParam(":currentX", $currentX, PDO::PARAM_INT);
            $stmt->bindParam(":currentY", $currentY, PDO::PARAM_INT);
            $stmt->bindParam(":currentAngle", $currentAngle, PDO::PARAM_INT);
            $stmt->execute();

            $checkMoveResult = $stmt->fetchAll(PDO::FETCH_OBJ);
            // error_log($checkMove, 1); 

            if (!empty($checkMoveResult)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Gérer l'exception
            error_log("Database error : " . $e->getMessage());
            return false;
        }
    }

    // Les méthodes checkForward, checkBack, checkRight et checkLeft vérifient la possibilité de déplacer le personnage dans différentes directions en fonction de l'angle actuel.
    public function checkForward(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newX = $currentX;
        $newY = $currentY;

        switch ($currentAngle) {
            case 90:
                $newY++;
                break;
            case 0:
                $newX++;
                break;
            case 270:
                $newY--;
                break;
            case 180:
                $newX--;
                break;
        }

        return $this->_checkMove($newX, $newY, $currentAngle);
    }

    public function checkBack(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newX = $currentX;
        $newY = $currentY;

        switch ($currentAngle) {
            case 90:
                $newY--;
                break;
            case 0:
                $newX--;
                break;
            case 270:
                $newY++;
                break;
            case 180:
                $newX++;
                break;
        }

        return $this->_checkMove($newX, $newY, $currentAngle);
    }

    public function checkRight(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newX = $currentX;
        $newY = $currentY;

        switch ($currentAngle) {
            case 90:
                $newX++;
                break;
            case 0:
                $newY--;
                break;
            case 270:
                $newX--;
                break;
            case 180:
                $newY++;
                break;
        }

        return $this->_checkMove($newX, $newY, $currentAngle);
    }

    public function checkLeft(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newX = $currentX;
        $newY = $currentY;

        switch ($currentAngle) {
            case 90:
                $newX--;
                break;
            case 0:
                $newY++;
                break;
            case 270:
                $newX++;
                break;
            case 180:
                $newY--;
                break;
        }

        return $this->_checkMove($newX, $newY, $currentAngle);
    }

    public function checkTurnRight(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newAngle = $currentAngle;

        switch ($currentAngle) {
            case 90:
                $newAngle = 0;
                break;
            case 0:
                $newAngle = 270;
                break;
            case 270:
                $newAngle = 180;
                break;
            case 180:
                $newAngle = 90;
                break;
        }

        return $this->_checkMove($currentX, $currentY, $newAngle);
    }

    public function checkTurnLeft(int $currentX, int $currentY, int $currentAngle): bool
    {

        $newAngle = $currentAngle;

        switch ($currentAngle) {
            case 90:
                $newAngle = 180;
                break;
            case 0:
                $newAngle = 90;
                break;
            case 270:
                $newAngle = 0;
                break;
            case 180:
                $newAngle = 270;
                break;
        }

        return $this->_checkMove($currentX, $currentY, $newAngle);
    }

    // Les méthodes goForward, goBack, goRight et goLeft effectuent les déplacements dans différentes directions en fonction de l'angle actuel.
    public function goForward(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                ++$this->_currentY;
                break;
            case 0:
                ++$this->_currentX;
                break;
            case 270:
                --$this->_currentY;
                break;
            case 180:
                --$this->_currentX;
                break;
        }
    }

    public function goBack(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                --$this->_currentY;
                break;
            case 0:
                --$this->_currentX;
                break;
            case 270:
                ++$this->_currentY;
                break;
            case 180:
                ++$this->_currentX;
                break;
        }
    }

    public function goRight(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                ++$this->_currentX;
                break;
            case 0:
                --$this->_currentY;
                break;
            case 270:
                --$this->_currentX;
                break;
            case 180:
                ++$this->_currentX;
                break;
        }
    }

    public function goLeft(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                --$this->_currentX;
                break;
            case 0:
                ++$this->_currentY;
                break;
            case 270:
                ++$this->_currentX;
                break;
            case 180:
                --$this->_currentY;
                break;
        }
    }

    // Les méthodes turnRight et turnLeft modifient l'angle actuel du personnage dans le sens horaire et antihoraire, respectivement.
    public function turnRight(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                $this->_currentAngle = 0;
                break;
            case 0:
                $this->_currentAngle = 270;
                break;
            case 270:
                $this->_currentAngle = 180;
                break;
            case 180:
                $this->_currentAngle = 90;
                break;
        }
    }

    public function turnLeft(): void
    {

        switch ($this->_currentAngle) {
            case 90:
                $this->_currentAngle = 180;
                break;
            case 0:
                $this->_currentAngle = 90;
                break;
            case 270:
                $this->_currentAngle = 0;
                break;
            case 180:
                $this->_currentAngle = 270;
                break;
        }
    }

    public function doAction()
    {
        // Récupére les coordonnées actuelles du joueur
        $currentX = $this->getCurrentX();
        $currentY = $this->getCurrentY();
        $currentAngle = $this->getCurrentAngle();
        $statusAction = $this->getStatusAction();

        if ($currentX !== null && $currentY !== null && $currentAngle !== null && $statusAction !== null) {

            $actionsTakeUse = "SELECT `path`
                FROM `images` 
                JOIN `actions` ON images.map_id = actions.map_id 
                JOIN `map` ON images.map_id = map.id 
                WHERE `coordx` = :currentX 
                AND `coordy` = :currentY 
                AND `direction` = :currentAngle
                AND images.status_action = 1";

            $stmtT = $this->getDbh()->prepare($actionsTakeUse);
            $stmtT->bindParam(':currentX', $currentX, PDO::PARAM_INT);
            $stmtT->bindParam(':currentY', $currentY, PDO::PARAM_INT);
            $stmtT->bindParam(':currentAngle', $currentAngle, PDO::PARAM_INT);
            $stmtT->execute();

            $resultTakeUse = $stmtT->fetch(PDO::FETCH_ASSOC);
            if ($resultTakeUse !== false && $resultTakeUse['path'] === '12-90-1.jpg') {
                $_SESSION['inventoryEmpty'] = false; 
                return self::IMAGES_FOLDER . $resultTakeUse['path'];
            } else if ($resultTakeUse !== false && $resultTakeUse['path'] === '01-180-1.gif') {
                $_SESSION['inventoryEmpty'] = true; 
                return self::IMAGES_FOLDER . $resultTakeUse['path'];
            }
        } else {
            return self::IMAGES_FOLDER . 'doom-error.png';
        }
    }

    // La méthode Action prend une chaîne d'action en entrée et appelle la méthode correspondante en fonction de cette action.
    public function Action(string $action): void
    {
        switch ($action) {
            case 'turnLeft':
                $this->turnLeft();
                break;
            case 'goForward':
                $this->goForward();
                break;
            case 'turnRight':
                $this->turnRight();
                break;
            case 'goLeft':
                $this->goLeft();
                break;
            case 'goRight':
                $this->goRight();
                break;
            case 'goBack':
                $this->goBack();
                break;
            case 'goEnter':
                $this->doAction();
                break;
        }
    }
}
