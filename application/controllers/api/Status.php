<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Status extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('timbrado_helper');
  }
 
  /**
   * Obtiene el estatus de un RFC por parte de FEL
   */
  public function index_get()
  {
    $rfc = $this->get('rfc');
    if (!$rfc){
      _send_error('HGSR0001');
    }
    $this->load->helper('fel_helper');
    try{
      $result = fel_validar_rfc($rfc);
    } catch(Exception $ex){
      _send_error($ex->getMessage());
    }
    $this->response(['status' => TRUE, 'data' => $result]);
  }
}

/* End of file Status.php */
