<?php

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

error_log(print_r($_POST, 1));
// L'action est déclenchée si le formulaire est soumis

if (empty($_POST)) {
    if ($baseClass->getCurrentX() === null || $baseClass->getCurrentY() === null || $baseClass->getCurrentAngle() === null) {
        $baseClass->setCurrentX(0);
        $baseClass->setCurrentY(1);
        $baseClass->setCurrentAngle(0);

        $firstPersonView->setCurrentX(0);
        $firstPersonView->setCurrentY(1);
        $firstPersonView->setCurrentAngle(0);

        $firstPersonText->setCurrentX(0);
        $firstPersonText->setCurrentY(1);
        $firstPersonText->setCurrentAngle(0);

        $firstPersonAction->setCurrentX(0);
        $firstPersonAction->setCurrentY(1);
        $firstPersonAction->setCurrentAngle(0);
    }
} else {

    $baseClass->setCurrentX($_POST['currentX']);
    $baseClass->setCurrentY($_POST['currentY']);
    $baseClass->setCurrentAngle($_POST['currentAngle']);

    $firstPersonView->setCurrentX($_POST['currentX']);
    $firstPersonView->setCurrentY($_POST['currentY']);
    $firstPersonView->setCurrentAngle($_POST['currentAngle']);

    $firstPersonText->setCurrentX($_POST['currentX']);
    $firstPersonText->setCurrentY($_POST['currentY']);
    $firstPersonText->setCurrentAngle($_POST['currentAngle']);

    $firstPersonAction->setCurrentX($_POST['currentX']);
    $firstPersonAction->setCurrentY($_POST['currentY']);
    $firstPersonAction->setCurrentAngle($_POST['currentAngle']);

    // Vérifier d'abord si une action a été effectuée
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $baseClass->Action($action);
        $firstPersonView->Action($action);
        $firstPersonText->Action($action);
        $firstPersonAction->Action($action);
        error_log("Action OK");
    } else {
        error_log("Action is not defined");
    }
}

$imagePath = $firstPersonView->getView();

$text = $firstPersonText->getText();

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
        function handleClick(action, currentX, currentY, currentAngle) {

            if (document.getElementById(action + 'Button').disabled) {
                // Le bouton est désactivé, donc ne pas effectuer d'action
                return;
            }
            document.getElementById('action').value = action;
            document.getElementById('currentX').value = currentX;
            document.getElementById('currentY').value = currentY;
            document.getElementById('currentAngle').value = currentAngle;
            document.getElementById('actionForm').submit();
        }

        function handleKey(event) {
            const currentX = <?php echo $baseClass->getCurrentX(); ?>;
            const currentY = <?php echo $baseClass->getCurrentY(); ?>;
            const currentAngle = <?php echo $baseClass->getCurrentAngle(); ?>;

            switch (event.key.toUpperCase()) {
                case 'A':
                    handleClick('turnLeft', currentX, currentY, currentAngle);
                    break;
                case 'Z':
                    handleClick('goForward', currentX, currentY, currentAngle);
                    break;
                case 'E':
                    handleClick('turnRight', currentX, currentY, currentAngle);
                    break;
                case 'Q':
                    handleClick('goLeft', currentX, currentY, currentAngle);
                    break;
                case 'D':
                    handleClick('goRight', currentX, currentY, currentAngle);
                    break;
                case 'S':
                    handleClick('goBack', currentX, currentY, currentAngle);
                    break;
                case 'ENTER': 
                    handleClick('goEnter', currentX, currentY, currentAngle);
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
                <img src="./assets/vide.png" alt="inventaire" width="50px" height="50px">
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
                </form>
                <div class="turnLeft-top-turnRight">
                    <!-- "turn-Left" -->
                    <button class="icon turn-left" id="turnLeftButton" onclick="handleClick('turnLeft', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>,
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$baseClass->checkTurnLeft($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>A</button>
                    <!-- "top" -->
                    <button class="icon up" id="goForwardButton" onclick="handleClick('goForward', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$baseClass->checkForward($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>Z</button>
                    <!-- "turn-right" -->
                    <button class="icon turn-right" id="turnRightButton" onclick="handleClick('turnRight',
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$baseClass->checkTurnRight($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>E</button>
                </div>
                <div class="left-action-right">
                    <!-- "goLeft" -->
                    <button class="icon left" id="goLeftButton" onclick="handleClick('goLeft',
                     <?php echo $baseClass->getCurrentX(); ?>,
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$baseClass->checkLeft($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>Q</button>
                    <!-- "action" -->
                    <button class="icon action" id="goEnterButton" onclick="handleClick('goEnter', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$firstPersonAction->checkAction($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>Enter</button>
                    <!-- "goRight" -->
                    <button class="icon right" id="goRightButton" onclick="handleClick('goRight', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)"
                    <?php if (!$baseClass->checkRight($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>D</button>
                </div>
                <!-- "goBack" -->
                <div class="btn-down">
                    <button class="icon down" id="goBackButton" onclick="handleClick('goBack', 
                    <?php echo $baseClass->getCurrentX(); ?>, 
                    <?php echo $baseClass->getCurrentY(); ?>, 
                    <?php echo $baseClass->getCurrentAngle(); ?>)" 
                    <?php if (!$baseClass->checkBack($baseClass->getCurrentX(), $baseClass->getCurrentY(), $baseClass->getCurrentAngle())) echo "disabled"; ?>>S</button>
                </div>
            </div>
        </div>
    </div>
    <canvas id="frame"></canvas>
    <script src="./doom_fire.js"></script>
</body>

</html>