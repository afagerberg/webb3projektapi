<?php
//DT173G Projekt Alice Fagerberg
class Education {

    //properties
    private $db;
    private $coursecode;
    private $cname;
    private $program;
    private $eduplace;
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
     * Add education
     * @param string $coursecode
     * @param string $cname
     * @param string $program
     * @param string $eduplace
     * @param string $startdate
     * @param string $enddate
     */
    public function addEducation(string $coursecode, string $cname, string $program, string $eduplace, string $startdate, string $enddate) :bool{

        $this->coursecode = $coursecode;
        $this->cname = $cname;
        $this->program = $program;
        $this->eduplace = $eduplace;
        $this->startdate = $startdate;
        $this->enddate = $enddate;

            // prepare statements
            $stmt = $this->db->prepare("INSERT INTO completedstudies(coursecode, cname, program, eduplace, startdate, enddate) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $this->coursecode, $this->cname, $this->program, $this->eduplace, $this->startdate, $this->enddate);

            // execute statement
            if ($stmt->execute()) {
                return true;
            }else {
                return false;
            }
            

            $stmt->close();
        

    }

    /**
     * Get all educations
     * @return array
     */
    public function getAllEducations() : array {
        $sql = "SELECT * FROM completedstudies ORDER BY startdate;";
        $result = $this->db->query($sql);

        // retirn associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * get education by id
     * @param int $eduid
     * @return array
     */
    public function getEducationByid(int $eduid) : array {
        
        $sql = "SELECT * FROM completedstudies WHERE eduid=$eduid";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    /**
     * update education 
     * @param string $coursecode
     * @param string $cname
     * @param string $program
     * @param string $eduplace
     * @param string $startdate
     * @param string $enddate
     * return boolean
     */
    function updateEducation(int $eduid, string $coursecode, string $cname, string $program, string $eduplace, string $startdate, string $enddate) : bool {

        $this->coursecode = $coursecode;
        $this->cname = $cname;
        $this->program = $program;
        $this->eduplace = $eduplace;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $eduid= intval($eduid);

            //prepare statements
            $stmt = $this->db->prepare("UPDATE completedstudies SET coursecode=?, cname=?, program=?, eduplace=?, startdate=?, enddate=? WHERE eduid=$eduid;");
            $stmt->bind_param("ssssss", $this->coursecode, $this->cname, $this->program, $this->eduplace, $this->startdate, $this->enddate);

            // execute statement
            if ($stmt->execute()) {
                return true;
            }else {
                return false;
            }
            

            $stmt->close();

    }

    /**
     * delete an education by id
     * @param string $eduid
     * return boolean
     */
    public function deleteEducation(int $eduid) :bool {

        $eduid = intval($eduid);

        $sql = "DELETE FROM completedstudies WHERE eduid=$eduid;";
        $result = $this->db->query($sql);

        return $result;
    }

    // setters f??r alla properties

    //villkor f??r kurskod
    public function setCourseid(string $coursecode) : bool {
        if(strlen($coursecode) > 4){
            $this->coursecode = $this->db->real_escape_string($coursecode);

            return true;

        }else {
            return false;
        }
    }

    //villkor f??r kursnamn
    public function setCoursename(string $cname) : bool {
        if(strlen($cname) > 4){
            $this->cname = $this->db->real_escape_string($cname);

            return true;

        }else {
            return false;
        }
    }

    //villkor f??r utbildningsprogram
    public function setProgram(string $program) : bool {
        if(strlen($program) > 4){
            $this->program = $this->db->real_escape_string($program);

            return true;

        }else {
            return false;
        }
    }

    //villkor f??r utbildningsprogram
    public function setEduPlace(string $eduplace) : bool {
        if(strlen($eduplace) > 4){
            $this->eduplace = $this->db->real_escape_string($eduplace);

            return true;

        }else {
            return false;
        }
    }

    //villkor f??r starttid
    public function setStartDate(string $startdate) : bool {
        
        if(strtotime($startdate)){
            $this->startdate = $this->db->real_escape_string($startdate);

            return true;

        }else {
            return false;
        }

    }

    
    public function setEndDate(string $enddate) : bool {
     
        if(strtotime($enddate)){
            $this->enddate = $this->db->real_escape_string($enddate);

            return true;

        }else {
            return false;
        }

    }

    //getters - h??mtar vardera property
    public function getCoursecode() : string {
        $this->coursecode = $coursecode;
    }

    public function getCoursename() : string {
        $this->cname = $cname;
    }

    public function getProgram() : string {
        $this->program = $program;
    }

    public function getEduPlace() : string {
        $this->eduplace = $eduplace;
    }

    public function getStartDate() : string {
        $this->start = $startdate;
    }

    public function getEndDate() : string {
        $this->end = $enddate;
    }

    // destructor - avsluta databasanslutning
    function __destruct(){
        mysqli_close($this->db);
    }
}
