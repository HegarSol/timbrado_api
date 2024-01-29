<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class Cliente extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('MdlClientes');
    $this->load->model('MdlErrores');
  }

  /**
   * Agrega un nuevo cliente al catalogo de clientes relacionados con el usuario
   * de API KEY proporcionada
   * 
   * @param string $clave     Es la clave identificadora del cliente
   * @param string $rfc       Es el RFC del cliente con que va a poder timbrar los comprobantes
   * @param string $nombre    Nombre o Razon social del cliente
   * @param int    $activo    Indica si el cliente se encuentra activo para el uso del timbrado
   */
  public function index_post()
  {
    $this->load->library('form_validation');
    $data = array(
      'clave' => $this->post('clave'),
      'rfc' => $this->post('rfc'),
      'nombre' => $this->post('nombre'),
      'activo' => $this->post('activo') ? $this->post('activo') : 1,
      'Notificar' => $this->post('notificar'),
      'email' => $this->post('email'),
      'id_user' => $this->rest->user_id
    );
    $this->form_validation->set_data($data);
    $this->form_validation->set_rules('clave', 'clave', 'required');
    $this->form_validation->set_rules('rfc', 'rfc', 'required');
    $this->form_validation->set_rules('nombre', 'nombre', 'required');
    $this->form_validation->set_rules('activo', 'activo', 'in_list[0,1]');
    $this->form_validation->set_rules('Notificar', 'Notificar', 'required');
    $this->form_validation->set_rules('email','email','required');
    if($this->form_validation->run() == FALSE)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => $this->form_validation->error_array()
        ],
        REST_Controller::HTTP_BAD_REQUEST
      );
    }
    if($this->MdlClientes->add($data) == FALSE)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'La clave ya se encuentra en uso'
        ],
        REST_Controller::HTTP_CONFLICT
      );
    }
    $newCliente = $this->MdlClientes->get_by_clave($data['clave']);
    $this->response($newCliente);
  }

  /**
   * Obtiene todos los clientes asignados al ususario o solo el que se especifica
   * 
   * @param string $clave   Indica la clave del cliente que se desea consultar, si se omite se regresa el listado
   */
  public function index_get()
  {
    $clave = $this->get('clave');
    $id_user = $this->rest->user_id;
    if($clave)
    {
      $cliente = $this->MdlClientes->get_by_clave_user($clave, $id_user);
      if(!$cliente)
      {
        $this->response(
          [
            'status' => FALSE,
            'error' => 'No se encontro el cliente especificado'
          ],
          REST_Controller::HTTP_NOT_FOUND
        );
      }
      $this->response($cliente);
    }
    $clientes = $this->MdlClientes->get_by_user($id_user);
    if(!$clientes)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se encontraron registros'
        ],
        REST_Controller::HTTP_NOT_FOUND
      );
    }
    $this->response($clientes);
  }

  /**
   * Borra el registro de un cliente siempre y cuando no se encuentre en uso
   * 
   * @param string $clave
   */
  public function index_delete()
  {
    $clave = $this->query('clave');
    if(!$clave)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se especifico el cliente a eliminar'
        ],
        REST_Controller::HTTP_BAD_REQUEST
      );
    }
    $cliente = $this->MdlClientes->get_by_clave_user($clave, $this->rest->user_id);
    if(!$cliente)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se encontro el cliente especificado'
        ],
        REST_Controller::HTTP_NOT_FOUND
      );
    }
    if(!$this->MdlClientes->delete($clave))
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se puede eliminar el cliente especificado'
        ],
        REST_Controller::HTTP_INTERNAL_SERVER_ERROR
      );
    }
    $this->response(
      [
        'status' => TRUE
      ]
    );
  }
}

/* End of file Cliente.php */
