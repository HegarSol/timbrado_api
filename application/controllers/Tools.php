<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {
  
  public function __construct() {
    parent::__construct();
		// Solo puede ser llamada desde la consola
		if (!is_cli()) {
		  exit('No esta permitido el acceso directo. Esta es una herramienta de consola. Usa la terminal');
		}
  }
  
  public function index() {
		$this->help();
	}

	public function help() {
    echo "Estos son los comandos disponibles en la interface\n\n" . PHP_EOL;
		echo "php index.php tools migration \"nombre_archivo\"   -> Crea un nuevo archivo de migration." . PHP_EOL;
		echo "php index.php tools new_encryption_key             -> Genera una nueva llave para la encriptacion de informacion" . PHP_EOL;
	}

	public function migration($name) {
		$this->make_migration_file($name);
	}

	protected function make_migration_file($name) {
		$date = new DateTime();
		$timestamp = $date->format('YmdHis');
		$table_name = strtolower($name);
		$path = APPPATH . "migrations/$timestamp" . "_" . "$name.php";
		$class = "CI_Migration";
		$my_migration = fopen($path, 'w') or die("No se puede crear el archivo de migracion");

		$migration_template = "<?php
class Migration_$name extends $class{
  public function up(){

  }

  public function down(){
    
  }
}";
		fwrite($my_migration, $migration_template);
		fclose($my_migration);
	  echo "$path migration creada correctamente" . PHP_EOL;
	}

  public function new_encryption_key() {
		echo bin2hex($this->encryption->create_key(16)) . PHP_EOL;
  }
}
/* End of file Tools.php */
/* Location: ./application/controllers/Tools.php */
