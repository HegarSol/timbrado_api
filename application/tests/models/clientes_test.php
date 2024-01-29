<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class clientes_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlClientes');
  }

  public function test_get_by_clave()
  {
    $this->assertObjectHasAttribute('clave', $this->CI->MdlClientes->get_by_clave('GAMG880406V7A'));
  }

  public function test_get_id_pac()
  {
    // Si existe
    $this->assertStringMatchesFormat('%s', $this->CI->MdlClientes->get_id_pac('GAMG880406V7A'));
    // No existe
    $this->assertNull($this->CI->MdlClientes->get_id_pac('asd'));
  }
}

/* End of file clientes_test.php */
