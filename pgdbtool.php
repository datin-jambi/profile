<?php
/*
   pgdbtool.php
   postgresql database tool class

   (c) Iwan abu bakar, 2006
*/
define("NOTFOUND", 100);
date_default_timezone_set('Asia/Jakarta');

class pgDBTool
{
    var $dbconn;			// database connection
    var $host;				// database host
    var $dbname;			// database name
    var $user;				// database user
    var $password;			// database password
    var $connected;			// status connection
    var $datefmt;			// date format for returned query
    var $sqlstr;			// query string
    var $errno;				// error number
    var $errmsg;			// error message
    var $prep_result = array();		// prepare result array
    var $status;			// status of a query
    var $primaryrs;			// primary resultset
    var $rs;				// resultset of a query
   
    // initialized
    function __construct(){
		// $this->host     = "192.168.0.3";
		$this->host     = "103.190.214.224";
		// $this->dbname   = "pgsamsatoldb";
		$this->dbname   = "pgsamsatdb24";
		$this->user     = "samsat";
		$this->password = "samsat";

		// not connected
		$this->dbconn    = false;
		$this->connected = false;
		$this->status = 0;

		// date format
		$this->datefmt = "";

		$this->sqlstr = "";
			$this->primaryrs = false;
			$this->rs = false;
		
		set_error_handler(array($this, "err_handler"), E_WARNING);
    }

    /*
	connect() to connect to database
    */
    public function connect(
	$host="", 
	$dbname="", 
	$user="", 
	$password="",
	$other="" ){
	$dbconn = false;

        $this->connected = false;
		$this->status = -1;

		// reset error
		$this->reset_error();

		if($host != "") $this->host = $host;
		if($dbname != "") $this->dbname = $dbname;
		if($user != "") $this->user = $user;
		if($password != "") $this->password = $password;

		$str = "host=$this->host dbname=$this->dbname user=$this->user " ."password=$this->password $other";
		
		// connect to database
		$dbconn = pg_connect($str);

		// if connected
		if($dbconn) {
			$this->connected = true;
			$this->status = 0;
		}

		$this->dbconn = $dbconn;
    } 

    public function connection_status(){
        if(pg_connection_status($this->dbconn) === PGSQL_CONNECTION_OK)
			return true;
		else
			return false;
    }

    /*
	query($query)

	to execute query
        return: resultset ($rs)
    
    
    // Versi lama 
	public function query($query){

		$this->primaryrs = false;
		$this->rs = false;
		$this->sqlstr = $query;

		// check for connection, if not connected return false
		if(!$this->connected) return false;

		// reset error
		$this->reset_error();

		// execute the query
		$rs = pg_query($this->dbconn, $query);

		$this->rs = $rs;
		$this->primaryrs = $rs;	

		return $rs;
	}
	*/

	// Versi baru untuk mengatasi sql injection
	public function query($query, $params = []) {
		$this->primaryrs = false;
		$this->rs = false;
		$this->sqlstr = $query;

		// check for connection, if not connected return false
		if (!$this->connected) return false;

		// reset error
		$this->reset_error();

		// execute the query with parameters
		if (!empty($params)) {
			$rs = pg_query_params($this->dbconn, $query, $params);
		} else {
			$rs = pg_query($this->dbconn, $query);
		}

		$this->rs = $rs;
		$this->primaryrs = $rs;    

		return $rs;
    }

    /*
		initvars(<table>)
        
        initialize empty array of row from <table>
		return: initialized row
    */
    public function initvars($table){
        $row = false;

		// check for connection, if not connected return false
		if(!$this->connected) return false;
		// reset error
		$this->reset_error();

		$meta = pg_meta_data($this->dbconn, $table);
		foreach($meta as $key => $value){
			$row[$key] = NULL;
		}
	
        return $row;
    }

    /*
	return meta_data of a table or given fields
    */
    public function meta_data($table, $fields=""){
        if(strlen($fields) == 0){
            return pg_meta_data($this->dbconn, $table);
        } else {
            if(!is_array($fields)) $fields = explode(',', $fields);
            
            $data = pg_meta_data($this->dbconn, $table);
            $meta = array();
            foreach($fields as $name){
                $meta[] = $data[$name];
            }
            return $meta;
        }
    }

    /*
	getrow(<query>[, <datefmt>]) 

        get a row from a query
	return: row if true, false otherwise
    */
    public function getrow($query, $datefmt = ""){
		// returned row
		$row = false;

		// check for connection, if not connected return false
		if(!$this->connected) return false;

		// reset error
		$this->reset_error();

		// use global datefmt if not specified
		if($datefmt == "") $datefmt = $this->datefmt;

		// limit to 1 row if not specified
		if(stripos($query, "LIMIT") !== false) $query .= " LIMIT 1";

		$rs = pg_query($this->dbconn, $query);

        if($rs){
			if($row = pg_fetch_assoc($rs)){
				// trim the value
				$i = -1;
				foreach($row as &$v){
					if(!is_null($v)) $v = rtrim($v);
					$i++;
					$type = pg_field_type($rs, $i);
					if($type == "numeric"){
						if(substr($v, -3) == ".00") $v = substr($v, 0, -3);	
					}
				}

				// if date format is specified
				if($datefmt != "") 
					$row = $this->datefmt_row($rs, $row, $datefmt);
					$this->status = 0;
			} else {
				$this->status = NOTFOUND;
            }
        } else {
			$this->status = -1;
		}
		$this->rs = $rs;
		$this->sqlstr = $query;

        return $row;	
    }

    /*
	getrows(<query>, [<datefmt>])

	get all rows from query
	return: all rows if true, false otherwise
    */
    public function getrows($query, $datefmt=""){
		// returned rows
		$rows = false;

		// use global datefmt if not specified
		if($datefmt == "") $datefmt = $this->datefmt;
		$rs = pg_query($this->dbconn, $query);

		if($rs){
			$rows = $this->fetch_all($rs, $datefmt);
			if($rows) 
				$this->status = 0;
			else
				$this->status = NOTFOUND;
		} else {
			$this->status = -1;
		}

		$this->rs = $rs;
		$this->sqlstr = $query;

		return $rows;

    }

    /*
	getvalue(<query>[, <datefmt>]) 

        get a value from a query
	return: a value if true, false otherwise

	use $value === false to check 
    */
    public function getvalue($query, $datefmt=""){
		$value = false;

		// check for connection, if not connected return false
		if(!$this->connected) return false;

		// reset error
		$this->reset_error();

		// use global datefmt if not specified
		if($datefmt == "") $datefmt = $this->datefmt;

		// limit to 1 row if not specified
		if(stripos($query, "LIMIT") !== false) $query .= " LIMIT 1";

		$rs = pg_query($this->dbconn, $query);

		if($rs){
			if($row = pg_fetch_row($rs)){
				$value = $row[0];
				if(!is_null($value)) $value = trim($value);
				if($datefmt != ""){
					//if(!is_null($value) && $value != ""){
					if($value){
						$type = pg_field_type($rs, 0);
						if($type == "date"){
							list($y, $m, $d) = explode("-", $value);
							$value = date($datefmt, 
							mktime(0, 0, 0, $m, $d, $y));
						}
					}
				}
				$this->status = 0;
			} else {
				$this->status = NOTFOUND;
			}
		} else {
			$this->status = -1;
		}

		$this->rs = $rs;
		$this->sqlstr = $query;

		return $value;
    }

    /*
        fetch_row(<rs>, [<datefmt>])

	fetch row with numeric index
        return: a row if true, false otherwise

	example:
	    $rs = $db->query($query);
	    while($row = $db->fetch_row($rs)){
	       ...
	    }
    */
    public function fetch_row($rs, $datefmt=""){
		$row = false;

		// use global datefmt if not specified
		if($datefmt == "") $datefmt = $this->datefmt;

			if($rs){
				if($row = pg_fetch_row($rs)){
					// trim the value
					foreach($row as &$v){
						if(!is_null($v)) $v = trim($v);
					}

					// if datefmt is specified
					if($datefmt != "") 
					$row = $this->datefmt_row($rs, $row, $datefmt);

					$this->status = 0;
				} else {
					$this->status = NOTFOUND;
				}
			} else {
				$this->status = -1;
			}

		return $row;

    }

    /*
        fetch_assoc(<rs>, [<datefmt>])

	fetch row with associative index
        return: a row if true, false otherwise
    */
    public function fetch_assoc($rs, $datefmt=""){

        $row = false;

	// use global datefmt if not specified
	if($datefmt == "") $datefmt = $this->datefmt;

        if($rs){
	    if($row = pg_fetch_assoc($rs)){
	        // trim the value
	        foreach($row as &$v){
		    if(!is_null($v)) $v = trim($v);
                }

	        // if datefmt is specified
		if($datefmt != "") $row = $this->datefmt_row($rs, $row, $datefmt);
		$this->status = 0;
	    } else {
		$this->status = NOTFOUND;
            }
        } else {
	    $this->status = -1;
        }

        return $row;

    }

    /*
        fetch_all(<rs>, [<datefmt>])

	fetch all rows from resultset
        return: rows if true, false otherwise

	example:
		$rs = $db->query($query);
		if($rows = $db->fetch_all($rs)){
		    ...
		}
    */
    public function fetch_all($rs, $datefmt=""){
	
	$rows = false;

	// use global datefmt if not specified
	if($datefmt == "") $datefmt = $this->datefmt;

        if($rs){
	    if($rows = pg_fetch_all($rs)){
	        foreach($rows as &$row){
		    foreach($row as &$v) if(!is_null($v)) $v = trim($v);
		    if($datefmt != "") 
			$row = $this->datefmt_row($rs, $row, $datefmt);
		}
		$this->status = 0;
	    } else {
	        $this->status = NOTFOUND;
	    }
        } else {
	    $this->status = -1;
        }

	return $rows;

    }


    /*
	row2str (<row>[, <nullstr>[, <sep>]) 
           to create string delimited with <sep>
    */
    public function row2str($row, $nullstr="", $unlsep="|"){
	$str = "";
	$sep = "";

	foreach($row as $v){
	    if(is_null($v)) $v = $nullstr;
/*
	    else
		if(substr($v, -3) == ".00") $v = substr($v, 0, -3);
*/
	    $str .= $sep . rtrim($v);
	    $sep  = $unlsep;
	}

        return $str;
    }

    /*
	rows2str
    */
    public function rows2str($rows, $nullstr="", $rowsep="\n", $colsep="|"){

	$str = "";
        $sep = "";

        foreach($rows as $row){
	    $str .= $sep . $this->row2str($row, $nullstr, $colsep);
	    $sep  = $rowsep; 
	}

	return $str;
    }

    /*
	date_part(<part>, <date>[, <datefmt>])
            to get part of a date
	
	part= 'year', 'month', 'day'
    */
    public function date_part($part, $v, $datefmt=""){
	$date_part = 0;

        if($datefmt == "") $datefmt = $this->datefmt;
        if($v){
	    if($datefmt == "")
		$date = date_parse_from_format("Y-m-d", $v);
            else
		$date = date_parse_from_format($datefmt, $v);

	    $date_part = $date[$part];
	}
        return $date_part;
    }

    // get year fraction of a date
    public function year($date, $datefmt=""){
	return $this->date_part('year', $date, $datefmt);
    }

    // get month fraction of a date
    public function month($date, $datefmt=""){
	return $this->date_part('month', $date, $datefmt);
    }

    // get day fraction of a date
    public function day($date, $datefmt=""){
	return $this->date_part('day', $date, $datefmt);
    }

    /*
  	prep_query(<query>, <stmtname>);
	    to prepare a query

	return: <stmtname> if ok, false otherwise;
    */
    public function prep_query($query, $stmtname=""){

	$retcode = false;
	
	// check for connection, if not connected return false
	if(!$this->connected) return false;

	// reset error
	$this->reset_error();

	if($stmtname == "") $stmtname = "prep_query";

	$rs = pg_prepare($this->dbconn, $stmtname, $query);
	if($rs){
	    $retcode = $stmtname;
	    $this->status = 0;
	}

	return $retcode;
    }

    /*
	prep_insert(<tabel>, [<fields>], [<stmtname>])
	    to prepare an insert 

        return: <stmtname> if ok, false otherwise;
    */
    public function prep_insert($tabel, $fields="*", $stmtname=""){
        
        $retcode = false;
        $insert  = "";

	// check for connection, if not connected return false
	if(!$this->connected) return false;

	// reset error
	$this->reset_error();

        // get fields descriptions
	$query = "SELECT $fields FROM $tabel WHERE (1=2)";

	$rs = pg_query($this->dbconn, $query);
	if($rs){
	    $str = "";
	    $sep = "";

	    $num_fields = pg_num_fields($rs);
	    for($i = 0; $i < $num_fields; $i++){
		$str .= $sep . "$" . ($i+1);
	        $sep  = ", ";
	    }

	    $insert = "INSERT INTO $tabel
			VALUES ( $str )";

	    if($stmtname == "") $stmtname = "sql_insert_" . $tabel;

	    // save current resource
	    $this->prep_result[$stmtname] = $rs;

	    $rs = pg_prepare($this->dbconn, $stmtname, $insert);
	    if($rs) {
		$retcode = $stmtname;
		$this->status = 0;
	    } else {
		$this->prep_result[$stmtname] = false;
		$retcode = false;
		$insert  = "";
	    }
	} 

	$this->sqlstr = $insert;
        return $retcode;
    }

    /*
	prep_update(<tabel>, [<fields>], [<where>], [<stmtname>])
	    to prepare an update

	return: <stmtname> if ok, false otherwise 
    */
    public function prep_update($tabel, $fields="*", $where="", $stmtname=""){

        $retcode = false;
        $update  = "";

	// check for connection, if not connected return false
	if(!$this->connected) return false;

	// reset error
	$this->reset_error();

	// get fields descriptions
	$query = "SELECT $fields FROM $tabel WHERE (1=2)";

	$rs = pg_query($this->dbconn, $query);

	if($rs){
	    $str = "";
	    $sep = "";

	    $num_fields = pg_num_fields($rs);
	    for($i = 0; $i < $num_fields; $i++){
	        $name = pg_field_name($rs, $i);
		$str .= $sep . "$name = $" . ($i+1);
	        $sep  = ", ";
	    }

	    $update = "UPDATE $tabel
			 SET $str";

	    if($where) $update .= " WHERE $where";
	   
	    if($stmtname == "") $stmtname = "sql_update_" . $tabel;

	    // save current resource
	    $this->prep_result[$stmtname] = $rs;

	    $rs = pg_prepare($this->dbconn, $stmtname, $update);
	    if($rs) {
		$retcode = $stmtname;
		$this->status = 0;
	    } else {
		$this->prep_result[$stmtname] = false;
		$retcode = false;
		$update  = "";
	    }
	}

	$this->sqlstr = $update;
        return $retcode;
    }

    /*
	execute(<stmt>, <values>, [<datefmt>], [<sep>] )
	    to execute a prepared statement

	return: result resource id
    */
    public function execute($stmtname, $values, $datefmt="", $sep="|"){

	// check for connection, if not connected return false
	if(!$this->connected) return false;

	if(!$stmtname) return false;

	// reset error
	$this->reset_error();

	if(!is_array($values)){
	    $values = explode($sep, $values);
	    foreach($values as &$v) {
	        $v = trim($v);
		if($v == "") $v = NULL;
	    }
        }

	// kalo date formatnya beda
	if($datefmt != ""){
	    // get prepare resource
	    $rs = $this->prep_result[$stmtname];

	    $i = -1;
	    foreach($values as &$v){
		$i++;
		if(!is_null($v) && $v != ""){
                    $type = pg_field_type($rs, $i);
		    if($type == "date"){
			$date = date_parse_from_format($datefmt, $v);
			$v = date('Y-m-d', mktime(0, 0, 0, 
				$date['month'], $date['day'], $date['year']));
		    }
		}
	    }
        }

	// you might need this!
	$this->rs = false;

	$rs = pg_execute($this->dbconn, $stmtname, $values);
	if($rs) {
	    $this->status = 0;
	    $this->rs = $rs;
	    return true;
	} else {
	    return false;
        }
    }

    public function insert($tabel, $values, $fields="*", $datefmt=""){
        	
	$stmt = $this->prep_insert($tabel, $fields);
	if($stmt){
	    return $this->execute($stmt, $values, $datefmt);
	}

	return false;
    }

    public function update($tabel, $fields, $values, $where="", $datefmt=""){
	
	$stmt = $this->prep_update($tabel, $fields, $where);
        if($stmt){
	    return $this->execute($stmt, $values, $datefmt);
        }
  
        return false;
    }

    /*
        sql_insert($tabel, $data);
        insert in old fashion way!
    */
    public function sql_insert($tabel, $values, $datefmt=""){
        
        if(!is_array($values)) $values = explode("|", $values);
        
        $meta = pg_meta_data($this->dbconn, $tabel);
        $field = array();
        
        $insert = "";
        $sep = "";
        
        $i = -1;
        foreach($meta as $name => $data){
            $field[$name] = $data['type'];
            $i++;
            
            $value = "";
            if(isset($values[$name])){
                $value = is_null($values[$name]) ? "NULL" : $values[$name];
            } else {
		if(isset($values[$i])){
                    $value = is_null($values[$i]) ? "NULL" : $values[$i];
		}
            }
            if(strlen($value) == 0) $value  = "NULL";
            $value = trim($value);
            
            switch($data['type']){
                case 'varchar':
                case 'bpchar':
                case 'text':                                    
                    if($value == "NULL"){
                        $insert .= $sep . "NULL";
                    } else {
                        $insert .= $sep . "'$value'";
                    }
                    $sep = ", ";
                    break;
                    
                case 'date':
                    if($value == "NULL"){
                        $insert .= $sep . "NULL";
                    } else {
                        if($value == "NOW" or $value == "TODAY") $value = date('Y-m-d');
			// if date format is specified
                        if($datefmt != ""){
                            $date = date_parse_from_format($datefmt, $value);
                            $value = date('Y-m-d', mktime(0, 0, 0,
                                $date['month'], $date['day'], $date['year']));
		        }
                        $insert .= $sep . "'$value'";
                    }
                    $sep = ", ";
                    break;
                    
                case 'time':
                    if($value == "NULL"){
                        $insert .= $sep . "NULL";
                    } else {
                        if($value == "NOW") $value = date('H:i:s');
                        $insert .= $sep . "'$value'";
                    }
                    $sep = ", ";
                    break;
                    
                case 'timestamp':                
                    if($value == "NULL"){
                        $insert .= $sep . "NULL";
                    } else {
                        if($value == "NOW" or $value == "TODAY") $value = date('Y-m-d H:i:s');
                        $insert .= $sep . "'$value'";
                    }
                    $sep = ", ";
                    break;
                    
                default:
                    if($value == "NULL"){
                        $insert .= $sep . "NULL";
                    } else {
                        $insert .= $sep . $value;
                    }
                    $sep = ", ";
                    break;                    
            }
        }
        
        $insert = "INSERT INTO $tabel VALUES ( $insert )";
        
        $rs = $this->query($insert);
        if($rs){ 
            $this->last_oid = pg_last_oid($rs);
            return true;
        } else {
            return false;
        }
    }

    /*
	change date format of a row from a query to specified format
    */
    public function datefmt_row($rs, $row, $datefmt){

        $i = -1;
        foreach($row as $fname => &$v){
	    $i++;

	    if(!is_null($v)){
	        $type = pg_field_type($rs, $i);
	        if($type == "date"){
	            if(!is_null($v)){
	                list($y, $m, $d) = explode("-", $v);
	                $v = date($datefmt, 
			            mktime(0, 0, 0, $m, $d, $y));
		    }
	        }
	    }
        } 

        return $row;

    }

    private function reset_error(){
	$this->status = 0;
	$this->errno  = 0;
        $this->errmsg = "";
    }

    private function set_error($errno, $errmsg){
	$this->errno  = $errno;
        $this->errmsg = $errmsg;
	$this->status = -abs($errno);
    }
 
    private function err_handler($errno, $errmsg){
	$this->errno  = $errno;
        $this->errmsg = $errmsg;
        $n = strpos($errmsg, "ERROR:");
	if($n !== false) $this->errmsg = substr($errmsg, $n);
	$this->status = -abs($errno);
    }

}

?>
