PowerDDNS
=========
PowerDDNS is a package designed to make it easy to provide a [DynDNS API](http://dyn.com/support/developers/api/) compatible endpoint for your own DNS servers.
While the name implies that it is for [PowerDNS](https://www.powerdns.com/), there's no reason you couldn't implement a backend that would work
with any other DNS server.

I'll happily take pull requests from people who implement more auth / backend interfaces.

Installing
----------
Installation is easy with composer, just run ` php composer.phar require mikethomson mikethomson/power-ddns `

Example
-------
All that's needed to implement an endpoint is an index.php like the following:

	<?php
	require 'vendor/autoload.php';
	$pdo = \PowerDDNS\Helper\PDOFactory::get(array(
		'dsn' => 'mysql:host=localhost;dbname=powerdns',
		'username' => 'root'
	));

	$auth = new \PowerDDNS\Auth\DbAuth($pdo);
	$backend = new \PowerDDNS\Backend\DbBackend($pdo);

	$pddns = new \PowerDDNS\PowerDDNS($auth, $backend);
	$pddns->endpoint();

TODO
----
- Implement a BIND backend
- Fix all the todos in the code
- Finish implementing tests

Credit
------
Credit to [nicokaiser](https://github.com/nicokaiser/Dyndns/) for a good reference while building this