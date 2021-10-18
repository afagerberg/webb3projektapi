<?php
//DT173G Projekt Alice Fagerberg
class Webpage {

    //variabler
    private $db;
    private $pageurl;
    private $title;
    private $pagedescription;

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
     * @param string $coursecode
     * @param string $cname
     * @param string $progression
     * @param string $courseplan
     */
    public function addWebpage(string $pageurl, string $title, string $pagedescription) :bool{
        
        
        $this->pageurl = $pageurl;
        $this->title = $title;
        $this->pagedescription = $pagedescription;

        // prepeare statements
        $stmt = $this->db->prepare("INSERT INTO webpages (pageurl, title, pagedescription) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->pageurl, $this->title, $this->pagedescription);

        // execute statement
        if ($stmt->execute()) {
            return true;
        }else {
            return false;
        }
        

        $stmt->close();

    }

    /**
     * Get all courses
     * @return array
     */
    public function getAllWebpages() : array {
        $sql = "SELECT * FROM webpages ORDER BY title;";
        $result = $this->db->query($sql);

        // retirn associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * get course by id
     * @param int $id
     * @return array
     */
    public function getwebpagebyId(int $id) : array {
        $id = intval($id);
        
        $sql = "SELECT * FROM webpages WHERE id=$id";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    /*blaha
     */
    function updateWebpage(int $id, string $pageurl, string $title, string $pagedescription) : bool {

        $this->pageurl = $pageurl;
        $this->title = $title;
        $this->pagedescription = $pagedescription;
        $id = intval($id);

        $stmt = $this->db->prepare("UPDATE webpages SET pageurl=?, title=?, pagedescription=? WHERE id=$id;");
        $stmt->bind_param("sss",$this->pageurl, $this->title, $this->pagedescription);

        // execute statement
        if ($stmt->execute()) {
            return true;
        }else {
            return false;
        }
        

        $stmt->close();

    }

    /**
     * delete a course by id
     * @param int $id
     * return boolean
     */
    public function deleteWebpage(int $id) :bool {
        $id = intval($id);

        $sql = "DELETE FROM webpages WHERE id=$id;";
        $result = $this->db->query($sql);

        return $result;
    }


    function setPageurl(string $pageurl) : bool {
        if(filter_var($pageurl, FILTER_VALIDATE_URL)) {
            $this->pageurl = $this->db->real_escape_string($pageurl);// säkra strängen
            return true;
        }else {
            return false;
        }
    }

    function setTitle(string $title) : bool {
        if(strlen($title) > 4) {
            $this->title = $this->db->real_escape_string($title);// säkra strängen
            return true;
        }else {
            return false;
        }
    }

    function setDescription(string $pagedescription) : bool {
        if(strlen($pagedescription) > 4) {
            $this->pagedescription = $this->db->real_escape_string($pagedescription);// säkra strängen
            return true;
        }else {
            return false;
        }
    }

    function getPageurl() :string {
        return $this->pageurl;
    }
    function getTitle() :string {
        return $this->title;
    }
    function getDescription() :string {
        return $this->pagedescription;
    }

    // destructor - avsluta databasanslutning
    function __destruct(){
        mysqli_close($this->db);
    }
}