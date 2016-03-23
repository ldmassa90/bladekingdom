<?php 
require("/configuration.php");
require("/ClassText.php");

class Connection extends Text{
	
	private $link;
    private $host, $username, $password, $database;
	
    public function __construct(){
    	
		global $db_host, $db_user, $db_pass, $db_name;
		
        $this->host        = $db_host;
        $this->username    = $db_user;
        $this->password    = $db_pass;
        $this->database    = $db_name;

        $this->link = mysqli_connect($this->host, $this->username, $this->password, $this->database) or die(mysqli_error());

        return true;
    }
	
    public function query($query) {

        $result = $this->link->query($query);

		if($this->link->affected_rows){
			return $result;
		}else{

			if(isset($result->error)){
				echo "ERRORE: ". $query ."".$this->link->error;
				return false;
			}

			return $result;
		}
        
    }

    public function __destruct() {
        mysqli_close($this->link) or die("There was a problem disconnecting from the database.");
    }

}

?>