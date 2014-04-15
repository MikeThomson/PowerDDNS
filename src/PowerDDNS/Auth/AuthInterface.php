<?php
namespace PowerDDNS\Auth;

interface AuthInterface {
	public function authenticate($username, $password);
	public function authorize($username, $domain);
}
