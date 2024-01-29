<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class timbrado_test extends TestCase {

  public function setUp(){
    $this->resetInstance();
    $this->CI->load->helper('timbrado_helper');
    $this->CI->load->model('MdlProveedores');
    $this->cfdi = file_get_contents(TESTPATH . 'xmls/comprobante_sin_timbre.xml');
    $this->ret = file_get_contents(TESTPATH . 'xmls/retenciones.xml');
  }

  public function tearDown(){
    $this->CI->MdlProveedores->set_contingencia(NULL,0);
  }

  /**
   * Determina que el pac no se encuentre en contingencia
   */
  public function test_pac_no_en_contingencia(){
    $this->assertFalse(pac_en_contingencia('FEL'));
  }

  /**
   * Determina si el cliente se encuentra registrado en el sistema
   */
  public function test_get_pac_by_cliente(){
    $this->assertEquals("FEL",get_pac_by_cliente('GAMG880406V7A'));
    $this->CI->MdlProveedores->set_contingencia('FEL',1);
    $this->assertStringMatchesFormat('%s', get_pac_by_cliente('GAMG880406V7A'));
  }

  /**
   * Determina que el tipo de xml se a `comprobante`
   */
  public function test_tipo_xml(){
    $xml = file_get_contents(TESTPATH . 'xmls/comprobante_sin_timbre.xml');
    $this->assertEquals('comprobante', get_tipo_xml($xml));
    $xml = file_get_contents(TESTPATH . 'xmls/retenciones.xml');
    $this->assertEquals('retenciones', get_tipo_xml($xml));
    $xml = file_get_contents(TESTPATH . 'phpunit.xml');
    $this->assertFalse(get_tipo_xml($xml));
    $this->assertFalse( get_tipo_xml(''));
  }

  /**
   * Obtener un PAC que no se encuentre en contingencia
   */
  public function test_pac_disponible(){
    $this->assertStringMatchesFormat('%s', get_pac_disponible());
    $this->CI->MdlProveedores->set_contingencia(NULL, 1);
    $this->expectException(Exception::class);
    get_pac_disponible();
  }

  public function test_timbre_credito()
  {
    $this->assertArrayHasKey('OperacionExitosa', timbra_credito('GAMG880406V7A', $this->cfdi));
    $this->assertArrayHasKey('OperacionExitosa', timbra_credito('GAMG880406V7A', $this->ret));
  }

  public function test_timbre_prepago()
  {
    $this->assertArrayHasKey('OperacionExitosa', timbra_prepago('GAMG880406V7A', $this->cfdi));
    $this->assertArrayHasKey('OperacionExitosa', timbra_prepago('GAMG880406V7A', $this->cfdi, 'FEL'));
    $this->assertArrayHasKey('OperacionExitosa', timbra_prepago('GAMG880406V7A', $this->ret));
  }

  public function test_save_xml()
  {
    $timbre = array(
      'XmlResultado' => base64_encode($this->cfdi),
      'Timbre' => array(
        'FechaTimbrado' => date('Y-m-d H:i:s'),
        'UUID' => uniqid('test_')
      ),
      'id_pac' => 'FEL'
    );
    $path = almacena_xml('GAMG880406V7A', $timbre);
    $this->assertFileExists($path);
  }

  public function test_existe_local()
  {
    $this->assertTrue(existe_local(['Timbre' => ['UUID' => 'test_5ae787dab3c5f']]));
    $this->assertNull(existe_local(['Prueba']));
    $this->assertFalse(existe_local(['Timbre' => ['UUID' => 'Prueba']]));
  }
}


/* End of file timbrado_test.php */
