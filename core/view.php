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
        $repo->insert($data);
        break;
    case 'PUT':
        $repo->update($data, $id);
        break;
    case 'DELETE':
        $repo->delete($id);
        break;
    default:
        break;
}

$repo->set_debug(true);
$items = $repo->list();
$repo->set_debug(false);
?>

