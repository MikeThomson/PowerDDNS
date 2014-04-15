<?php 
namespace PowerDDNS\Backend;

interface BackendInterface {
	public function updateRecord($domain, $newIp, $recordType = 'A');
	public function updateSerial();
}
