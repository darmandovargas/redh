<?php
//define('TIMEZONE', 'America/Bogota');
//date_default_timezone_set('America/Bogota');
include_once('error.php');
// Get sms libs
include_once('sms.php');
include_once('alarm.php');

class db {

    protected $connection;
	protected $query;
	public $query_count = 0;
	
	public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = '', $charset = 'utf8') {
		//echo $dbhost." - ".$dbuser." - ".$dbpass." - ".$dbname;
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if ($this->connection->connect_error) {
                //$this->close(); 
                
                $this->connection = new mysqli('redhidro.org', 'thinkclo_redh', 'redh_rescue', 'thinkclo_redh_rescue');

                $messageText = "ALERTA: La conexión a la base de datos no está respondiendo, llame a su administrador !, ERROR: ".mysqli_connect_errno();

                //echo $messageText."</br>";

                if (!$this->connection->connect_error) {

                    $rescue_message = $this->query("SELECT * FROM redh_rescue_message")->fetchAll();           

                    $datetime1 = new DateTime($rescue_message[0]["rescue_message_date"], new DateTimeZone('America/Bogota'));
                    $datetime2 = new DateTime('', new DateTimeZone('America/Bogota'));
                    //echo $datetime1->format('Y-m-d H:i:s');
                    //echo $datetime2->format('Y-m-d H:i:s');
                    $interval = $datetime1->diff($datetime2);
                    //echo $interval->format('%R%h horas');
                    //echo "</br>";

                    if( $interval->format('%h') >= 8 ){
                        //echo "IF</br>";
                        //$messagesDestiny = "573136355940,573108311240";
						//$messagesDestiny = "573108311240";

						
						$messagesDestiny = "573136355940";                        
                        $response = AltiriaSMS($messagesDestiny, $messageText, "dvargas", false);	                        
						$insert = $this->query('UPDATE redh_rescue_message SET rescue_message_date = ?',$datetime2->format('Y-m-d H:i:s'));                        
						

						//$insert = $this->query('flush hosts');
                        //$insert = $this->query('flush tables');
                        
                        @error_log(PHP_EOL.PHP_EOL.$datetime2->format("Y-m-d H:i:s")." MYSQL SERVER DOWN: ".$messageText.PHP_EOL.$response, 3, "/home/thinkclo/public_html/redh/sms.log");                                    
                    } /*else{
                        echo "ELSE</br>";
                    }   */                
                }
            
                
            $this->close();                
			die('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		$this->connection->set_charset($charset);
	}
	
    public function query($query) {
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->errno) {
				die('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
			$this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }

	public function fetchAll() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
		return $result;
	}

	public function fetchArray() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
		return $result;
	}
	
	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	private function _gettype($var) {
	    if(is_string($var)) return 's';
	    if(is_float($var)) return 'd';
	    if(is_int($var)) return 'i';
	    return 'b';
	}

}
?>