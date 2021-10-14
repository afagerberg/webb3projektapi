<?php
//Moment 5 DT173G Alice fagerberg
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

$course = new Course($db);

switch($method) {
    case 'GET': //Hämta
        //Skickar en "HTTP response status code"
        http_response_code(200); //Ok - The request has succeeded

        $response = $course->getAllCourses();

        if(count($response) == 0) {
            //Lagrar ett meddelande som sedan skickas tillbaka till anroparen
        $response = array("message" => "There is nothing to get yet");
        }

        

        break;
    case 'POST': // lägg till /skapa ny
        //Läser in JSON-data skickad med anropet och omvandlar till ett objekt.
        $data = json_decode(file_get_contents("php://input"));

        if($data->coursecode == "" || $data->cname == "" || $data->progression == "" || $data->courseplan == ""){
            $response = array("message" => "Enter full cours code, course name, progression and url to course plan");

            http_response_code(400); //user error
        } else {
            if($course->addCourse($data->coursecode, $data->cname, $data->progression, $data->courseplan)){

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

            if($course->updateCourse($id, $data->coursecode, $data->cname, $data->progression, $data->courseplan)){
                http_response_code(200);
                $response = array("message" => "Kurs med id=$id is updated");
            }else{
                http_response_code(503);
                $response = array("message" =>"Kurs uppdaterades inte");
            }

            
        }
        break;
    case 'DELETE': //radera
        if(!isset($id)) {
            http_response_code(400);
            $response = array("message" => "No id is sent");  
        } else {
            // Kör för att radera en rad i tabellen
            if($course->deleteCourse($id)){
                $response = array("message" => "Kursen med id=$id är raderad");
                http_response_code(200);
            }
            else {
                http_response_code(503); //server error
                $response = array("message" => "det gick inte att radera, något gick fel");
            }    
            
        }
        break;
        
}

//Skickar svar tillbaka till avsändaren
$finaldata = json_encode($response);

echo $finaldata;

$db = $database->close();

