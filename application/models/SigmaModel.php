<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SigmaModel extends CI_Model {
	
	function __construct() {
        parent::__construct();
    }

    function registrar($data){
    	$data = array(
			    	"name" => $data['nombre'],
			    	"email" => $data['correo'],
			    	"state" => $data['departamento'],
			    	"city" => $data['ciudad'],
			    );

    	return $this->db->insert("contacts", $data);
    }
}
