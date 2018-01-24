CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__access_logs (
	log_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	ip_address VARCHAR(15) NOT NULL,
	time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (log_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__blocked_visitors (
	blocked_visitor_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	ip_address VARCHAR(100) NOT NULL,
	description VARCHAR(255) NOT NULL,
	time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (blocked_visitor_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__canned_messages (
	message_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	canned_message TEXT,
	PRIMARY KEY (message_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO __TABLE_PREFIX__canned_messages VALUES(1, 'Hello!');
INSERT INTO __TABLE_PREFIX__canned_messages VALUES(2, 'Hello! How may I help you?');

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__chats (
	chat_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	chat_status TINYINT(1) NOT NULL DEFAULT '0',
	chat_hash VARCHAR(40) NOT NULL,
	time_start INT(11) UNSIGNED NOT NULL,
	time_end INT(11) UNSIGNED NOT NULL,
	last_activity INT(11) UNSIGNED NOT NULL,
	new_activity TINYINT(1) NOT NULL DEFAULT '0',
	user_typing TINYINT(1) NOT NULL DEFAULT '0',
	operator_typing TINYINT(1) NOT NULL DEFAULT '0',
	ip_address VARCHAR(100) NOT NULL,
	username VARCHAR(50) NOT NULL,
	email VARCHAR(100) NOT NULL,
	referer TEXT,
	user_agent VARCHAR(255) NOT NULL,
	department_name VARCHAR(100) NOT NULL,
	department_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (chat_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__departments (
	department_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	department_name VARCHAR(100) NOT NULL,
	department_email VARCHAR(100) NOT NULL,
	PRIMARY KEY (department_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO __TABLE_PREFIX__departments VALUES(1, 'Support', 'email@example.com');

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__department_operators (
	department_id INT(11) UNSIGNED NOT NULL,
	operator_id INT(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO __TABLE_PREFIX__department_operators VALUES(1, 1);

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__messages (
	message_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	chat_id INT(11) UNSIGNED NOT NULL,
	message TEXT,
	operator_name VARCHAR(100) NOT NULL,
	operator_email VARCHAR(100) NOT NULL,
	time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (message_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__online_visitors (
	visitor_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	ip_address VARCHAR(100) NOT NULL,
	referer TEXT,
	user_agent VARCHAR(255) NOT NULL,
	invitation_message TEXT,
	time INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (visitor_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__operators (
	operator_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	first_name VARCHAR(100) NOT NULL,
	last_name VARCHAR(100) DEFAULT NULL,
	last_activity INT(11) UNSIGNED NOT NULL,
	hide_online TINYINT(1) NOT NULL,
	PRIMARY KEY (operator_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__operator_sessions (
	operator_id INT(11) UNSIGNED NOT NULL,
	chat_id INT(11) UNSIGNED NOT NULL,
	chat_active TINYINT(1) UNSIGNED NOT NULL,
	last_chat_id INT(11) UNSIGNED NOT NULL,
	last_message_id INT(11) UNSIGNED NOT NULL,
	last_activity INT(11) UNSIGNED NOT NULL,
	operator_key CHAR(128) NOT NULL,
	PRIMARY KEY (operator_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__options (
	option_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	option_name VARCHAR(64) NOT NULL,
	option_value TEXT,
	PRIMARY KEY (option_id),
	UNIQUE KEY (option_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO __TABLE_PREFIX__options VALUES(1, 'site_title', '');
INSERT INTO __TABLE_PREFIX__options VALUES(2, 'absolute_url', '');
INSERT INTO __TABLE_PREFIX__options VALUES(3, 'absolute_path', '');
INSERT INTO __TABLE_PREFIX__options VALUES(4, 'admin_email', '');
INSERT INTO __TABLE_PREFIX__options VALUES(5, 'operator_timeout', '1800');
INSERT INTO __TABLE_PREFIX__options VALUES(6, 'ip_tracker_url', '');
INSERT INTO __TABLE_PREFIX__options VALUES(7, 'date_format', 'F d, Y');
INSERT INTO __TABLE_PREFIX__options VALUES(8, 'time_format', 'H:i:s');
INSERT INTO __TABLE_PREFIX__options VALUES(9, 'timezone', 'UTC');
INSERT INTO __TABLE_PREFIX__options VALUES(10, 'max_connections', '2');
INSERT INTO __TABLE_PREFIX__options VALUES(11, 'background_color_1', '#5BB75B');
INSERT INTO __TABLE_PREFIX__options VALUES(12, 'background_color_2', '#FFFFFF');
INSERT INTO __TABLE_PREFIX__options VALUES(22, 'background_color_3', '#5C84C9');
INSERT INTO __TABLE_PREFIX__options VALUES(13, 'color_1', '#FFFFFF');
INSERT INTO __TABLE_PREFIX__options VALUES(14, 'color_2', '#000000');
INSERT INTO __TABLE_PREFIX__options VALUES(23, 'color_3', '#FCFCFC');
INSERT INTO __TABLE_PREFIX__options VALUES(15, 'records_per_page', '10');
INSERT INTO __TABLE_PREFIX__options VALUES(16, 'access_logs', '1');
INSERT INTO __TABLE_PREFIX__options VALUES(17, 'user_timeout', '864000');
INSERT INTO __TABLE_PREFIX__options VALUES(18, 'chat_timeout', '600');
INSERT INTO __TABLE_PREFIX__options VALUES(19, 'charset', 'UTF-8');
INSERT INTO __TABLE_PREFIX__options VALUES(20, 'max_visitors', '2');
INSERT INTO __TABLE_PREFIX__options VALUES(21, 'chat_position', 'right');

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__users (
	user_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_id INT(11) UNSIGNED NOT NULL,
	user_email VARCHAR(100) NOT NULL,
	user_password VARCHAR(64) NOT NULL,
	user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	user_approved TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	user_created INT(11) UNSIGNED NOT NULL,
	last_login INT(11) UNSIGNED NOT NULL,
	last_ip VARCHAR(40) NOT NULL,
	remember_code VARCHAR(40) DEFAULT NULL,
	activation_code VARCHAR(40) DEFAULT NULL,
	PRIMARY KEY (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__user_groups (
	group_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_name VARCHAR(20) NOT NULL,
	group_permissions TEXT,
	PRIMARY KEY (group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__user_profiles (
	profile_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	first_name VARCHAR(50) NOT NULL,  
	last_name VARCHAR(50) DEFAULT NULL, 
	PRIMARY KEY (profile_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__visitor_sessions (
	chat_hash VARCHAR(40) NOT NULL,
	last_message_id INT(11) UNSIGNED NOT NULL,
	last_activity INT(11) UNSIGNED NOT NULL,
	visitor_key CHAR(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
