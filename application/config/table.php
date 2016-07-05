<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//those tables from login site
$config['table_setup_user'] = 'setup_user';
$config['table_setup_user_info'] = 'setup_user_info';
$config['table_setup_users_other_sites'] = 'setup_users_other_sites';
$config['table_system_other_sites'] = 'system_other_sites';
$config['table_other_sites_visit'] = 'other_sites_visit';
$config['table_setup_designation'] = 'setup_designation';


//pms system setup tables
$config['table_system_assigned_group'] = 'pms_system_assigned_group';
$config['table_system_user_group'] = 'pms_system_user_group';
$config['table_system_task'] = 'pms_system_task';
$config['table_system_user_group_role'] = 'pms_system_user_group_role';
$config['table_history'] = 'pms_history';
$config['table_history_hack'] = 'pms_history_hack';
//$config['table_system_assigned_area'] = 'bms_system_assigned_area';
$config['table_system_site_offline'] = 'pms_system_site_offline';

//pms site tables
//location
$config['table_location_zones'] = 'pms_location_zones';
$config['table_location_territories'] = 'pms_location_territories';
$config['table_location_districts'] = 'pms_location_districts';
$config['table_location_upazilas'] = 'pms_location_upazilas';
$config['table_location_unions'] = 'pms_location_unions';
//Crop Classifications
$config['table_cc_crops'] = 'pms_cc_crops';
$config['table_cc_classifications'] = 'pms_cc_classifications';
$config['table_cc_types'] = 'pms_cc_types';
$config['table_cc_skin_types'] = 'pms_cc_skin_types';
$config['table_cc_varieties'] = 'pms_cc_varieties';