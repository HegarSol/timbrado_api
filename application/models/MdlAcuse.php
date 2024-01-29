<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlAcuse extends CI_Model{
    public function add($data)
    {
        $this->db->insert('acuse_cancelacion',$data);
        return $this->db->insert_id();
    }
}