<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class errores_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlErrores');
  }

  public function test_get_by_codigo()
  {
    $this->assertObjectHasAttribute('codigo', $this->CI->MdlErrores->get_by_codigo('HGSR0001'));
    $this->assertNull($this->CI->MdlErrores->get_by_codigo(''));
  }
}

/* End of file errores_test.php */
