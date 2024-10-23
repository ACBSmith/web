CREATE TABLE IF NOT EXISTS `EbookTags` (
  `EbookId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`EbookId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
