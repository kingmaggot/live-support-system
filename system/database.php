<?php

namespace system;

use \PDO;

class Database {
	
	//Instance of the database
	private static $instance;
	
	//PDO
	private $pdo;

	//Database configurations
	private $config;

	//Statement
	private $stmt;
	
	//Constructor
	private function __construct() {
	
		if (!extension_loaded('pdo')) {

			error('The PDO extension is required.');
			
		}
		
		$config = config_load('database');
		
		if (empty($config['db_driver'])) {

			error('Please set a valid database driver from config/database.php');
			
		}
		
		switch ($config['db_driver']) {

			case 'mysql':

				try {
					
					$this->pdo = new PDO('mysql:host=' . $config['db_server'] . ';port=' . $config['db_port'] . ';dbname=' . $config['db_name'], $config['db_username'], $config['db_password']);
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$this->pdo->query('SET NAMES ' . $config['db_charset']);				
					
				} catch (PDOException $exception) {
					
					throw new Exception($exception->getMessage());

				}

			break;

			default:
				error('This database driver does not support: ' . $config['db_driver']);

		}
		
	}

	//Disallow cloning of the class
	private function __clone() {}

	//Connects to the database
	public static function connection() {
		
		if (!self::$instance) {
			
			self::$instance = new Database();
			
		}
		
		return self::$instance;

	}

	//Executes a query
	private function query($sql, $parameters) {

		$this->stmt = $this->pdo->prepare($sql);
		
		if (is_array($parameters)) {
			
			foreach ($parameters as $key => $value) {
				
				$this->stmt->bindValue(':' . $key, $value);
				
			}
			
		}

		$this->stmt->execute();
		
		return $this->stmt;

    }

	//Select the data from the database
	public function select($sql, $parameters = null) {

		return $this->query($sql, $parameters)->fetchAll(PDO::FETCH_ASSOC);
		
	}

	//Get the field from table
	public function get_field($table, $fields, $where, $parameters = null) {
		
		$sql = 'SELECT ' . $fields . ' FROM ' . $table . ' WHERE ' . $where;
		
		$data = $this->query($sql, $parameters)->fetchAll(PDO::FETCH_ASSOC);
		
		return isset($data[0]) ? $data[0] : false;
		
	}

	//Counts the number of rows
    public function count($sql, $parameters = null) {

		return $this->query($sql, $parameters)->rowCount();

    }
	
	//Insert
	public function insert($table, $data) {			
		
		$parameters = array();
		
		foreach ($data as $key => $value) {
			
			$fields[] = $key . ' = :' . $key;
			
			$parameters[$key] = $value;
			
		}

		$sql = 'INSERT INTO ' . $table . ' SET ' . implode(', ', $fields);
		
		$this->query($sql, $parameters);

	}

	//Update
	public function update($table, $fields, $where, $parameters = null) {

		$sql  = 'UPDATE ' . $table . ' SET ' . $fields . ' WHERE ' . $where;

		$this->query($sql, $parameters);

	}

	//Delete
	public function delete($table, $where, $parameters = null) {

		$sql  = 'DELETE FROM ' . $table . ' WHERE ' . $where;
		
		$this->query($sql, $parameters);

    }

	//Get the id of the last inserted row
	public function last_insert_id() {
		
		return $this->pdo->lastInsertId();
	
	}

	//Get the information of database
	public function info() {
		
		$data = array(
			'Driver'			=> 'DRIVER_NAME',
			'Client version'	=> 'CLIENT_VERSION',
			'Server version'	=> 'SERVER_VERSION',
			'Connection'		=> 'CONNECTION_STATUS'
		);

		foreach ($data as $key => $value) {
			
			$info[$key] = $this->pdo->getAttribute(constant('PDO::ATTR_' . $value));
			
		}

		return $info;
		
	}
	
}

?>
