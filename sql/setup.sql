CREATE TABLE `schema_migrations` (
  `version` varchar(255) NOT NULL,
  UNIQUE KEY `unique_schema_migrations` (`version`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) NOT NULL,
  `crypted_password` varchar(255) NOT NULL,
  `password_salt` varchar(255) NOT NULL,
  `persistence_token` varchar(255) NOT NULL,
  `perishable_token` varchar(255) NOT NULL,
  `failed_login_count` int(11) NOT NULL default '0',
  `current_login_at` datetime default NULL,
  `last_login_at` datetime default NULL,
  `current_login_ip` varchar(255) default NULL,
  `last_login_ip` varchar(255) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `flickr_userid` varchar(255) default NULL,
  `is_admin` boolean default FALSE,
  `photobucket_username` varchar(255) default NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
);

CREATE TABLE `user_pw_reset` (
  `user_id` int(11) NOT NULL
);

CREATE TABLE `yoyos` (
  `id` int(11) NOT NULL auto_increment,
  `manufacturer` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `model_year` int(11) default NULL,
  `model_name` varchar(255) default NULL,
  `user_id` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `notes` text,
  `condition` varchar(15) default NULL,
  `mod` varchar(255) default NULL,
  `serialnum` varchar(64) default NULL,
  `value` decimal(14,2) default NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
);

CREATE TABLE `photos` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) default NULL,
  `yoyo_id` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY (`id`),
  KEY `yoyo_id_idx` (`yoyo_id`)
);

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

CREATE TABLE `system` (
  `name`  varchar(255) default NULL,
  `value`  varchar(4000) default NULL
);
INSERT INTO system VALUES('max_thumbnail_id', '1024');

