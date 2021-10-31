<?php
//DT173G Projekt Alice Fagerberg
class Job {

    //properties
    private $db;
    private $title;
    private $workplace;
    private $startdate;
    private $enddate;

    //constructor
    public function __construct() {
        //mysqli connection
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

        //check connection
        if ($this->db->connect_error){
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    /**
     * Add job
     * @param string $title
     * @param string $workplace
     * @param string $startdate
     * @param string $enddate
     */
    public function addJob(string $title, string $workplace, string $startdate, string $enddate) :bool{

            $this->title = $title;
            $this->workplace = $workplace;
            $this->startdate = $startdate;
            $this->enddate = $enddate;

            // prepeare statements
            $stmt = $this->db->prepare("INSERT INTO workexperience(title, workplace, startdate, enddate) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $this->title, $this->workplace, $this->startdate, $this->enddate);

            // execute statement
            if ($stmt->execute()) {
                return true;
            }else {
                return false;
            }
            

            $stmt->close();

    }

    /**
     * Get all jobs
     * @return array
     */
    public function getAllJobs() : array {
        $sql = "SELECT * FROM workexperience ORDER BY startdate;";
        $result = $this->db->query($sql);

        // return associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * get job by id
     * @param int $id
     * @return array
     */
    public function getJobByid(int $id) : array {

        $id = intval($id);
        
        $sql = "SELECT * FROM workexperience WHERE jobid=$id";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    /**
     * update a job 
     * @param string $title
     * @param string $workplace
     * @param string $startdate
     * @param string $enddate
     * return boolean
     */
    function updateJob(int $id, string $title, string $workplace, string $startdate, string $enddate) : bool {

        $this->title = $title;
        $this->workplace = $workplace;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $id = intval($id);

        //prepare statements
        $stmt = $this->db->prepare("UPDATE workexperience SET title=?, workplace=?, startdate=?, enddate=? WHERE jobid=$id;");
        $stmt->bind_param("ssss", $this->title, $this->workplace, $this->startdate, $this->enddate);

        // execute statement
        if ($stmt->execute()) {
            return true;
        }else {
            return false;
        }
            

        $stmt->close();
        

    }

    /**
     * delete a job 
     * @param int $id
     * return boolean
     */
    public function deleteJob(int $id) :bool {
        $id = intval($id);

        $sql = "DELETE FROM workexperience WHERE jobid=$id;";
        $result = $this->db->query($sql);

        return $result;
    }

    // setters för alla properties

    //villkor för titel
    public function setTitle(string $title) : bool {
        if(strlen($title) > 2){
            $this->title = $this->db->real_escape_string($title);

            return true;

        }else {
            return false;
        }
    }

    //villkor för arbetsplats
    public function setWorkplace(string $workplace) : bool {
        if(strlen($workplace) > 2){
            $this->workplace = $this->db->real_escape_string($workplace);

            return true;

        }else {
            return false;
        }
    }


    //villkor för starttid
    public function setStartDate(string $startdate) : bool {
        
        if(strtotime($startdate)){
            $this->startdate = $this->db->real_escape_string($startdate);

            return true;

        }else {
            return false;
        }

    }

    //villkor för sluttid
    public function setEndDate(string $enddate) : bool {
     
        if(strtotime($enddate)){
            $this->enddate = $this->db->real_escape_string($enddate);

            return true;

        }else {
            return false;
        }

    }

    //getters - hämtar vardera property
    public function getTitle() : string {
        $this->Title = $Title;
    }

    public function getWorkplace() : string {
        $this->Workplace = $Workplace;
    }

    public function getStartDate() : string {
        $this->startdate = $startdate;
    }

    public function getEndDate() : string {
        $this->enddate = $enddate;
    }

    // destructor - avsluta databasanslutning
    function __destruct(){
        mysqli_close($this->db);
    }
}