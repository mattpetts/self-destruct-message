<?php

class ProcessMessage 
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

    public function random()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public function runProcess () {
        // Set some vars
        $recipEmail = $_POST['email'];
        $message = $_POST['message'];

        //generate an ID
        $rand = $this->random();

        //Insert into the database
        $conn = $this->connect();
        $query = "INSERT INTO messages (`msg_id`, `message`) VALUES ('$rand', '$message')";
        $result = mysqli_query($conn, $query);

        //Generate a link
        $link = $_SERVER['HTTP_HOST'] . '/view.php?id=' . $rand;

        // SendMail
        $mail = $this->sendMail($recipEmail, $link);

    }

    public function sendMail($email, $link) {

        $msg = "To view your one time message, click this link: <a href='" . $link . "' target='_blank'>here</a>";

        mail($email,"You have a new self-descructing message",$msg);
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

$process = new ProcessMessage($db_host, $db_user, $db_pass, $db_dbse);

if ($_POST['message']) {
    $run = $process->runProcess();
    echo $run;
}