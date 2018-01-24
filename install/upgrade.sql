ALTER TABLE __TABLE_PREFIX__options ADD UNIQUE (option_name);
ALTER TABLE __TABLE_PREFIX__chat RENAME TO __TABLE_PREFIX__chats;

INSERT IGNORE INTO __TABLE_PREFIX__options (option_name, option_value) VALUES('max_visitors', '2');
INSERT IGNORE INTO __TABLE_PREFIX__options (option_name, option_value) VALUES('background_color_3', '#5C84C9');
INSERT IGNORE INTO __TABLE_PREFIX__options (option_name, option_value) VALUES('color_3', '#FCFCFC');
INSERT IGNORE INTO __TABLE_PREFIX__options (option_name, option_value) VALUES('chat_position', 'right');

DROP TABLE IF EXISTS __TABLE_PREFIX__languages;
DROP TABLE IF EXISTS __TABLE_PREFIX__translations;

UPDATE __TABLE_PREFIX__options SET option_name = 'chat_timeout' WHERE option_name = 'chat_expire';
UPDATE __TABLE_PREFIX__options SET option_name = 'user_timeout' WHERE option_name = 'user_expire';
UPDATE __TABLE_PREFIX__options SET option_name = 'operator_timeout' WHERE option_name = 'online_timeout';

ALTER TABLE __TABLE_PREFIX__access_logs CHANGE log_ip ip_address VARCHAR(15) NOT NULL;
ALTER TABLE __TABLE_PREFIX__users MODIFY user_password VARCHAR(64) NOT NULL;
ALTER TABLE __TABLE_PREFIX__chat ADD new_activity TINYINT(1) NOT NULL DEFAULT '0' AFTER last_activity;
ALTER TABLE __TABLE_PREFIX__chat ADD user_agent VARCHAR(255) NOT NULL AFTER referer;
ALTER TABLE __TABLE_PREFIX__messages ADD operator_email VARCHAR(100) NOT NULL AFTER operator_name;
ALTER TABLE __TABLE_PREFIX__online_visitors ADD user_agent VARCHAR(255) NOT NULL AFTER referer;

DELETE FROM __TABLE_PREFIX__options WHERE option_name = 'console_interval';
DELETE FROM __TABLE_PREFIX__options WHERE option_name = 'chat_interval';
DELETE FROM __TABLE_PREFIX__options WHERE option_name = 'template_extension';
DELETE FROM __TABLE_PREFIX__options WHERE option_name = 'secret_word';

TRUNCATE TABLE __TABLE_PREFIX__user_groups;

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

CREATE TABLE IF NOT EXISTS __TABLE_PREFIX__visitor_sessions (
	chat_hash VARCHAR(40) NOT NULL,
	last_message_id INT(11) UNSIGNED NOT NULL,
	last_activity INT(11) UNSIGNED NOT NULL,
	visitor_key CHAR(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
