<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class proveedores_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlProveedores');
  }

  public function test_get_by_nombre()
  {
    $this->assertObjectHasAttribute('id', $this->CI->MdlProveedores->get_by_nombre('DFACTURE'));
  }

  public function test_set_contingencia()
  {
    $this->assertGreaterThan(0, $this->CI->MdlProveedores->set_contingencia());
    $this->assertGreaterThan(0, $this->CI->MdlProveedores->set_contingencia('FEL',0));
    $this->assertGreaterThan(0, $this->CI->MdlProveedores->set_contingencia(NULL, 0));
  }
}

/* End of file proveedores_test.php */
