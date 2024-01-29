<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlLogError extends CI_Model {

  public function create($cliente, $pac, $codigo, $mensaje, $detallado)
  {
    $data['clave_cliente'] = $cliente;
    $data['id_pac'] = $pac;
    $data['codigo_error'] = $codigo;
    $data['mensaje'] = $mensaje;
    $data['mensaje_detallado'] = $detallado;
    $this->db->insert('log_error_timbrado', $data);
  }
}

/* End of file MdlLogError.php */
