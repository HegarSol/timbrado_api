<?php
class Migration_Crea_campos_paquetes_clientes extends CI_Migration{
  public function up()
  {
    $fields = array(
      'cantidad_comprada' => array(
        'type' => 'INT',
        'null' => FALSE,
        'default' => 0
      ),
      'referencia_compra' => array(
        'type' => 'VARCHAR',
        'constraint' => 30,
        'null' => TRUE
      ),
      'uuid_factura' => array(
        'type' => 'VARCHAR',
        'constraint' => 38,
        'null' => TRUE
      )
    );
    $this->dbforge->add_column('paquetes_clientes', $fields);
  }

  public function down()
  {
    $this->dbforge->drop_column('paquetes_clientes', ['cantidad_comprada', 'referencia_compra', 'uuid_factura']);  
  }
}