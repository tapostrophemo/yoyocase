alter table yoyos add `condition` varchar(15) default null;
alter table yoyos add `mod` varchar(255) default null;
alter table yoyos add `serialnum` varchar(64) default null;
alter table yoyos add `value` decimal(14,2) default null;

CREATE TABLE `acquisitions` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `yoyo_id` int(11) NOT NULL,
  `date` date default NULL,
  `type` varchar(15) default NULL,
  `party` varchar(255) CHARACTER SET utf8 default NULL,
  `price` decimal(14,2) default NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acq_user_yoyo` (`user_id`, `yoyo_id`),
  KEY `acq_user_id_idx` (`user_id`),
  KEY `acq_yoyo_id_idx` (`yoyo_id`)
);

