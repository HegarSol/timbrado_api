<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FEL_test extends TestCase {

  public function setUp(){
    $this->resetInstance();
    $this->CI->load->helper('fel_helper');
    $this->cfdi = file_get_contents(TESTPATH . 'xmls/comprobante_sin_timbre.xml');
    $this->ret = file_get_contents(TESTPATH . 'xmls/retenciones.xml');
    $this->bad_xml = file_get_contents(TESTPATH . 'phpunit.xml');
  }

  public function test_validar_rfc(){
    $this->assertArrayHasKey('RFCLocalizado', fel_validar_rfc('GAMG880406V7A'));
  }

  public function test_genera_referencia(){
    $this->assertEquals('comp_VAGM7505244I5A25', fel_genera_referencia($this->cfdi));
    $this->assertEquals('ret_HEGF7110049X96', fel_genera_referencia($this->ret));
    $this->expectException(Exception::class);
    fel_genera_referencia($this->bad_xml);
  }

  public function test_realiza_timbrado()
  {
    $this->assertArrayHasKey('OperacionExitosa', fel_realiza_timbrado($this->cfdi));
    $this->assertArrayHasKey('OperacionExitosa', fel_realiza_timbrado($this->ret));
    $this->expectException(Exception::class);
    fel_realiza_timbrado($this->bad_xml);
  }
}

/* End of file FEL_test.php */
