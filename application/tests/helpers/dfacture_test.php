<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class dfacture_test extends TestCase
{
  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->helper('dfacture_helper');
    $this->cfdi = file_get_contents(TESTPATH . 'xmls/comprobante_sin_timbre.xml');
    $this->retencion = file_get_contents(TESTPATH . 'xmls/retenciones.xml');
  }

  public function test_tibrado()
  {
    
  }
}

/* End of file dfacture_test.php */
