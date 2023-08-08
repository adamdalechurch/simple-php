<?php
$RepoClass = "Example";
require_once("core/view.php");
?>
<html>
    <head>
        <title>Example</title>
    </head>
    <body>
        <form method="POST">
            <input type="text" name="name">
            <input type="hidden" name="id" >
            <input type="submit" value="Submit">
        </form>
    </body>
</html>

<?php
    foreach ($items as $item) {
        echo $item["id"]." ".$item["name"]. "<br>";
    }
?>
