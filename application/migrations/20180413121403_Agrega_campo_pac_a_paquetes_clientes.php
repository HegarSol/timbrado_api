<?php
class Migration_Agrega_campo_pac_a_paquetes_clientes extends CI_Migration{
  public function up(){
    $field = array(
      'id_pac' => array(
        'type' => 'CHAR',
        'constraint' => 10,
        'null' => TRUE,
        'default' => NULL
      )
    );
    $this->dbforge->add_column('paquetes_clientes', $field);
  }

  public function down(){
    $this->dbforge->drop_column('paquetes_clientes','id_pac');
  }
}