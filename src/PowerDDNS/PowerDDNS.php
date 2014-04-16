<?php
namespace PowerDDNS;

class PowerDDNS
{
	protected $authAdapter;
	protected $backendAdapter;
	
	public function __construct() {
		
	}
	
	public function setAuthAdapter(Auth\AuthInterface $adapter) {
		$this->authAdapter = $adapter;
	}
	
	public function getAuthAdapter() {
		return $this->authAdapter;
	}
	
	public function setBackendAdapter(Backend\BackendInterface $adapter) {
		$this->backendAdapter = $adapter;
	}
	
	public function getBackendAdapter() {
		return $this->backendAdapter;
	}
	
	public function endpoint() {

	}
	
	public function update($username, $password, $domains, $ip) {
		
	}
}
