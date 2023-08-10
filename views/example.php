<?php
namespace SimplePHP\Views;

$view = new \SimplePHP\Core\View("Example");
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