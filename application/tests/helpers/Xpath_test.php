<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Xpath_test extends TestCase {

  public function setUp(){
    $this->resetInstance();
    $this->xml_comprobante = file_get_contents(
      TESTPATH . 'xmls/comprobante_sin_timbre.xml');
    $this->xml_retenciones = file_get_contents(
      TESTPATH . 'xmls/retenciones.xml'
    );
    $this->CI->load->helper('timbrado_helper');
  }

  public function test_xpath_query(){
    $this->assertInstanceOf(
      'DOMNodeList',
      execute_xpath_query($this->xml_comprobante, 'cfdi:Comprobante')
    );
  }

  public function test_xpath_get_attribute(){
    $this->assertEquals(
      '3.3',
      xpath_get_attribute($this->xml_comprobante, 'cfdi:Comprobante/@Version')
    );
    $this->assertEquals(
      '1.0',
      xpath_get_attribute($this->xml_retenciones, 'retenciones:Retenciones/@Version')
    );
    $this->assertEquals(
      '',
      xpath_get_attribute($this->xml_comprobante, 'cfdi:Comprobante/@version')
    );
    $this->assertEquals('',xpath_get_attribute('', 'mal/query'));
  }
}

/* End of file Xpath_test.php */
