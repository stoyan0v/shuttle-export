<?php
namespace ShuttleExport\DBConn;
use ShuttleExport\Exception;

abstract class DBConn {
	public $host;
	public $username;
	public $password;
	public $name;
	public $port;
	public $prefix;

	public $charset;

	protected $connection;

	function __construct($options) {
		preg_match("~([A-Za-z0-9\-\.]+):?([0-9]+)?~", $options['db_host'], $parsed_host );

		$this->host = ! empty( $parsed_host[1] ) ? $parsed_host[1] : 'localhost';
		$this->port = ! empty( $parsed_host[2] ) ? $parsed_host[2] : 3306;

		//$this->host = $options['db_host'];
		//$this->port = $options['db_port'];
		$this->username = $options['db_user'];
		$this->password = $options['db_password'];
		$this->name = $options['db_name'];
		$this->charset = 'utf8';
		$this->prefix = $options['prefix'];
	}

	static function create($options) {
		if (class_exists('\mysqli')) {
			$class_name = "ShuttleExport\\DBConn\\Mysqli";
		} else if (function_exists('mysql_connect')) {
			$class_name = "ShuttleExport\\DDBConn\\Mysql";
		} else {
			throw new Exception("The PHP installation doesn't have neither mysqli nor mysql extensions. ");
		}

		return new $class_name($options);
	}

	abstract function connect();
	abstract function query($query);
	abstract function fetch_numeric($query);
	abstract function fetch($query, $result_type='');
	abstract function escape($value);
	abstract function get_var($sql);
	abstract function fetch_row($data);
	abstract function server_version();

	public function escape_like($search) {
		return str_replace(
			array('_', '%'),
			array('\\_', '\\%'),
			$search
		);
	}
}
