<?php 
namespace PowerDDNS\Backend;

/**
 * Interface BackendInterface
 * Provides an interface for PowerDDNS to communicate with PowerDNS
 * @package PowerDDNS\Backend
 */
interface BackendInterface {
	/**
	 * Updates a record on the DNS server
	 * @param string $domain domain to perform the update for
	 * @param string $newIp IP address to set content to
	 * @param string $recordType
	 * @return \PowerDDNS\Record updated copy of the record with zone information
	 */
	public function updateRecord($domain, $newIp, $recordType = 'A');

	/**
	 * Update the serial on the SOA record so that chnages propogate
	 * @param string $zone
	 * @return void
	 */
	public function updateSerial($zone);
}
