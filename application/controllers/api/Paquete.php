<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';
class Paquete extends REST_Controller
{

  protected $methods = [
    'index_delete' => ['level' => 10]
  ];

  public function __construct()
  {
    parent::__construct();
    $this->load->model('MdlPaquetes');
    $this->load->model('MdlErrores');
  }

  /**
   * Agregar un nuevo paquete perteneciente al usuario para poder ser asignado a sus clientes
   * 
   * @param int     $cantidad     Es la cantidad de timbres que tiene el paquete
   * @param number  $adicional    Es el precio adicional que tiene cada timbre cuando es de credito
   * @param number  $precio       Es el precio del paquete
   * @param bool    $credito      Indica si el paquete se maneja a credito o como prepago
   * @param bool    $especial     Indica si el paquete es un paquete especial que no se ofrece a todos los clientes
   */
  public function index_post()
  {
    $cantidad = $this->post('cantidad');
    $adicional = $this->post('precio_adicional');
    $precio = $this->post('precio');
    $credito = $this->post('credito');
    $especial = $this->post('especial');
    $data = array(
      'id_user' => $this->rest->user_id,
      'cantidad' => $cantidad,
      'precio_adicional' => isset($adicional) ? $adicional : 0.00,
      'precio' => $precio,
      'credito' => isset($credito) ? $credito : 0,
      'especial' => isset($especial) ? $especial : 0
    );
    $id_paquete = $this->MdlPaquetes->add($data);
    $new_paquete = $this->MdlPaquetes->get_by_id($id_paquete);
    if(!isset($new_paquete))
    {
      $this->response(array(
        'status' => FALSE,
        'error' => 'No se genero el Paquete'
      ), REST_Controller::HTTP_BAD_REQUEST);
    }
    unset($new_paquete->id_user);
    $this->response($new_paquete, REST_Controller::HTTP_CREATED);
  }

  /**
   * Retorna la informacion del paquete especificado
   * 
   * @param int $id_paquete   Es el ID del paquete que se requiere
   */
  public function index_get()
  {
    $id_paquete = $this->get('id');
    if(!$id_paquete)
    {
      $paquete = $this->MdlPaquetes->get_by_user($this->rest->user_id);
    }
    else
    {
      $paquete = $this->MdlPaquetes->get_by_id($id_paquete);
    }
    if(!$paquete)
    {
      $this->response(
        array(
          'status' => FALSE,
          'error' => 'No se encontraron registros'
        ),
        REST_Controller::HTTP_NOT_FOUND
      );
    }
    $this->response($paquete);
  }

  /**
   * Elimina un paquete de la lista de paquetes siempre y cuando este no este siendo usado por un cliente
   * 
   * @param int $id   Es el ID del paquete que se desea borrar
   */
  public function index_delete()
  {
    $id_paquete = $this->query('id');
    $paquete = $this->MdlPaquetes->get_by_id($id_paquete);
    if(!$paquete)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se encontro el paquete especificado o no se especifico alguno'
        ],
        REST_Controller::HTTP_NOT_FOUND
      );
    }
    if($paquete->id_user !== $this->rest->user_id)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'El paquete que intenta eliminar no pertenece a su usuario'
        ],
        REST_Controller::HTTP_UNAUTHORIZED
      );
    }
    $this->load->model('MdlPaquetesClientes');
    $usado = $this->MdlPaquetesClientes->count_clientes_by_paquete($id_paquete);
    if($usado > 0)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'El paquete no se puede eliminar ya que se encuentra en uso'
        ],
        REST_Controller::HTTP_UNAUTHORIZED
      );
    }
    if($this->MdlPaquetes->delete($id_paquete))
    {
      $this->response(NULL, REST_Controller::HTTP_OK);
    }
    $this->response(
      [
        'status' => FALSE,
        'error' => 'No se pudo eliminar el paquete'
      ],
      REST_Controller::HTTP_INTERNAL_SERVER_ERROR
    );
  }

  /**
   * Actualiza la informacion de un paquete previamente registrado
   * 
   * @param int $id         Es el ID del paquete que se quiere modificar
   * @param int $cantidad   La cantidad de timbres que tiene el paquete
   * @param number $adicional   Es el precio que tiene el timbre adicional cuando es de credito
   * @param number $precio      Es el precio que tiene el paquete
   * @param bool $credito       Indica si el paquete es de credito
   * @param bool $especial      Indica si el paquete es especial para cierto cliente 
   */
  public function index_put()
  {
    $id_paquete = $this->put('id');
    $paquete = $this->mdlPaquetes->get_by_id($id);
    if(!$paquete)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'No se econtro el paquete especificado'
        ],
        REST_Controller::HTTP_NOT_FOUND
      );
    }
    if($paquete->id_user != $this->rest->user_id)
    {
      $this->response(
        [
          'status' => FALSE,
          'error' => 'El paquete no pertenece a su usuario'
        ],
        REST_Controller::HTTP_UNAUTHORIZED
      );
    }
    $cantidad = $this->put('cantidad');
    $adicional = $this->put('adicional');
    $precio = $this->put('precio');
    $credito = $this->put('credito');
    $especial = $this->put('especial');
    $data = array(
      'cantidad' => isset($cantidad) ? $cantidad : $paquete->cantidad,
      'precio_adicional' => isset($adicional) ? $adicional : $paquete->precio_adicional,
      'precio' => isset($precio) ? $precio : $paquete->precio,
      'credito' => isset($credito) ? $credito : $paquete->credito,
      'especial' => isset($especial) ? $especial : $paquete->especial
    );
    if($this->mdlPaquetes->update($id_paquete, $data))
    {
      $this->response($this->MdlPaquetes->get_by_id($id_paquete), REST_Controller::HTTP_OK);
    }
    $this->response(
      [
        'status' => FALSE,
        'error' => 'No se actualizo el registro'
      ],
      REST_Controller::HTTP_INTERNAL_SERVER_ERROR
    );
  }

}

/* End of file Paquete.php */
