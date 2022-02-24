CREATE TABLE IF NOT EXISTS `urlsCutterStatistic_table` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `URL_CODE` varchar(100) NOT NULL,
  `SHORT_URL_CODE` varchar(100) NOT NULL,
  `BROWSER_DATA` varchar(100) NOT NULL,
  `LAST_USED` date NOT NULL,
  `NUMBER_OF_USED` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
);