<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Enable/Disable Migrations
|--------------------------------------------------------------------------
|
| Migrations are disabled by default but should be enabled 
| whenever you intend to do a schema migration.
|
*/
$config['migration_type'] = 'timestamp';
$config['migration_table'] = 'migrations';
$config['migration_enabled'] = TRUE;


/*
|--------------------------------------------------------------------------
| Migrations version
|--------------------------------------------------------------------------
|
| This is used to set migration version that the file system should be on.
| If you run $this->migration->latest() this is the version that schema will
| be upgraded / downgraded to.
|
*/
//$config['migration_version'] = 20161220065100;
$config['migration_version'] = 20171212114105;
//$config['migration_version'] = 20170406033859;

/*
|--------------------------------------------------------------------------
| Migrations Path
|--------------------------------------------------------------------------
|
| Path to your migrations folder.
| Typically, it will be within your application path.
| Also, writing permission is required within the migrations path.
|
*/
$config['migration_path'] = APPPATH . 'migrations/';
//$config['migration_path'] = APPPATH . '/application/config/migrations/';

/* End of file migration.php */
/* Location: ./application/config/migration.php */
