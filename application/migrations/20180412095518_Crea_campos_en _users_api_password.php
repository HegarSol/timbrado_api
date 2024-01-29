<?php
class Migration_Crea_campos_en_users_api_password extends CI_Migration{
  public function up(){
    $fields = array(
      'api_user' => array(
        'type' => 'VARCHAR',
        'constraint' => 20,
        'unique' => TRUE,
        'null' => FALSE
      ),
      'api_password' => array(
        'type' => 'VARCHAR',
        'constraint' => 100,
        'null' => FALSE
      )
    );
    $this->dbforge->add_column('users', $fields);
  }

  public function down(){
    $this->dbforge->drop_column('users', ['api_user', 'api_password']);
  }
}