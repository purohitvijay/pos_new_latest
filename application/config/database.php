<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = '123456';
$db['default']['database'] = 'new_post';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['live']['hostname'] = 'localhost';
$db['live']['username'] = 'root3';
$db['live']['password'] = '!3Hands0me';
$db['live']['database'] = 'admin_postkicrm';
$db['live']['dbdriver'] = 'mysql';
$db['live']['dbprefix'] = '';
$db['live']['pconnect'] = FALSE;
$db['live']['db_debug'] = TRUE;
$db['live']['cache_on'] = FALSE;
$db['live']['cachedir'] = '';
$db['live']['char_set'] = 'utf8';
$db['live']['dbcollat'] = 'utf8_general_ci';
$db['live']['swap_pre'] = '';
$db['live']['autoinit'] = TRUE;
$db['live']['stricton'] = FALSE;

$db['imran']['hostname'] = 'localhost';
$db['imran']['username'] = 'postki_stage';
$db['imran']['password'] = '!3Hands0me';
$db['imran']['database'] = 'postki_staging';
$db['imran']['dbdriver'] = 'mysql';
$db['imran']['dbprefix'] = '';
$db['imran']['pconnect'] = FALSE;
$db['imran']['db_debug'] = TRUE;
$db['imran']['cache_on'] = FALSE;
$db['imran']['cachedir'] = '';
$db['imran']['char_set'] = 'utf8';
$db['imran']['dbcollat'] = 'utf8_general_ci';
$db['imran']['swap_pre'] = '';
$db['imran']['autoinit'] = TRUE;
$db['imran']['stricton'] = FALSE;


$db['mdi']['hostname'] = 'crm.postki.mdiapp.com';
$db['mdi']['username'] = 'postki_crm';
$db['mdi']['password'] = '%Q))W{i[@8=3';
$db['mdi']['database'] = 'postki_crm';
$db['mdi']['dbdriver'] = 'mysql';
$db['mdi']['dbprefix'] = '';
$db['mdi']['pconnect'] = FALSE;
$db['mdi']['db_debug'] = TRUE;
$db['mdi']['cache_on'] = FALSE;
$db['mdi']['cachedir'] = '';
$db['mdi']['char_set'] = 'utf8';
$db['mdi']['dbcollat'] = 'utf8_general_ci';
$db['mdi']['swap_pre'] = '';
$db['mdi']['autoinit'] = TRUE;
$db['mdi']['stricton'] = FALSE;

$db['luckyDrawLive']['hostname'] = '101.100.216.68';
$db['luckyDrawLive']['username'] = 'postki_user';
$db['luckyDrawLive']['password'] = 'postki@12345';
$db['luckyDrawLive']['database'] = 'postki_postki';
$db['luckyDrawLive']['dbdriver'] = 'mysql';
$db['luckyDrawLive']['dbprefix'] = '';
$db['luckyDrawLive']['pconnect'] = FALSE;
$db['luckyDrawLive']['db_debug'] = TRUE;
$db['luckyDrawLive']['cache_on'] = FALSE;
$db['luckyDrawLive']['cachedir'] = '';
$db['luckyDrawLive']['char_set'] = 'utf8';
$db['luckyDrawLive']['dbcollat'] = 'utf8_general_ci';
$db['luckyDrawLive']['swap_pre'] = '';
$db['luckyDrawLive']['autoinit'] = TRUE;
$db['luckyDrawLive']['stricton'] = FALSE;

//$db['luckyDrawLive']['hostname'] = 'crm.postki.mdiapp.com';
//$db['luckyDrawLive']['username'] = 'postki_crm';
//$db['luckyDrawLive']['password'] = '%Q))W{i[@8=3';
//$db['luckyDrawLive']['database'] = 'postki_crm';
//$db['luckyDrawLive']['dbdriver'] = 'mysql';
//$db['luckyDrawLive']['dbprefix'] = '';
//$db['luckyDrawLive']['pconnect'] = FALSE;
//$db['luckyDrawLive']['db_debug'] = TRUE;
//$db['luckyDrawLive']['cache_on'] = FALSE;
//$db['luckyDrawLive']['cachedir'] = '';
//$db['luckyDrawLive']['char_set'] = 'utf8';
//$db['luckyDrawLive']['dbcollat'] = 'utf8_general_ci';
//$db['luckyDrawLive']['swap_pre'] = '';
//$db['luckyDrawLive']['autoinit'] = TRUE;
//$db['luckyDrawLive']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */


