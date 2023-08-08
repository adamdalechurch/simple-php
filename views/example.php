<?php
require_once("core/view.php");
$view = new View("Example");
?>
<html>
    <head>
        <title>Example</title>
    </head>
    <body>
        <?php echo $view->create_form(); ?>
        <?php echo $view->list(); ?>
    </body>
</html>

<?php
    foreach ($items as $item) {
        echo $item["id"]." ".$item["name"]. "<br>";
    }
?>
