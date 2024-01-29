<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class users_test extends TestCase
{

  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('MdlUsers');
  }

  public function test_get_by_email()
  {
    $this->assertObjectHasAttribute('id',$this->CI->MdlUsers->get_by_email('ggarza@hegarss.com'));
    $this->assertNull($this->CI->MdlUsers->get_by_email('gahshsh'));
  }

  public function test_get_by_api_user()
  {
    $this->assertObjectHasAttribute('id',$this->CI->MdlUsers->get_by_api_user('hegarss'));
    $this->assertNull($this->CI->MdlUsers->get_by_api_user('gahshsh'));
  }
}

/* End of file users_test.php */
