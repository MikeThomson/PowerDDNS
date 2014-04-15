<?php
namespace PowerDDNS\Helper;

class PDOFactory {
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
