<?php
class Conexion{

	private $usuario;
	private $movimientos;
	private $soluciones;

	public function __construct(){
		$this->Conectar();
		$this->usuario=array();
		$this->movimientos=array();
		$this->soluciones=array();
	}	

	public function Conectar(){
		$con = mysql_connect("localhost","root",'root');
		mysql_select_db("webservice");
		return $con;
	}

	public function validaUsuario($usuario,$clave){
		$sql="
			SELECT *
			FROM usuarios
			WHERE usuario='{$usuario}'
			AND clave='{$clave}'
			";
		$res=mysql_query($sql);

		if($row=mysql_fetch_assoc($res)){
			$this->usuario=$row;
		}

		return !empty($this->usuario)?$this->usuario:'no';
	}

	public function consultarSaldo($numeroTar){
		$sql="
			SELECT *
			FROM movimientos
			WHERE tarjeta=$numeroTar
		";
		$res=mysql_query($sql);

		while($row=mysql_fetch_assoc($res)){
			$this->movimientos[]=$row;
		}

		return $this->movimientos;
	}

	public function informacionSoluciones($id){
		$sql="
			SELECT sol.id,user.usuario,sol.solucion
			FROM soluciones sol
			JOIN usuarios user ON sol.usuario=user.id
			WHERE sol.usuario=$id
		";
		$res=mysql_query($sql);

		while($row=mysql_fetch_assoc($res)){
			$this->soluciones[]=$row;
		}

		return $this->soluciones;
	}

}

?>