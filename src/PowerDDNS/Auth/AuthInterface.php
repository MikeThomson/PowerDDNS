<?php
namespace PowerDDNS\Auth;

/**
 * Interface AuthInterface
 * Provides the interface for PowerDDNS to authenticate requests and authorize users to change a domain
 * @package PowerDDNS\Auth
 */
interface AuthInterface {
	/**
	 * Checks if this username and password combination are a valid user
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function authenticate($username, $password);

	/**
	 * Checks to see if this username can update the domain or domains provided
	 * @param string $username
	 * @param string|array $domains
	 * @param string $recordType
	 * @return bool
	 */
	public function authorize($username, $domains, $recordType = 'A');
}
