<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class paquetes_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlPaquetes');
  }

  public function test_get_by_id()
  {
    $this->assertObjectHasAttribute('id', $this->CI->MdlPaquetes->get_by_id(1));
    $this->assertNull($this->CI->MdlPaquetes->get_by_id(0));
  }
}

/* End of file paquetes_test.php */
