<?php 

include_once '../php/some-utils.php';

$c = new Utils();

$finalName = '';
$extension = '';
$isThereFile = false;
$isOK = false;

if( isset( $_FILES['file'] ) && strlen( $_FILES['file']['name'] ) > 0 ){
	$nombre_archivo = $_FILES['file']['name'];

	$temp  = explode('.', $nombre_archivo);
	$extension = strtolower( $temp[ count($temp) -1 ] ) ; 

	$name = getdate()[0];
	$finalName = "_{$name}.{$extension}";
	$isThereFile = true;
}elseif( !isset( $_POST['msg'] ) ){
	echo "err|Archivo demasiado grande, intente con uno más ligero...";
	exit();
}
$msg_data = array( 
				'user'  =>	( isset( $_POST['user'] ) ) ? $_POST['user'] : '',
				'msg'	=>	( isset( $_POST['msg'] ) )  ? $_POST['msg']  : '',
				'uri'	=>	( isset( $_POST['uri'] ) )  ? $_POST['uri']  : '',
				'ip'	=>	$_SERVER['REMOTE_ADDR']
			);

if( $isThereFile ){
	if( $extension == 'png' || $extension == 'jpg' || $extension == 'gif' ){
		$finalName = "jQueryChat_".substr($msg_data['user'], 0, 2).$finalName;
		move_uploaded_file($_FILES['file']['tmp_name'], "../imgs/uploaded/".$finalName);
		$msg_data['uri'] = $finalName;
		$isOK = true;
	}else{
		echo "err|Extensión Invalida...";
		exit();
	}
}else
	$isOK = true;

if( $isOK ){
	$res = $c->new_message( $msg_data );
	if( $res )
		echo "res|ok";
	else
		echo "err|error";
}else
	echo "err|error";
	
?>