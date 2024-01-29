<?php
class Migration_Agrega_campo_prioridad_proveedor extends CI_Migration{
  public function up()
  {
    $field = array(
      'prioridad' => array(
        'type' => 'TINYINT',
        'default' => 0
      )
    );
    $this->dbforge->add_column('proveedores', $field);
    $this->db->set('prioridad', 100)->where('id', 'FEL')->update('proveedores');
    $this->db->set('prioridad', 90)->where('id', 'DFACTURE')->update('proveedores');
  }

  public function down()
  {
    $this->dbforge->drop_column('proveedores', 'prioridad');
  }
}