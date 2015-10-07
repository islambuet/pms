<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['table_users'] = 'ait_user_login';
$config['table_user_group'] = 'pms_user_group';
$config['table_task'] = 'pms_task';
$config['table_user_group_role'] = 'pms_user_group_role';
$config['table_history'] = 'pms_history';

//setup tables
$config['table_crops'] = 'pms_crops';
$config['table_classifications'] = 'pms_classifications';
$config['table_types'] = 'pms_types';
$config['table_skin_types'] = 'pms_skin_types';
$config['table_varieties'] = 'pms_varieties';
//$config['table_variety_price_history'] = 'pms_variety_price_history';
$config['table_consignment'] = 'pms_consignment';
$config['table_container'] = 'pms_container';

//location setup tables
$config['table_zones'] = 'pms_zones';
$config['table_territories'] = 'pms_territories';
$config['table_districts'] = 'pms_districts';
$config['table_upazilas'] = 'pms_upazilas';
$config['table_unions'] = 'pms_unions';
$config['table_customers'] = 'pms_customers';

//bookings
$config['table_bookings'] = 'pms_bookings';
$config['table_booked_varieties'] = 'pms_booked_varieties';
$config['table_booking_payments'] = 'pms_booking_payments';