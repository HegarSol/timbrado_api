<?php
class Migration_crear_campos_noti_fecha_a_tabla_clientes extends CI_Migration{
  public function up(){
    $field = array(
      'notificar_correo' => array(
        'type' => 'int',
        'constraint' => 5,
        'default' => 0,
        'null' => TRUE
      )
    );
    $this->dbforge->add_column('clientes', $field);

    $field2 = array(
      'fecha_correo' => array(
        'type' => 'DATE',
      )
    );
    $this->dbforge->add_column('clientes',$field2);
  }

  public function down(){
 //   $this->dbforge->drop_column('proveedores', 'contingencia');
  }
}