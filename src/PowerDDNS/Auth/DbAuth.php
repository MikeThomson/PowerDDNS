<?php

namespace PowerDDNS\Auth;

use \PowerDDNS\Helper\PDOFactory;

class DbAuth implements AuthInterface
{
	protected $userTable = 'pddns_users';
	protected $permissionTable = 'pddns_perms';
	
	private $pdo;
	private $cachedUserIds = array();
	
	public function __construct($params) {
		if(is_array($params)) {
			$this->pdo = PDOFactory::get($params);
		} else if($params instanceof \PDO) {
			$this->pdo = $params;
		} else {
			throw new \InvalidArgumentException();
		}
	}

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
		var_dump($domains);
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
	
	public function hashPassword($password, $username = null) {
		return md5($password);
	}
}
