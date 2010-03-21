CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
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
  PRIMARY KEY (`id`)
);
ALTER TABLE users ADD CONSTRAINT UNIQUE KEY (username);
ALTER TABLE users ADD CONSTRAINT UNIQUE KEY (email);

CREATE TABLE `yoyos` (
  `id` int(11) NOT NULL auto_increment,
  `manufacturer` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `model_year` int(11) default NULL,
  `model_name` varchar(255) default NULL,
  `user_id` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `description` text,
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

