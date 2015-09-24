Friends Razors Edge Integration
=========

This plugin for the [Friends Platform](https://github.com/DallasMuseumArt/OctoberFriends) syncronizes data from a razors edge database into the friends platform.

## Overview

An artisan command is provided that will syncronize records from your Razors Edge installation into a new table in the Friends Platform.  The syncronization will create a record for all records in your RE database.  If an account exists then the record will be automatically connected to the appropriate user account using the email address as a primary identifier.

## Installation

### Configure your server to connect to an MS-SQL database
In order for the friends platform to connect to your razors edge server you will need to install and configure support for MS-SQL in PHP.  The most common method is to install and confingure [FreeTDS](http://www.freetds.org/) documentation is provided on the [FreeTDS website](http://www.freetds.org/userguide/php.htm)

### Configure OctoberCMS to connect to your database
In config/database.php add a new database as follows:

	'razorsedge' => array(
		'driver'   => 'sqlsrv',
		'host'     => MSSQL_HOSTNAME,
		'port'     => '1433',
		'database' => 're7',
		'username' => USERNAME,
		'password' => PASSWORD,
		'prefix'   => '', 
     ),  

### Setup cron

The cron task to syncronize data can be very cpu intensive.  It is recommended that it is configured to run in half hour intervals during non business hours if possible.

	*/30 * * * * php /PATH_TO_OCTOBERCMS_ROOT/artisan friends:sync-razorsedge
