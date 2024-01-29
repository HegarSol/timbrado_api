<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class CompraPre extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('MdlClientes');
    $this->load->model('MdlPaquetesClientes');
    $this->load->model('MdlPaquetes');
    $this->load->model('MdlProveedores');
  }

  public function index_post()
  {
    $this->load->library('form_validation');
    $data = array(
      'clave_cliente' => $this->post('clave_cliente'),
      'cantidad' => $this->post('cantidad'),
      'cantidad_comprada' => $this->post('cantidad'),
      'id_paquete' => 0,
      'referencia_compra' => $this->post('referencia'),
      'id_pac' => $this->post('id_pac'),
      'uuid_factura' => $this->post('uuid_factura') ? $this->post('uuid_factura') : '',
      'fecha_vence' => date("Y-m-d",strtotime("+ 1 days", strtotime($this->post('fecha_vence')))) 
    );
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('clave_cliente', 'clave_cliente', 'required');
    $this->form_validation->set_rules(
      'id_pac',
      'id_pac',
      array(
        'valid_id_pac',
        function($id_pac){
          $valid = $this->MdlProveedores->valid_id($id_pac);
          echo var_dump($valid);
          return $valid;
        }
      )
    );
     
    $this->form_validation->set_message('valid_id_pac', 'El PAC especificado no se encuentra registrado');
   
    $id_user = $this->rest->user_id;
    
    $data['fecha_activacion'] = date('Y-m-d');

    $this->MdlPaquetesClientes->add($data);
    //$compra = $this->MdlPaquetesClientes->get_by_id($id_insert);
    // if(!$compra)
    // {
    //   $this->response(['status' => FALSE, 'error' => 'No se pudo insertar el nuevo registro'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    // }
    //$this->response($compra);
   }

}


/* End of file Compra.php */
