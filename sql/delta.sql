CREATE TABLE `archives` (
  `id` int(11) NOT NULL auto_increment,
  `yoyo_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `data` text default NULL,
  PRIMARY KEY (`id`)
);

