<?php 
include_once '../php/conexion.php';
$c = new mySql();
$user  			=	( isset( $_POST['user'] ) ) 		? $_POST['user'] 		: '';
$fullName		=	( isset( $_POST['fullName'] ) )  	? $_POST['fullName']  	: '';
$description	=	( isset( $_POST['description'] ) )  ? $_POST['description']	: '';
if( $user == '' or $fullName == '' or $description == ''  ){
	echo "error faltan datos!!";
	exit();
}
$user 			= addslashes( $user );
$fullName 		= addslashes( $fullName );
$description 	= addslashes( $description );
$result			= ( ( rand(0,2*strlen($description)) ) > 20 ) ? 'true' : 'false';
$query_ = "select id from usuarios where user = '$user' "; 
$res = $c->executeQuery( $query_ );
if( $c->fetch( $res ) ){
	echo "error, usuario ya existe!!";
	exit();
}
$c->executeQuery("Insert into usuarios values(null, '$user', '$fullName', '$description', $result )");
$res = $c->executeQuery($query_);
if( $row = $c->fetch( $res ) )
	echo "ok. Tu nuevo ID es " . $row['id'] . ", Ahora consulta resultados... ";
else
	echo "Error...";
?>