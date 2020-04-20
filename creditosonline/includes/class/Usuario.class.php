<?php
class Usuario{
	private $usuario_id = '';
	private $usuario = '';
	private $nombre = '';
	private $email = '';
	private $rol_id = '';
	private $rol = '';
	private $empresa ='';
	private $empresa_id ='';

	

	public  function __construct($usuario_id = ''){
		$query = new Query();
		$valida_acceso_general = false;
		
		if($usuario_id == ''){
			$this->usuario_id = $_SESSION['usuaid_sahilices'];
			$this->nombre = $_SESSION['nombre_sahilices'];
			$this->usuario = $_SESSION['usua_sahilices'];
			$this->email = $_SESSION['email_sahilices'];
			$this->rol_id = $_SESSION['idroll_sahilices'];
			$this->rol = $_SESSION['refroll_sahilices'];			
		}

		// funciones para buscar mas datos del usuario

	}

	public function getUsuarioId()
	{
		return $this->usuario_id;
	}

	public function getUsuario()
	{
		return $this->usuario;
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getRol()
	{
		return $this->rol;
	}

	public function getRolId()
	{
		return $this->rol_id;
	}

	public function getEmpresa()
	{
		return $this->empresa;
	}
	public function getEmpresaId()
	{
		return $this->empresa_id;
	}


	public function generaTokenContrato()
	{

	}

	public function generaTokenDispocision()
	{

	}



	
}