<?php
namespace PowerDDNS;

use PowerDDNS\Auth\AuthInterface;
use PowerDDNS\Backend\BackendInterface;
use PowerDDNS\Helper\EndpointHelper;

class PowerDDNS
{
	protected $authAdapter;
	protected $backendAdapter;
	
	public function __construct(AuthInterface $authAdapter, BackendInterface $backendAdapter) {
		$this->authAdapter = $authAdapter;
		$this->backendAdapter = $backendAdapter;
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
		if(!EndpointHelper::hasAuth())
			EndpointHelper::requestAuth();
		$username = EndpointHelper::getUser();
		$pass = EndpointHelper::getPass();
		$domains = EndpointHelper::getDomains();
		$ip = EndpointHelper::getIp();

		if(count($domains) == 0)
			EndpointHelper::sendResponse('notfqdn');

		EndpointHelper::sendResponse($this->update($username, $pass, $domains, $ip));

	}
	
	public function update($username, $password, $domains, $ip) {
		if(!$this->authAdapter->authenticate($username, $password)) {
			return Response::BADAUTH;
		}
		if(!$this->authAdapter->authorize($username, $domains)) {
			return Response::NO_HOST;
		}
		$zonesToUpdate = array();
		foreach($domains as $domain) {
			$record = $this->backendAdapter->updateRecord($domain, $ip);
			$zonesToUpdate[] = $record->zone;
		}
		$zonesToUpdate = array_unique($zonesToUpdate);
		foreach($zonesToUpdate as $zone) {
			$this->backendAdapter->updateSerial($zone);
		}
		return Response::GOOD;
	}
}
