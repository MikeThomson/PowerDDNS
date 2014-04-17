<?php

namespace PowerDDNS\Backend;

use PowerDDNS\Record;

/**
 * Class DbBackend
 * Provides control for PowerDNS instances backing to a relational database
 * @package PowerDDNS\Backend
 */
class DbBackend implements BackendInterface
{
	/**
	 * The PDO instance to use for querying the database
	 * @var \PDO
	 */
	private $pdo;

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
	 * {@inheritDoc}
	 * @param string $domain
	 * @param string $newIp
	 * @param string $recordType
	 * @return Record
	 */
	public function updateRecord($domain, $newIp, $recordType = 'A')
	{
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

	/**
	 * {@inheritDoc}
	 * @param string $zone zone to update
	 */
	public function updateSerial($zone)
	{
		// @TODO this should cache the zone id instead, if possible
		// @TODO probbaly should implement standard date-based serials
		// get the current serial
		$select = $this->pdo->prepare('SELECT id, content FROM records WHERE name=:zone AND type = :type');
		$select->bindParam(':zone', $zone);
		$soa = 'SOA';
		$select->bindParam(':type', $soa);
		if($select->execute()) {
			if($row = $select->fetch()) {
				$soa = $row['content'];
				$data = explode(' ', $soa);
				$serial = (int) ($data[2]);
				$id = $row['id'];
				$serial += 1;
				$data[2] = $serial;
				$insert = $this->pdo->prepare('UPDATE records SET content = :data WHERE id=:id');
				$insert->bindParam(':data', implode(' ' , $data));
				$insert->bindParam(':id', $id);
				$insert->execute();
			}
		} else {
			// @TODO throw an exception?
		}
	}
}
