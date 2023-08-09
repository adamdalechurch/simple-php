<?php
require_once("core/view.php");
$view = new View("Example");
?>
<html>
        <?php echo $view->head(); ?>
    <body>
        <?php echo $view->create_form(); ?>
        <div style="overflow-x:auto">
            <?php echo $view->list(); ?>
        </div>
        <?php echo $view->footer(); ?>
    </body>
</html>