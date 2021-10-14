<?php
//Anslut till databas
include("configuration/config.php");

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);

if($db->connect_errno > 0){
    die('fel vid anslutning [' . $db->connect_error . ']');
}

/*SQL fråga skapa tabell kurser
$sql = "DROP TABLE IF EXISTS completedstudies;
    CREATE TABLE completedstudies(
    courseid VARCHAR(255) PRIMARY KEY,
    cname VARCHAR(255) NOT NULL,
    program VARCHAR(255) NOT NULL,
    eduplace VARCHAR(255) NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL
    );";

$sql .= "
    INSERT INTO completedstudies(courseid, cname, program, eduplace, startdate, enddate) VALUES('DT057G', 'Webbutveckling I', 'Webbutveckling', 'Mittuniversitetet','2020-08-31','2020-11-02');
    INSERT INTO completedstudies(courseid, cname, program, eduplace, startdate, enddate) VALUES('DT084G', 'Introduktion till programmering i JavaScript', 'Webbutveckling', 'Mittuniversitetet','2020-08-30','2020-11-02');
";
*/
$sql = "DROP TABLE IF EXISTS workexperience;
    CREATE TABLE workexperience(
    jobid INT(11) PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    workplace VARCHAR(255) NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL
    );";

$sql .= "
    INSERT INTO workexperience(title, workplace, startdate, enddate) VALUES('Praktikant grafisk designer', 'Frosting Kommunikationsbyrå', '2019-02-20', '2019-04-20');
    INSERT INTO workexperience(title, workplace, startdate, enddate) VALUES('Trafikinformatör timanställning', 'Trafikverket', '2016-06-13', '2020-08-16');
";
/*
$sql = "DROP TABLE IF EXISTS webpages;
    CREATE TABLE webpages(
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    pageurl VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    pagedescription VARCHAR(1200) NOT NULL
    );";

$sql .= "
    INSERT INTO webpages(pageurl, title, pagedescription) VALUES('https://hojta.afagerberg.se/', 'Hojta', 'En blogg där du kan skapa en användare samt skapa och lägga ut inlägg');
    INSERT INTO webpages(pageurl, title, pagedescription) VALUES('https://gelatostugan.afagerberg.se/', 'Gelatostugan', 'Utforma en webbplats för ett fiktivt företag, huvudsyftet var att skapa god typografi');
";*/

echo "<pre>$sql</pre>";

if($db->multi_query($sql)) {
    echo "Tabeller installerade.";
}else {
    echo "Fel vid installation av tabeller.";
}