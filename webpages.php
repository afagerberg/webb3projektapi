<?php
//Projekt REST DT173G Alice Fagerberg
include_once("configuration/config.php");
include_once ("configuration/database.php");
/*Headers med inställningar för din REST webbtjänst*/

//Gör att webbtjänsten går att komma åt från alla domäner (asterisk * betyder alla)
header('Access-Control-Allow-Origin: *');

//Talar om att webbtjänsten skickar data i JSON-format
header('Content-Type: application/json');

//Vilka metoder som webbtjänsten accepterar, som standard tillåts bara GET.
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

//Vilka headers som är tillåtna vid anrop från klient-sidan, kan bli problem med CORS (Cross-Origin Resource Sharing) utan denna.
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

//Läser in vilken metod som skickats och lagrar i en variabel
$method = $_SERVER['REQUEST_METHOD'];

//Om en parameter av id finns i urlen lagras det i en variabel
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

$database = new Database();
$db = $database->getConnection();

$webpage = new Webpage($db);

switch($method) {
    case 'GET': //Hämta
        //Skickar en "HTTP response status code"
        http_response_code(200); //Ok - The request has succeeded

        $response = $webpage->getAllWebpages();

        if(count($response) == 0) {
            //Lagrar ett meddelande som sedan skickas tillbaka till anroparen
        $response = array("message" => "There is nothing to get yet");
        }

        

        break;
    case 'POST': // lägg till /skapa ny
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"));

        if($webpage->setPageurl($data->pageurl) == false || $webpage->setTitle($data->title) == false || $webpage->setDescription($data->pagedescription) == false){
            $response = array("message" => "Enter pageurl, a title, and a description!");

            http_response_code(400); //user error
        } else {
            if($webpage->addWebpage($data->pageurl, $data->title, $data->pagedescription)){

                $response = array("message" => "Created");
                http_response_code(201); //Created
    
            } else{
                $response = array("message" => "something went wrong");
                http_response_code(500); // server error
            }  
        }
        
        
        break;
    case 'PUT': //Uppdatera
        //Om inget id är med skickat, skicka felmeddelande
        if(!isset($id)) {
            http_response_code(400); //Bad Request - The server could not understand the request due to invalid syntax.
            $response = array("message" => "No id is sent");
        //Om id är skickad   
        } else {
            $data = json_decode(file_get_contents("php://input"));

            if($webpage->setPageurl($data->pageurl) == false || $webpage->setTitle($data->title) == false || $webpage->setDescription($data->pagedescription) == false){
                $response = array("message" => "Enter pageurl, a title, and a description!");
                
            }else{
                if($webpage->updateWebpage($id, $data->pageurl, $data->title, $data->pagedescription)){
                    http_response_code(200);
                    $response = array("message" => "webpage with id=$id is updated");
                }else{
                    http_response_code(503);
                    $response = array("message" => "webpage did not update, something went wrong");
                }
            }

            
        }
        break;
    case 'DELETE': //radera
        if(!isset($id)) {
            http_response_code(400);
            $response = array("message" => "No id is sent");  
        } else {
            // Kör för att radera en rad i tabellen
            if($webpage->deleteWebpage($id)){
                $response = array("message" => "webpage with id=$id is deleted");
                http_response_code(200);
            }
            else {
                http_response_code(503); //server error
                $response = array("message" => "could not delete, something went wrong");
            }    
            
        }
        break;
        
}

//Skickar svar tillbaka till avsändaren
$finaldata = json_encode($response, JSON_PRETTY_PRINT);

echo $finaldata;

$db = $database->close();

