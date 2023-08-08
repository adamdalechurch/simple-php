<?php
class Api{
    
    private $repo;

    public function __construct($repo){
        $this->repo = $repo;
        $this->handle_request();
    }

    public function handle_request(){
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if(isset($_GET[$this->repo->get_id_name()])){
                    $result = $this->repo->get_by_id($id);
                    echo json_encode($result);
                }
                break;
            case 'POST':
                $this->repo->insert($_POST);
                break;
            case 'PUT':
                $this->repo->update($_POST, $id);
                break;
            case 'DELETE':
                $this->repo->delete($id);
                break;
            default:
                break;
            }
    }
}
?>
