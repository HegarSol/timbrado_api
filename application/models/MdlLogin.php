<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlLogin extends CI_Model {
       
       public function __construct()
       {
       	   parent::__construct();
       }
       public function login($user,$pass)
       {
   	      $this->db->join('keys','keys.user_id = users.id');
       	  $this->db->where('email',$user);
       	  $this->db->where('password',$pass);
       	  $q = $this->db->get('users');
       	  if($q->num_rows()>0)
       	  {
       	  	 return $q->row();
       	  }
       	  else
       	  {
       	  	return false;
       	  }
       }
}