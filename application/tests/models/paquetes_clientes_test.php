<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class paquetes_clientes_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlPaquetesClientes');
  }

  public function test_resta_cantidad()
  {
    $this->assertGreaterThan(0, $this->CI->MdlPaquetesClientes->resta_cantidad_by_id(1));
  }

  public function test_paquetes_vigentes()
  {
    $this->assertInternalType('array', $this->CI->MdlPaquetesClientes->get_paquetes_vigentes('GAMG880406V7A'));
  }
}

/* End of file paquetes_clientes_test.php */
