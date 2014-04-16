CREATE TABLE `pddns_users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(64) NOT NULL,
	`password` VARCHAR(64) NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2;

CREATE TABLE `pddns_perms` (
	`userId` INT(11) NOT NULL,
	`recordId` INT(11) NOT NULL,
	PRIMARY KEY (`userId`, `recordId`),
	INDEX `fk_pddns_records_permissions` (`recordId`),
	CONSTRAINT `fk_pddns_users_permissions` FOREIGN KEY (`userId`) REFERENCES `pddns_users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_pddns_records_permissions` FOREIGN KEY (`recordId`) REFERENCES `records` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;
