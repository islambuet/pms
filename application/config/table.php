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
$config['table_consignment'] = 'pms_consignment';
$config['table_container'] = 'pms_container';
$config['table_container_varieties'] = 'pms_container_varieties';
$config['table_variety_price'] = 'pms_variety_price';
$config['table_setup_container_arrival'] = 'pms_setup_container_arrival';
$config['table_setup_container_opening'] = 'pms_setup_container_opening';
$config['table_setup_vehicle_loading'] = 'pms_setup_vehicle_loading';

//location setup tables
$config['table_zones'] = 'pms_zones';
$config['table_territories'] = 'pms_territories';
$config['table_districts'] = 'pms_districts';
$config['table_upazilas'] = 'pms_upazilas';
$config['table_unions'] = 'pms_unions';
$config['table_customers'] = 'pms_customers';

//bookings
$config['table_bookings'] = 'pms_bookings';
$config['table_preliminary_varieties'] = 'pms_preliminary_varieties';
$config['table_permanent_varieties'] = 'pms_permanent_varieties';
$config['table_payments'] = 'pms_payments';

//allocation
$config['table_allocation_varieties'] = 'pms_allocation_varieties';
$config['table_quantity_color'] = 'pms_quantity_color';

//delivery allocation
$config['table_delivery_allocation_varieties'] = 'pms_delivery_allocation_varieties';

$config['table_data_container_arrival'] = 'pms_data_container_arrival';
$config['table_data_container_opening'] = 'pms_data_container_opening';
