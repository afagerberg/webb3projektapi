<?php
//DT173G Projekt Alice Fagerberg
class Education {

    //variabler
    private $db;
    private $courseid;
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
     * Add course
     * @param string $courseid
     * @param string $cname
     * @param string $program
     * @param string $eduplace
     * @param string $startdate
     * @param string $enddate
     */
    public function addEducation(string $courseid, string $cname, string $program, string $eduplace, string $startdate, string $enddate) :bool{

        $this->courseid = $courseid;
        $this->cname = $cname;
        $this->program = $program;
        $this->eduplace = $eduplace;
        $this->startdate = $startdate;
        $this->enddate = $enddate;

            // prepeare statements
            $stmt = $this->db->prepare("INSERT INTO completedstudies(courseid, cname, program, eduplace, startdate, enddate) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $this->courseid, $this->cname, $this->program, $this->eduplace, $this->startdate, $this->enddate);

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
     * get course by id
     * @param string $courseid
     * @return array
     */
    public function getEducationByCourseid(string $courseid) : array {
        
        $sql = "SELECT * FROM completedstudies WHERE courseid=$courseid";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    /**
     * update education 
     * @param string $courseid
     * @param string $cname
     * @param string $program
     * @param string $eduplace
     * @param string $startdate
     * @param string $enddate
     * return boolean
     */
    function updateEducation(string $courseid, string $cname, string $program, string $eduplace, string $startdate, string $enddate) : bool {

        $this->courseid = $courseid;
        $this->cname = $cname;
        $this->program = $program;
        $this->eduplace = $eduplace;
        $this->startdate = $startdate;
        $this->enddate = $enddate;

            $stmt = $this->db->prepare("UPDATE completedstudies SET cname=?, program=?, eduplace=?, startdate=?, enddate=? WHERE courseid=$courseid;");
            $stmt->bind_param("sssss", $this->cname, $this->program, $this->eduplace, $this->startdate, $this->enddate);

            // execute statement
            if ($stmt->execute()) {
                return true;
            }else {
                return false;
            }
            

            $stmt->close();

    }

    /**
     * delete an education by course id
     * @param string $courseid
     * return boolean
     */
    public function deleteEducation(string $courseid) :bool {

        $sql = "DELETE FROM completedstudies WHERE courseid=$courseid;";
        $result = $this->db->query($sql);

        return $result;
    }

    // setters för all properties

    //villkor för kurskod
    public function setCourseid(string $courseid) : bool {
        if(strlen($courseid) > 4){
            $this->courseid = $this->db->real_escape_string($courseid);

            return true;

        }else {
            return false;
        }
    }

    //villkor för kursnamn
    public function setCoursename(string $cname) : bool {
        if(strlen($cname) > 4){
            $this->cname = $this->db->real_escape_string($cname);

            return true;

        }else {
            return false;
        }
    }

    //villkor för utbildningsprogram
    public function setProgram(string $program) : bool {
        if(strlen($program) > 4){
            $this->program = $this->db->real_escape_string($program);

            return true;

        }else {
            return false;
        }
    }

    //villkor för utbildningsprogram
    public function setEduPlace(string $eduplace) : bool {
        if(strlen($eduplace) > 4){
            $this->eduplace = $this->db->real_escape_string($eduplace);

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

    
    public function setEndDate(string $enddate) : bool {
     
        if(strtotime($enddate)){
            $this->enddate = $this->db->real_escape_string($enddate);

            return true;

        }else {
            return false;
        }

    }

    //getters - hämtar vardera property
    public function getCourseid() : string {
        $this->courseid = $courseid;
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
