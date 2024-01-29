<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlComprobantes extends CI_Model {

  public function get_uuid($uuid){
    return $this->db->from('timbrado')
    ->where('uuid', $uuid)
    ->get()
    ->row();
  }
  public function get_xml($emisor,$folio,$serie){
    return $this->db->from('timbrado')
    ->where('emisor',$emisor)
    ->where('folio',$folio)
    ->where('serie',$serie)
    ->get()
    ->row();
  }
}