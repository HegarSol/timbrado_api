<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlErrores extends CI_Model {

  public function get_by_codigo($codigo){
    return $this->db->from('errores')
    ->where('codigo', $codigo)
    ->get()
    ->row();
  }

}

/* End of file MdlErrores.php */
