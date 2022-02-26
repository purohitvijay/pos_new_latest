<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['log_enabled'] = FALSE;
$config['order_number_config'] = array(
    'order_number_start' => 90000,
//    'order_number_prefix' => 'POSTKI',
//    'order_number_separator' => '/',
    'order_number_prefix' => '',
    'order_number_separator' => '',
    'order_number_size_in_digits' => 6,
);
$config['proximity_in_meters'] = 3000;
$config['coordinates_switch_proximity_in_meters'] = 1000;
$config['status_scan_cool_off_time_in_mins'] = 3;
$config['status_scan_cool_off_time_in_mins_when_same_day'] = 1;
$config['check_proximity'] = true;
$config['roles'] = array(
    'driver' => array('role' => 'driver', 'eod_required' => true),
    'warehousemanager' => array('role' => 'warehousemanager', 'eod_required' => false),
);

$config['status_type_group'] = array(
    'delivery' => array('booking_attended_by_driver', 'box_delivered'),
    'collection' => array('collection_attended_by_driver', 'box_collected'),
);

$config['statuses'] = array(
    'order_booked' => array('next' => 'booking_attended_by_driver', 'glyphicon' => 'fa-pencil-square-o', 'cash_collection' => false,
        'label_color' => '#f8a31f', 'display_text' => 'Booked', 'check_proximity' => false,
        'font_color' => 'maroon', 'voucher_cash' => false, 'responsibility_completed' => false, 'show_driver_drop_down_in_escalation' => false),
    'booking_attended_by_driver' => array('next' => 'box_delivered', 'glyphicon' => 'fa-truck fa-flip-horizontal', 'cash_collection' => false,
        'label_color' => '#8cbf26', 'display_text' => 'Booking Assigned', 'check_proximity' => false,
        'callback' => 'validateDriverStatutes', 'font_color' => 'black', 'voucher_cash' => false,
        'responsibility_completed' => false, 'show_driver_drop_down_in_escalation' => true),
    'box_delivered' => array('next' => 'collection_attended_by_driver', 'glyphicon' => 'fa-folder-open-o', 'cash_collection' => true,
        'label_color' => '#00aba9', 'display_text' => 'Delivered', 'check_proximity' => true,
        'callback' => 'validateDriverStatutes', 'responsibility_completed' => true,
        'font_color' => 'black', 'voucher_cash' => false, 'show_driver_drop_down_in_escalation' => false),
    'collection_attended_by_driver' => array('next' => 'box_collected', 'glyphicon' => 'fa-truck', 'cash_collection' => false,
        'label_color' => '#339933', 'display_text' => 'Collection Assigned', 'check_proximity' => false,
        'callback' => 'validateCollectionStatus', 'responsibility_completed' => false,
        'font_color' => 'black', 'voucher_cash' => false, 'show_driver_drop_down_in_escalation' => true),
    'box_collected' => array('next' => 'collected_at_warehouse', 'glyphicon' => 'fa-folder-o', 'cash_collection' => true,
        'label_color' => '#e671b8', 'display_text' => 'Collected', 'check_proximity' => true,
        'callback' => 'validateDriverStatutes', 'responsibility_completed' => true,
        'font_color' => 'black', 'voucher_cash' => true, 'show_driver_drop_down_in_escalation' => false),
    'collected_at_warehouse' => array('next' => '', 'glyphicon' => 'fa-thumbs-up', 'cash_collection' => false,
        'label_color' => '#a200ff', 'display_text' => 'Shipped', 'check_proximity' => false,
        'callback' => 'validateCollectionaAtWatehouseStatus', 'respo   nsibility_completed' => false,
        'font_color' => 'white', 'voucher_cash' => false, 'switch_to_jakarta' => true, 'show_driver_drop_down_in_escalation' => false),
);

$config['jakarta_statuses'] = array(
    'ready_for_receiving_at_jakarta' => array('next' => 'received_at_jakarta_warehouse', 'glyphicon' => 'glyphicon-boat', 'cash_collection' => false,
        'label_color' => 'green', 'display_text' => 'Ready @ Jkt', 'check_proximity' => false,
        'font_color' => 'lemonchiffon', 'voucher_cash' => false, 'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
    'received_at_jakarta_warehouse' => array('next' => 'delivered_at_jkt_picture_not_taken', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Received @ Jkt', 'check_proximity' => false,
        'callback' => '', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
     'delivered_at_jkt_picture_not_taken' => array('next' => 'delivered_at_jkt_picture_taken', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Delivered @ Jakarta, Picture not taken', 'check_proximity' => false,
        'callback' => 'sendMessageToCustomer', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
    'delivered_at_jkt_picture_taken' => array('next' => '', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Delivered @ Jakarta, Picture taken', 'check_proximity' => false,
        'callback' => '', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false)
);

 
$config['consolidated_statuses'] = array(
    'order_booked' => array('next' => 'booking_attended_by_driver', 'glyphicon' => 'fa-pencil-square-o', 'cash_collection' => false,
        'label_color' => '#f8a31f', 'display_text' => 'Booked', 'check_proximity' => false,
        'font_color' => 'maroon', 'voucher_cash' => false, 'responsibility_completed' => false, 'show_driver_drop_down_in_escalation' => false),
    'booking_attended_by_driver' => array('next' => 'box_delivered', 'glyphicon' => 'fa-truck fa-flip-horizontal', 'cash_collection' => false,
        'label_color' => '#8cbf26', 'display_text' => 'Booking Assigned', 'check_proximity' => false,
        'callback' => 'validateDriverStatutes', 'font_color' => 'black', 'voucher_cash' => false,
        'responsibility_completed' => false, 'show_driver_drop_down_in_escalation' => true),
    'box_delivered' => array('next' => 'collection_attended_by_driver', 'glyphicon' => 'fa-folder-open-o', 'cash_collection' => true,
        'label_color' => '#00aba9', 'display_text' => 'Delivered', 'check_proximity' => true,
        'callback' => 'validateDriverStatutes', 'responsibility_completed' => true,
        'font_color' => 'black', 'voucher_cash' => false, 'show_driver_drop_down_in_escalation' => false),
    'collection_attended_by_driver' => array('next' => 'box_collected', 'glyphicon' => 'fa-truck', 'cash_collection' => false,
        'label_color' => '#339933', 'display_text' => 'Collection Assigned', 'check_proximity' => false,
        'callback' => 'sendMsgToCustomerWhenCollectionAssigned', 'responsibility_completed' => false,
        'font_color' => 'black', 'voucher_cash' => false, 'show_driver_drop_down_in_escalation' => true),
    'box_collected' => array('next' => 'collected_at_warehouse', 'glyphicon' => 'fa-folder-o', 'cash_collection' => true,
        'label_color' => '#e671b8', 'display_text' => 'Collected', 'check_proximity' => true,
        'callback' => 'validateDriverStatutes', 'responsibility_completed' => true,
        'font_color' => 'black', 'voucher_cash' => true, 'show_driver_drop_down_in_escalation' => false),
    'collected_at_warehouse' => array('next' => 'ready_for_receiving_at_jakarta', 'glyphicon' => 'fa-thumbs-up', 'cash_collection' => false,
        'label_color' => '#a200ff', 'display_text' => 'Shipped', 'check_proximity' => false,
        'callback' => 'validateCollectionaAtWatehouseStatus', 'responsibility_completed' => false,
        'font_color' => 'white', 'voucher_cash' => false, 'show_driver_drop_down_in_escalation' => false),
    'ready_for_receiving_at_jakarta' => array('next' => 'received_at_jakarta_warehouse', 'glyphicon' => 'glyphicon-boat', 'cash_collection' => false,
        'label_color' => 'green', 'display_text' => 'Ready @ Jkt', 'check_proximity' => false,
        'font_color' => 'lemonchiffon', 'voucher_cash' => false, 'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
    'received_at_jakarta_warehouse' => array('next' => 'delivered_at_jkt_picture_not_taken', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Received @ Jkt', 'check_proximity' => false,
        'callback' => '', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
    'delivered_at_jkt_picture_not_taken' => array('next' => 'delivered_at_jkt_picture_taken', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Delivered @ Jakarta, Picture not taken', 'check_proximity' => false,
        'callback' => '', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false),
    'delivered_at_jkt_picture_taken' => array('next' => '', 'glyphicon' => 'glyphicon-sort', 'cash_collection' => false,
        'label_color' => 'silver', 'display_text' => 'Delivered @ Jakarta, Picture taken', 'check_proximity' => false,
        'callback' => '', 'font_color' => 'brown', 'voucher_cash' => false,
        'responsibility_completed' => 'yes', 'show_driver_drop_down_in_escalation' => false)
);


$config['outstanding_statuses'] = array(
    'box_collected', 'collected_at_warehouse', 'ready_for_receiving_at_jakarta', 'received_at_jakarta_warehouse', 'delivered_at_jkt_picture_not_taken', 'delivered_at_jkt_picture_taken');


$config['exclude_boxes_id'] = array('48');
$config['exclude_location_id'] = array('6');
$config['container_types'] = array('LCL', '40HC', '20GP');


$config['access_layer'] = array(
    'edit_status' => array(2, 42, 43, 44, 45, 46, 47, 59, 54, 58, 176, 110,171 , 177, 158, 69, 55, 191),
    'cancel_order' => array(2, 42, 43, 47),
    'luckydraw' => array(2, 42, 43),
    'miscellaneous' => array(2, 42, 43, 58, 65, 54, 176, 191),
    'order_edit_status' => array(2, 42, 43, 54, 58, 103, 65, 158, 176, 44, 80, 110, 171),
    'shipment_batch_load_plan_edit' => array(2, 42, 43, 54, 58, 65, 158, 176, 44, 110, 171),
    'restore_EOD_status' => array(2, 42, 43, 58, 65),
    'commission_module' => array(2, 42, 43, 158),
    'passport_img_update' => array(2, 42, 43, 54, 58, 65, 158, 176, 177, 182, 157, 183, 184, 189, 190, 191,211),
    'mass_order_status_update' => array(2, 42, 43, 58),
	'blacklist' => array(42, 43, 58),
);

$config['order_edit_lock_status'] = array("ready_for_receiving_at_jakarta", "received_at_jakarta_warehouse");
$config['order_completed_lock_status'] = array();
$config['picture_receiving_date_status'] = "collected_at_warehouse";

$config['geo_type'] = array('singapore' => 'Singapore', 'jakarta' => 'Jakarta', 'all' => 'All');

$config['per_box_collected_amount'] = 10;
$config['redelivery_amount'] = 5.00;

$config['delivery_commission_base_amount'] = 5.00;

$config['image_upload'] = array(
    'upload_dir' => './assets/dynamic/jkt_images/archives',
    'extraction_dir' => './assets/dynamic/jkt_images/extracted_images',
    'allowed_extensions' => 'zip',
);

$config['tracking_status'] = array(
    'box_collected',
    'ready_for_receiving_at_jakarta',
    'received_at_jakarta_warehouse',
    'recipient_received'
);
$config['order_box_edit_permissible_status'] = 
        'received_at_jakarta_warehouse';

$config['qr_code_bg_image_path'] = './assets/img/logo-big.png';

$config['check_proximity_jkt'] = false;
$config['proximity_in_meters_jkt'] = 800;
$config['coordinates_switch_proximity_in_meters_jkt'] = 1000;

$config['CURLOPT_RETURNTRANSFER'] = '1';
$config['manually_escalated_api'] = 'admin/api/updateStatusJakarta';
$config['CURLOPT_USERAGENT'] = 'Codular Sample cURL Request';
$config['CURLOPT_POST'] = '1';
$config['manually_order_lattitude'] = '0';
$config['manually_order_longitude'] =  '0';
$config['manually_order_dc_lattitude'] = '0';
$config['manually_order_dc_longitude'] =  '0';
$config['order_manual_entry'] = '1';
$config['distribution_center_id'] = '-1';
$config['status_escalation_type'] = 'manual';
$config['get_oldest_image_data_in_days'] = '180';
$config['get_orders_older_data_by_date'] = '2013-12-31';
$config['database_user'] = 'postki_user';
$config['database_password'] = 'postki@12345';
$config['database_host'] = '101.100.216.68';
$config['database_name'] = 'postki_postki'; 
$config['sql_zip_file_backup_path'] = '/home/postkicrm/public_html/YearlyDB/';
$config['get_old_orders_active_status'] = array('delivered_at_jkt_picture_not_taken','delivered_at_jkt_picture_taken');

$config['bar_code_order_no_img_path'] = array(
    'bar_codes' => './assets/dynamic/bar_codes',
    'order_nos' => './assets/dynamic/order_nos'
);
$config['check_shipment_receiving_data_in_days'] = '1095';

$config['configurable_password'] = 'P4U5';

//set for query result limit
$config['set_query_result'] = 10;//(where 0 = retireve all, else return no. of rows set)