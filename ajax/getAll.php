<?php 
include_once '../php/conexion.php';
$c = new mySql();
$query_ = "select * from usuarios "; 
$res = $c->executeQuery( $query_ );
$response = array();
while( $row = $c->fetch( $res ) ){
	$r = array(
			"id" 			=>		$row['id'],
			"user" 			=>		$row['user'],
			"fullName" 		=>		$row['fullName'],
			"description" 	=>		$row['description'],
			"result" 		=>		$row['result']
		);
	array_push($response, $r);
}
echo json_encode($response );
?>