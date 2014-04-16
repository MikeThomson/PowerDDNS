<?php

namespace PowerDDNS\Backend;

use PowerDDNS\Record;

class DbBackend implements BackendInterface
{
	private $pdo;

	public function __construct($params) {
		if(is_array($params)) {
			$this->pdo = PDOFactory::get($params);
		} else if($params instanceof \PDO) {
			$this->pdo = $params;
		} else {
			throw new \InvalidArgumentException();
		}
	}

	public function updateRecord($domain, $newIp, $recordType = 'A')
	{
		// TODO: write logic here
		$update = $this->pdo->prepare('UPDATE records SET content = :ip WHERE name=:domain AND type = :type');
		$update->bindParam(':domain', $domain);
		$update->bindParam(':ip', $newIp);
		$update->bindParam(':type', $recordType);
		$update->execute();

		// now reselect the record to get the zone
		$select = $this->pdo->prepare('SELECT records.name as name, records.type, records.content, domains.name as zone FROM records JOIN domains on records.domain_id = domains.id WHERE records.name = :domain AND records.type = :type');
		$select->bindParam(':domain', $domain);
		$select->bindParam(':type', $recordType);
		if($select->execute()) {
			if($row = $select->fetch()) {
				return new Record(array(
					'zone' => $row['zone'],
					'content' => $row['content'],
					'type' => $row['type'],
					'domain' => $row['name'],
				));
			}
		} else {
			// @TODO throw an exception?
		}
	}

	public function updateSerial($zone)
	{
		// @TODO this should cache the zone id instead, if possible
		// get the current serial
		$select = $this->pdo->prepare('SELECT id, notified_serial FROM domains WHERE name=:zone');
		$select->bindParam(':zone', $zone);
		if($select->execute()) {
			if($row = $select->fetch()) {
				$serial = (int) $row['notified_serial'];
				$id = $row['id'];
				$serial += 1;
				$insert = $this->pdo->prepare('UPDATE domains SET notified_serial = :serial WHERE id=:id');
				$insert->bindParam(':serial', $serial);
				$insert->bindParam(':id', $id);
				$insert->execute();
			}
		} else {
			// @TODO throw an exception?
		}
	}
}
