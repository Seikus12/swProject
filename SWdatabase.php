<?php
class Database {  
    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "";
    private $_database = "sw1";
     public static $sql;
    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    private function __construct() {
        $this->_connection = new mysqli(
            $this->_host, 
            $this->_username, 
            $this->_password, 
            $this->_database
        );

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                 E_USER_ERROR);
        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
 
    }
//qu
    public function insert($table,$value,$row=null){
		$insert= " INSERT INTO ".$table;
		if($row!=null){
			$insert.=" (". $row." ) ";
		}
		for($i=0; $i<count($value); $i++){
			if(is_string($value[$i])){
				$value[$i]= '"'. $value[$i] . '"';
			}
		}
		$value=implode(',',$value);
		$insert.=' VALUES ('.$value.')';
		$ins=$this->_connection->query($insert);
		if($ins){
			return true;
		}else{
			return false;
		}
	}
      
    public function update($table,$rows,$where){
		 // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for($i = 0; $i < count($where); $i++)
            {
                if($i%2 != 0)
                {
                    if(is_string($where[$i]))
                    {
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }
            $where = implode(" ",$where);


            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++)
            {
                if(is_string($rows[$keys[$i]]))
                {
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
                }
                else
                {
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
                }

                // Parse to add commas
                if($i != count($rows)-1)
                {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;
            $query = $this->_connection->query($update);
            if($query)
            {
                return true;
            }
            else
            {
                return false;
            }
	    
         }
    
    
    
    
    
    public function delete($table,$where=null){
		if($where == null)
            {
                $delete = "DELETE ".$table;
            }
            else
            {
                $delete = "DELETE  FROM ".$table." WHERE ".$where;
            }
			$del=$this->_connection->query($delete);
			if($del){
				return true;
			}else{
				return false;
			}
	}
    
    
    
    
    public function select($table,$row="*",$where=null,$order=null){
		$query='SELECT '.$row.' FROM '.$table;
		if($where!=null){
			$query.=' WHERE '.$where;
		}
		if($order!=null){
			$query.=' ORDER BY ';
		}
		$Result=$this->_connection->query($query);
		return $Result;

	}


}
$db = Database::getInstance();


$ab=$db->select('users',$query='email ' ,'');
//$row=mysqli_fetch_object($ab);
  // echo $row->Fname;
while($db=$ab->fetch_array()){
	
    echo $db[0]
."<br />";
}
//$ins=array('','eslam','zaft','01245637892','eslamzaft1548@gmail.com');
//$db->insert('users',$ins,null);
//$upd=array('Fname'=>'Badshah',
//'Lname'=>'Badshah',
//'email'=>'badshah@gmail.com','password'=>'121213212');
//$db->update('users',$upd,array('id=11'));
//$db = Database::getInstance();
?>
