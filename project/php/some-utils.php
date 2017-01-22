<?php 

require_once 'conexion.php';

class Utils extends MySql{
	private $LIMIT_ = 11;

	function __construct(){
		parent::__construct();
	}
	function getNextIdFor($table, $id='id'){
		$res = $this->executeQuery("select ifnull( max($id) , 0)+1 as next from $table ");
		$row = $this->fetch( $res );
		return $row['next'];
	}
	function rowAmountFor($table, $where=''){
		$res = $this->executeQuery("select count(*) as total from $table " . ( ($where != '')? 'where '.$where : '' )  );
		$row = $this->fetch( $res );
		return $row['total'];
	}

	function resetDB(){
		$this->executeQuery("delete from msgs");
	}
	function getMsgs($encoded=0){
		$res = $this->executeQuery("select id, msg, uri, user, ip, time_ from msgs order by id");
		$msgs = array();
		while( $row = $this->fetch( $res ) )
			array_push( $msgs , array( 
									'id' 	=> $row['id'],
									'msg' => $row['msg'],
									'uri' => $row['uri'],
									'user' => $row['user'],
									'ip' => $row['ip'],
									'time_' => $row['time_']
								));
		return ($encoded)? $this->json_stringify( $msgs ) : $msgs; ;
	}
	function json_stringify( $value ){
		return json_encode( $value, JSON_UNESCAPED_SLASHES );
	}
	function new_message( $msg_data ){
		if( ( !isset( $msg_data['msg'] ) || $msg_data['msg'] == '' ) && ( !isset( $msg_data['uri'] ) || $msg_data['uri'] == '' ) )
			return false;
		$msg = $msg_data['msg'];
		$uri = $msg_data['uri'];
		$user = ( !isset( $msg_data['user'] ) || $msg_data['user'] == '' ) ?'unknow' : $msg_data['user'] ;
		$ip = ( !isset( $msg_data['ip'] ) || $msg_data['ip'] == '' ) ? 'unknow' : $msg_data['ip'] ;
		$this->executeQuery( "Insert into msgs values (null, '$msg', '$uri', '$user', '$ip', now() )" );
		$x = $this->rowAmountFor("msgs") - $this->LIMIT_;
		if( $x > 0 )
			$this->killExtra( $x );
		return true;
	}

	function killExtra( $extra ){
		$res = $this->executeQuery("select id from msgs order by id limit $extra");
		$ids = array();
		while( $row = $this->fetch( $res ) )
			array_push($ids, $row['id']);
		$this->executeQuery("delete from msgs where id = " . implode(' or id = ', $ids));
	}



}

?>