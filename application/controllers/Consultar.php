<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Consultar extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('MdlPaquetesClientes');
        $this->load->model('MdlClientes');
    }
    public function index()
    {
            $data = array('dato' => 0, 'saldoT' => 0);
            $this->load->view('Templates/header',$data);
            $this->load->view('Consultar/index');
            $this->load->view('Templates/footer'); 
    }
    public function consultartimbres()
    {
        $rfc = $this->input->post('rfc');
        $re = $this->MdlClientes->get_by_clave($rfc);
        if(count($re) > 0)
        {
            $resultado = $this->MdlPaquetesClientes->get_paquetes_vigentes($rfc);
            $dato = array('status' => true, 'data' => $resultado);
            echo json_encode($dato);

        }
        else
        {
            $dato = array('status' => false, 'data' => 'El RFC no existe.');
            echo json_encode($dato);
        }
        
    }
}