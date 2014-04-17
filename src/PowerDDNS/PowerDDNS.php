<?php
namespace PowerDDNS;

use PowerDDNS\Auth\AuthInterface;
use PowerDDNS\Backend\BackendInterface;
use PowerDDNS\Helper\EndpointHelper;

/**
 * Class PowerDDNS
 * A class designed to provide a DynDNS API compatible interface for PowerDNS servers
 * @package PowerDDNS
 */
class PowerDDNS
{
	/**
	 * @var Auth\AuthInterface
	 */
	protected $authAdapter;
	/**
	 * @var Backend\BackendInterface
	 */
	protected $backendAdapter;

	/**
	 * @param AuthInterface $authAdapter
	 * @param BackendInterface $backendAdapter
	 */
	public function __construct(AuthInterface $authAdapter, BackendInterface $backendAdapter) {
		$this->authAdapter = $authAdapter;
		$this->backendAdapter = $backendAdapter;
	}

	/**
	 * @param AuthInterface $adapter
	 */
	public function setAuthAdapter(Auth\AuthInterface $adapter) {
		$this->authAdapter = $adapter;
	}

	/**
	 * @return AuthInterface
	 */
	public function getAuthAdapter() {
		return $this->authAdapter;
	}

	/**
	 * @param BackendInterface $adapter
	 */
	public function setBackendAdapter(Backend\BackendInterface $adapter) {
		$this->backendAdapter = $adapter;
	}

	/**
	 * @return BackendInterface
	 */
	public function getBackendAdapter() {
		return $this->backendAdapter;
	}

	/**
	 * Gets all the information from PHP superglobals needed to update, providing a one-method, full-implementation
	 * endpoint for the DynDNS protocol
	 */
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

	/**
	 * Attempt an update on a record. The user witll be authenticated and checked for authorization before allowing
	 * the update to happen
	 * @param string $username
	 * @param string $password
	 * @param string|array $domains
	 * @param string $ip
	 * @return string DynDNS repsonse code
	 */
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
