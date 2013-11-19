<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, SOAPAction");
header("Access-Control-Allow-Methods : POST,GET,OPTIONS");
require_once"lib/nusoap.php";

$server=new soap_server();

$url="urn:servicioweb";

$server->configureWSDL("servicioweb",$url);

$entrada=array("usuario"=>"xsd:string","clave"=>"xsd:string");

$server->wsdl->addComplexType(
	'informacion',
	'ComplexType',
	'struct',
	'all',
	'',
	array(
		'salida'=>array('name'=>'salida','type'=>'xsd:string')
		)
	);

$respuesta=array('respuesta'=>'tns:informacion');

$server->register(
	'informacionUsuario',
	$entrada,
	$respuesta,
	$url
	);

$entradaSol=array('id'=>'xsd:string');

$server->wsdl->addComplexType(
	'soluciones',
	'ComplexType',
	'struct',
	'all',
	'',
	array(
		'salidaSol'=>array('name'=>'salidaSol','type'=>'xsd:string')
		)
	);

$respuestaSol=array('respuestaSol'=>'tns:soluciones');

$server->register(
	'informacionSoluciones',
	$entradaSol,
	$respuestaSol,
	$url
	);

$entradaSal=array('tarjeta'=>'xsd:string');

$server->wsdl->addComplexType(
	'saldo',
	'ComplexType',
	'struct',
	'all',
	'',
	array(
		'salidaSal'=>array('name'=>'salidaSal','type'=>'xds:string')
		)
	);
$respuestaSal=array('respuestaSal'=>'tns:saldo');

$server->register(
	'consultarSaldo',
	$entradaSal,
	$respuestaSal,
	$url
	);


function informacionUsuario($usuario,$clave){
	require_once"class/Conexion.php";

	$obj=new Conexion();

	$respuesta = $obj->validaUsuario($usuario,$clave);

	return new soapval('return','xsd:string',json_encode($respuesta));
}

function consultarSaldo($numeroTarjeta){
	require_once"class/Conexion.php";
	$obj=new Conexion();

	$movimientos = $obj->consultarSaldo($numeroTarjeta);

	return new soapval('return','xsd:string',json_encode($movimientos));
}


function informacionSoluciones($id){
	require_once"class/Conexion.php";
	$obj=new Conexion();
	$soluciones = $obj->informacionSoluciones($id);

	return new soapval('return','xsd:string',json_encode($soluciones));

}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)?file_get_contents('php://input'):'';
$server->service($HTTP_RAW_POST_DATA);

?>