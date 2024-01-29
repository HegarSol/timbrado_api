<?php
class Migration_Agregar_campos_noti extends CI_Migration{
  public function up()
  {
    $field = array(
        'Notificar' => array(
            'type' => 'int',
            'constraint' => 20,
            'null' => FALSE
          ),
    );
    $this->dbforge->add_column('clientes', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('clientes', 'Notificar');
  }
}