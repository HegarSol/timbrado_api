<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlTimbrado extends CI_Model
{

  public function create($data)
  {
    $this->db->insert('timbrado', $data);
    return $this->db->insert_id();
  }

  public function get_by_uuid($uuid)
  {
    return $this->db->from('timbrado')
    ->where('uuid', $uuid)
    ->get()
    ->row();
  }
}

/* End of file MdlTimbrado.php */
