<?php
class Migration_Agrega_campo_especial_paquetes extends CI_Migration{
  public function up()
  {
    $field = array(
      'especial' => array(
        'type' => 'TINYINT',
        'null' => FALSE,
        'default' => 0
      )
    );
    $this->dbforge->add_column('paquetes', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('paquetes', 'especial');  
  }
}