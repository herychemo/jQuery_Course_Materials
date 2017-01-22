<?php 

class MySql {
	
	private $conexion;		

	function __construct(){
		if(!isset($this->conexion)){ 
			$this->conexion = new mysqli( "localhost","root","","usuarios_jquery" ) or die ( mysql_error() );// Para servidor local
			//$this->conexion = new mysqli( "localhost","root","","usuarios_jquery" ) or die ( mysql_error() );// Para servidor local
		}
	}
	public function executeQuery($query){
		mysqli_set_charset( $this->conexion , 'utf8');
		$result = $this->conexion->query($query);
		return $result;
	}

	public function fetch($res){
		return mysqli_fetch_array($res);
	}
}
?>