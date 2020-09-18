<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sigma extends CI_Controller {

	public function __construct(){
         parent::__construct();
         $this->load->model('SigmaModel');  

 	}

	public function index() {
		
		$dep_ciudsssss = json_decode(file_get_contents('https://sigma-studios.s3-us-west-2.amazonaws.com/test/colombia.json'), true);

		$this->load->view('sigma/sigma.php', array('dep_ciud'=>$dep_ciudsssss));
	}

	public function buscarCiudad(){
		$dep_ciud = json_decode(file_get_contents('https://sigma-studios.s3-us-west-2.amazonaws.com/test/colombia.json'), true);
		exit(json_encode(array("ciudades"=>$dep_ciud[$_POST['dpto']])));
		
	}
	public function guardar(){
		$resp= true;
		$resp = $this->SigmaModel->registrar($_POST);
		if($resp){
			$data['status'] = "success";
			$data['message'] = "Su informacion fue recibida satisfactoriamente.";
		} else{
			$data['status'] = "error";
			$data['message'] = "Ocurrio un error inesperado intente de nuevo";
		}
		exit(json_encode($data));
	}
}
