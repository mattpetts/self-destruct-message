<?php

class ReadMessage 
{

    public function __construct($db_host, $db_user, $db_pass, $db_dbse){
        $this->host = $db_host;
        $this->dbse = $db_dbse;
        $this->user = $db_user;
        $this->pass = $db_pass;
    }

    public function connect () {

        $host = $this->host;
        $user = $this->user;
        $pass = $this->pass;
        $dbse = $this->dbse;

        $conn = new mysqli($host, $user, $pass, $dbse);

        // Check connection
        if ($conn->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        return $conn;
    }

    public function read () {
        //Get the message
        $msgid = $_GET['id'];

        //Query the db
        $conn = $this->connect();
        $query = "SELECT `message` FROM `messages` WHERE `msg_id` = '$msgid'";
        $result = mysqli_query($conn, $query);

        // Check row count
        $rowcount = mysqli_num_rows($result);

        if ($rowcount > 0) {
            $row = $result->fetch_assoc();
            $message = $row['message'];
    
            // Delete the message from the database
            $descruct = $this->destruct($msgid);
            return $message;
        } else {
            return 'This message is no longer available to read';
        }
    }

    public function destruct($id) {
        $conn = $this->connect();
        $query = "DELETE FROM `messages` WHERE `msg_id` = '$id'";
        $result = mysqli_query($conn, $query);
    }

}

$loc = dirname($_SERVER['DOCUMENT_ROOT']);
require($loc . "/vendor/autoload.php");
$dotenv = Dotenv\Dotenv::createImmutable($loc);
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_dbse = $_ENV['DB_DBSE'];


$process = new ReadMessage($db_host, $db_user, $db_pass, $db_dbse);

if ($_GET['id']) {
    $run = $process->read();
    $message = $run;
} else {
    $message = 'You have not submitted a valid message ID';
}