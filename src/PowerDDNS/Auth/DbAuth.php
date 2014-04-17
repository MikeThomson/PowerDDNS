<?php

namespace PowerDDNS\Auth;

use \PowerDDNS\Helper\PDOFactory;

/**
 * Class DbAuth
 * Provides authentication and authorization for PowerDNS instances backing to a relational database
 * @package PowerDDNS\Auth
 */
class DbAuth implements AuthInterface
{
	/**
	 * The table containing users and their passwords
	 * @var string
	 */
	protected $userTable = 'pddns_users';
	/**
	 * The table mapping users to domains they are authorized to change
	 * @var string
	 */
	protected $permissionTable = 'pddns_perms';

	/**
	 * PDO instance to use
	 * @var \PDO
	 */
	private $pdo;
	/**
	 * userIds cache to speed up lookups
	 * @var array
	 */
	private $cachedUserIds = array();

	/**
	 * Constructor that can either take a PDO instance or a config array that will be passed to \PowerDDNS\PDOFactory
	 * @param array|\PDO
	 */
	public function __construct($params) {
		if(is_array($params)) {
			$this->pdo = PDOFactory::get($params);
		} else if($params instanceof \PDO) {
			$this->pdo = $params;
		} else {
			throw new \InvalidArgumentException();
		}
	}

	/**
	 * {@inheritdoc}
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function authenticate($username, $password)
	{
		$select = $this->pdo->prepare('SELECT id FROM '.$this->userTable.' WHERE username = :username AND password = :password');
		$select->bindParam(':username', $username);
		$select->bindParam(':password', $this->hashPassword($password, $username));
		if($select->execute()) {
			if($row = $select->fetch()) {
				$this->cachedUserIds[$username] = $row[0];
				return true;
			}
		} else {
			// @TODO throw some exception?
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 * @param string $user
	 * @param array|string $domains
	 * @param string $recordType
	 * @return bool
	 */
	public function authorize($user, $domains, $recordType = 'A')
	{
		if(!is_array($domains))
			$domains = array($domains);
		
		$imploder = array();
		$i = 0;
		foreach($domains as $domain) {
			$imploder[] = ":domain" . $i;
			$i++;
		}
		$domainGroup = implode(',', $imploder);
		// @TODO shouldn't actually have to authenticate before authorizing
		$userId = $this->cachedUserIds[$user];
		$select = $this->pdo->prepare(
			'SELECT records.name as domain ' .
			'FROM '.$this->permissionTable.' AS permissions ' .
			'JOIN records ON permissions.recordId = records.id ' .
			'WHERE permissions.userId = :id AND records.type = :recordtype AND records.name IN('.$domainGroup.')');
		$select->bindParam(':id', $userId);
		$select->bindParam(':recordtype', $recordType);
		$i = 0;
		foreach($domains as $key=>$domain) {
			$select->bindParam(':domain' . $i, $domains[$key]);
			$i++;
		}
		
		// we only need to verify the counts for now. PowerDNS constraints should guarantee there won't be duplicates
		if($select->execute()) {
			if(count($domains) == count($select->fetchAll()))
				return true;
		} else {
			// @TODO throw some exception?
		}
		return false;
	}

	/**
	 * This is called on the cleartext password given to \PowerDDNS\Auth\DbAuth::authenticate
	 * This can be overriden to match the hashing scheme used by your user provider
	 * BE AWARE: MD5 alone is not a secure hashing mechanism for passwords
	 * @param string $password
	 * @param null|string $username
	 * @return string hashed password
	 */
	public function hashPassword($password, $username = null) {
		return md5($password);
	}
}
