<?php

session_start();

// Si la méthode de requête est GET, cela signifie que la page a été rafraîchie manuellement
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['inventoryEmpty'] = true;
}

// Initialise l'état de l'inventaire s'il n'est pas déjà initialisé
if (!isset($_SESSION['inventoryEmpty'])) {
    $_SESSION['inventoryEmpty'] = true;
}

function chargerClasse($classe)
{
    require $classe . '.class.php';
}
spl_autoload_register('chargerClasse');

// Utilisation du singleton BaseClass
$baseClass = BaseClass::getInstance();

$firstPersonView = new FirstPersonView();

$firstPersonText = new FirstPersonText();

$firstPersonAction = new FirstPersonAction();

if (empty($_POST)) {
    if (
        $baseClass->getCurrentX() === null
        || $baseClass->getCurrentY() === null
        || $baseClass->getCurrentAngle() === null
        || $baseClass->getStatusAction() === null
    ) {
        $baseClass->setCurrentX(0);
        $baseClass->setCurrentY(1);
        $baseClass->setCurrentAngle(0);
        $baseClass->setStatusAction(0);

        $firstPersonView->setCurrentX(0);
        $firstPersonView->setCurrentY(1);
        $firstPersonView->setCurrentAngle(0);
        $firstPersonView->setStatusAction(0);

        $firstPersonText->setCurrentX(0);
        $firstPersonText->setCurrentY(1);
        $firstPersonText->setCurrentAngle(0);
        $firstPersonText->setStatusAction(0);

        $firstPersonAction->setCurrentX(0);
        $firstPersonAction->setCurrentY(1);
        $firstPersonAction->setCurrentAngle(0);
        $firstPersonAction->setStatusAction(0);
    }
} else {
    // Vérifier si la clé 'action' est définie dans $_POST
    if (isset($_POST['action'])) {
        // La clé 'action' est définie, vous pouvez continuer avec votre logique existante
        $action = $_POST['action'];
        $currentX = $_POST['currentX'];
        $currentY = $_POST['currentY'];
        $currentAngle = $_POST['currentAngle'];
        $statusAction = $_POST['statusAction'];

        // Définir les coordonnées et le statut de l'action dans BaseClass et les autres classes
        $baseClass->setCurrentX($currentX);
        $baseClass->setCurrentY($currentY);
        $baseClass->setCurrentAngle($currentAngle);
        $baseClass->setStatusAction($statusAction);

        $firstPersonView->setCurrentX($currentX);
        $firstPersonView->setCurrentY($currentY);
        $firstPersonView->setCurrentAngle($currentAngle);
        $firstPersonView->setStatusAction($statusAction);

        $firstPersonText->setCurrentX($currentX);
        $firstPersonText->setCurrentY($currentY);
        $firstPersonText->setCurrentAngle($currentAngle);
        $firstPersonText->setStatusAction($statusAction);

        $firstPersonAction->setCurrentX($currentX);
        $firstPersonAction->setCurrentY($currentY);
        $firstPersonAction->setCurrentAngle($currentAngle);
        $firstPersonAction->setStatusAction($statusAction);

        // Exécuter l'action appropriée
        $baseClass->Action($action);
    } else {
        // La clé 'action' n'est pas définie dans $_POST
        // Vous pouvez gérer cela ici en affichant un message d'erreur ou en effectuant une autre action
    }
}

// Mettre à jour les coordonnées dans FirstPersonView, FirstPersonText et FirstPersonAction après chaque action
$firstPersonView->setCurrentX($baseClass->getCurrentX());
$firstPersonView->setCurrentY($baseClass->getCurrentY());
$firstPersonView->setCurrentAngle($baseClass->getCurrentAngle());
$firstPersonView->setStatusAction($baseClass->getStatusAction());

$firstPersonText->setCurrentX($baseClass->getCurrentX());
$firstPersonText->setCurrentY($baseClass->getCurrentY());
$firstPersonText->setCurrentAngle($baseClass->getCurrentAngle());
$firstPersonText->setStatusAction($baseClass->getStatusAction());

$firstPersonAction->setCurrentX($baseClass->getCurrentX());
$firstPersonAction->setCurrentY($baseClass->getCurrentY());
$firstPersonAction->setCurrentAngle($baseClass->getCurrentAngle());
$firstPersonAction->setStatusAction($baseClass->getStatusAction());

if ($_POST['action'] !== 'goEnter') {
    $imagePath = $firstPersonView->getView();
    $text = $firstPersonText->getText();
} else {
    $imagePath = $baseClass->doAction();
    $text = $firstPersonText->getText();
    if ($imagePath == "./images/12-90-1.jpg") {
        $text  = "Putain ! Trop bien j'ai la clef.";
    } else if ($imagePath == "./images/01-180-1.gif") {
        $text = "YES !!! Enfin dehors...";
    } else {
        $text = $firstPersonText->getText();
    }
}

// Utiliser la variable d'état de l'inventaire de la session pour déterminer l'image de l'inventaire
if ($_SESSION['inventoryEmpty']) {
    $inventoryImage = "./assets/vide.png";
} else {
    $inventoryImage = "./assets/cle.png";
}

// error_log("t'est ici " . $baseClass->getCurrentX() . " " . $baseClass->getCurrentY() . " " . $baseClass->getCurrentAngle()); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOOM LIKES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <script>
        function handleClick(action, currentX, currentY, currentAngle, statusAction) {

            if (document.getElementById(action + 'Button').disabled) {
                // Le bouton est désactivé, donc ne pas effectuer d'action
                return;
            }
            document.getElementById('action').value = action;
            document.getElementById('currentX').value = currentX;
            document.getElementById('currentY').value = currentY;
            document.getElementById('currentAngle').value = currentAngle;
            document.getElementById('statusAction').value = statusAction;
            document.getElementById('actionForm').submit();
        }

        function handleKey(event) {
            const currentX = <?php echo $baseClass->getCurrentX(); ?>;
            const currentY = <?php echo $baseClass->getCurrentY(); ?>;
            const currentAngle = <?php echo $baseClass->getCurrentAngle(); ?>;
            const statusAction = <?php echo $baseClass->getStatusAction(); ?>;

            switch (event.key.toUpperCase()) {
                case 'A':
                    handleClick('turnLeft', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'Z':
                    handleClick('goForward', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'E':
                    handleClick('turnRight', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'Q':
                    handleClick('goLeft', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'D':
                    handleClick('goRight', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'S':
                    handleClick('goBack', currentX, currentY, currentAngle, statusAction);
                    break;
                case 'ENTER':
                    handleClick('goEnter', currentX, currentY, currentAngle, statusAction);
                    break;
                default:
                    break;
            }
        }
    </script>
</head>

<body onkeyup="handleKey(event)">
    <div class="container">
        <div class="title"></div>
        <div class="screen" style="background-image: url(<?php echo $imagePath ?>);">
            <div class="inventory-container">
                <p class="inventory-txt">Inventaire : </p>
                <img src="<?php echo $inventoryImage ?>" alt="inventaire" width="50px" height="50px">
            </div>
        </div>
        <div class="controller-text-container">
            <div class="text">
                <p class="txt"><?php echo $text ?></p>
            </div>
            <div class="controller">
                <div class="controller-explain">
                    <p>A:<span class="icon-2">&#10226;</span></p>
                    <p>Z:<span>&#8679;</span></p>
                    <p>E:<span class="icon-2">&#10227;</span></p>
                    <p>Q:<span>&#8678;</span></p>
                    <p>S:<span>&#8681;</span></p>
                    <p>D:<span>&#8680;</span></p>
                    <p>Enter:<span>&#8617;</span></p>
                </div>
                <form id="actionForm" action="index.php" method="post">
                    <input type="hidden" id="action" name="action" value="">
                    <input type="hidden" id="currentX" name="currentX" value="">
                    <input type="hidden" id="currentY" name="currentY" value="">
                    <input type="hidden" id="currentAngle" name="currentAngle" value="">
                    <input type="hidden" id="statusAction" name="statusAction" value="">
                </form>
                <div class="turnLeft-top-turnRight">
                    <!-- "turn-Left" -->
                    <button class="icon turn-left" id="turnLeftButton" onclick="handleClick('turnLeft', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>,
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkTurnLeft(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>A</button>
                    <!-- "top" -->
                    <button class="icon up" id="goForwardButton" onclick="handleClick('goForward', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkForward(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>Z</button>
                    <!-- "turn-right" -->
                    <button class="icon turn-right" id="turnRightButton" onclick="handleClick('turnRight',
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkTurnRight(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>E</button>
                </div>
                <div class="left-action-right">
                    <!-- "goLeft" -->
                    <button class="icon left" id="goLeftButton" onclick="handleClick('goLeft',
                     <?php echo $baseClass->getCurrentX(); ?>,
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkLeft(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>Q</button>
                    <!-- "action" -->
                    <button class="icon action" id="goEnterButton" onclick="handleClick('goEnter', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$firstPersonAction->checkAction(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>Enter</button>
                    <!-- "goRight" -->
                    <button class="icon right" id="goRightButton" onclick="handleClick('goRight', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkRight(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>D</button>
                </div>
                <!-- "goBack" -->
                <div class="btn-down">
                    <button class="icon down" id="goBackButton" onclick="handleClick('goBack', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>,
                    <?php echo $baseClass->getStatusAction(); ?>)" <?php if (!$baseClass->checkBack(
                                                                        $baseClass->getCurrentX(),
                                                                        $baseClass->getCurrentY(),
                                                                        $baseClass->getCurrentAngle(),
                                                                        $baseClass->getStatusAction()
                                                                    )) echo "disabled"; ?>>S</button>
                </div>
            </div>
        </div>
    </div>
    <canvas id="frame"></canvas>
    <script src="./doom_fire.js"></script>
</body>

</html>