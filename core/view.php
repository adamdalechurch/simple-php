<?php
require_once("data/".strtolower($RepoClass).".php");

$repo = new $RepoClass();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET[$repo->get_id_name()])){
            $result = $repo->get_by_id($id);
            echo json_encode($result);
        }
        break;
    case 'POST':
        $repo->insert($_POST);
        break;
    case 'PUT':
        $repo->update($_POST, $id);
        break;
    case 'DELETE':
        $repo->delete($id);
        break;
    default:
        break;
}

$items = $repo->list();

?>

