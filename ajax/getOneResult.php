<?php 
include_once '../php/conexion.php';
$c = new mySql();
$id  			=	( isset( $_GET['id'] ) ) 		? $_GET['id'] 		: 0;
if( $id < 1 ){
	echo "error, datos erroneos!!";
	exit();
}
$id 			= addslashes( $id );
$query_ = "select * from usuarios where id = $id "; 
$res = $c->executeQuery( $query_ );
if( $row = $c->fetch( $res ) ){
	$response = array(
			"id" 			=>		$row['id'],
			"user" 			=>		$row['user'],
			"fullName" 		=>		$row['fullName'],
			"description" 	=>		$row['description'],
			"result" 		=>		$row['result']
		);
	echo json_encode($response );
}else{
	echo "error, usuario no encontrado!!";
	exit();
}

?>