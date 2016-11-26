# Database-admin
<b>Author:</b> Niesuch <br />
<b>Programming language:</b> PHP <br />

## Table of Contents:
1. [Description](https://github.com/niesuch/database-admin#description)
2. [How to install](https://github.com/niesuch/database-admin#how-to-install)
3. [Starting configuration](https://github.com/niesuch/database-admin#starting-configuration)
4. [Changelogs](https://github.com/niesuch/database-admin#changelogs)
5. [Copyright and License](https://github.com/niesuch/database-admin#copyright-and-license)

## Description:
![Alt text](/docs/screens/screen1.jpg)

Application updates the chosen database. User can paste/write query in the textarea, choose databases which are going to be updated and press button "Go" to perform this action. Application has history query function. To check a particular query user should doubleclick the select record. Query is exported to textarea field where it can be modified and used again. There were defined three types of logs:  

* Query log
* Error log
* History query log

which involve standard information about operations performed in application. It is possible that user don't want to use these functions. We can turn them off or add other type.

Other functions:
* Paginations
* Sorter
* Ini config

## How to install
* Download repository
* Copy files to your webserver catalog (you can use for example XAMPP if you work on localhost)
* Configure according to instruction in "Starting configuration" section (below)
* Open in your browser

## Starting configuration:
- <b>env.php</b> <br />
Configuration application.ini path and environment name. For example:
```php
define('APPLICATION_INI', '../config/application.ini');
define('APPLICATION_ENV', 'niesuch');
```
- <b>config/application.ini</b> <br />
Configuration main database and logs data. Important! You must using the same name which exist in env.php (in my example is 'niesuch'). Example:
```ini
[niesuch]
; base config
db[host] = "localhost"
db[user] = "root"
db[password] = ""

; logs config
log_query[base] = "logs"
log_query[table] = "log_query"
log_query[fields] = "id, base_name, query, date"

log_error[base] = "logs"
log_error[table] = "log_error"
log_error[fields] = "id, query, error, date"

log_history_query[base] = "logs"
log_history_query[table] = "log_history_query"
log_history_query[fields] = "id, base_name, query, date"
```

- <b>docs/create_log.sql</b> <br />
If my table structure is appropriate, you can import them from this file to your database. Otherwise you must change configuration in application.ini.
```sql
CREATE DATABASE IF NOT EXISTS `logs`;
USE `logs`;

CREATE TABLE IF NOT EXISTS `log_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `query` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `log_error` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`query` text COLLATE utf8_polish_ci NOT NULL,
	`error` text COLLATE utf8_polish_ci NOT NULL,
	`date` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE IF NOT EXISTS `log_history_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base_name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `query` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
```

Now you can run application!

## Changelogs
Click on this link to see changelogs: [CHANGELOG](https://github.com/niesuch/database-admin/releases)

## Copyright and License
Copyright 2016 Niesuch, Inc. Code released under the [MIT license](https://github.com/niesuch/database-admin/blob/master/LICENSE.md).

[Back to top](https://github.com/niesuch/database-admin/blob/master/README.md#database-admin)
