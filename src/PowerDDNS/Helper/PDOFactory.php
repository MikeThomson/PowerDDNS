<?php
namespace PowerDDNS\Helper;

/**
 * Class PDOFactory
 * Builds PDO objects from config arrays
 * @package PowerDDNS\Helper
 */
class PDOFactory {
	/**
	 * Builds a PDO object based on a config array. Possible parameters are:
	 * 'dsn' - required
	 * 'username'
	 * 'password'
	 * 'driver_options'
	 * @param $params keys to use as parameters for PDO
	 * @return \PDO Built PDO object
	 * @throws \Exception If no DSN is provided for PDO to connect to
	 */
	public static function get($params) {
		$settings = array(
			'dsn' => null,
			'username' => null,
			'password' => null,
			'driver_options' => null
		);
		foreach($params as $key=>$param) {
			if(array_key_exists($key, $settings))
				$settings[$key] = $param;
		}
		$connection = null;
		
		if($settings['dsn'] !== null)
			$connection = new \PDO($settings['dsn'], $settings['username'], $settings['password'], $settings['driver_options']);
		
		if($connection === null)
			throw new \Exception('DSN not provided for connection');
		
		return $connection;
		
	}
}
