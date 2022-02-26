<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

$config['cost_config'] = array(
    array(
        'section_text' => 'BOX / MATERIAL COSTING (PER ORDER QTY)',
        'currency' => 'SGD',
        'geographical' => 'domestic',
        'section' => 'box_material_costing',
        'scheme' => 'per_order_quantity',
        'has_counter_overseas' => false,
        'data' => array(
            array('text' => 'GIANT (G)', 'type' => 'system', 'default_value' => '8', 'name' => 'GIANT (G)'),
            array('text' => 'JUMBO (J)', 'type' => 'system', 'default_value' => '6', 'name' => 'JUMBO (J)'),
            array('text' => 'HALF (H)', 'type' => 'system', 'default_value' => '5', 'name' => 'HALF (H)'),
            array('text' => 'SPECIAL PACKS (SP)', 'type' => 'system', 'default_value' => '10', 'name' => 'SPECIAL PACKS (SP)'),
            array('text' => 'REPACK (RP)', 'type' => 'system', 'default_value' => '10', 'name' => 'REPACK (RP)')
        )),
    array(
        'section_text' => 'LOCAL COSTING (PER ORDER)',
        'currency' => 'SGD',
        'geographical' => 'domestic',
        'section' => 'local_costing',
        'scheme' => 'per_order',
        'has_counter_overseas' => true,
        'data' => array(
            array('text' => 'DELIVERY & COLLECTION', 'type' => 'system', 'default_value' => '22', 'name' => 'DELIVERY & COLLECTION'),
            array('text' => 'RE-DELIVERY', 'type' => 'system', 'default_value' => '5', 'name' => 'RE-DELIVERY'),
            array('text' => 'COLLECTION SP', 'type' => 'custom', 'name' => 'COLLECTION SP'),
            array('text' => 'COMPENSATION', 'type' => 'custom', 'name' => 'COMPENSATION')
        )),
    array(
        'section_text' => 'OVERSEAS COSTING (PER ORDER QTY)',
        'currency' => 'IDR',
        'geographical' => 'overseas',
        'section' => 'overseas_costing',
        'scheme' => 'per_order_quantity',
        'has_counter_overseas' => false,
        'data' => array(
            array('text' => 'JAWA BARAT', 'type' => 'system', 'default_value' => '200000', 'name' => 'Jawa Barat'),
            array('text' => 'JAWA TENGAH', 'type' => 'system', 'default_value' => '200000', 'name' => 'Jawa Tengah'),
            array('text' => 'JAWA TIMUR', 'type' => 'system', 'default_value' => '200000', 'name' => 'Jawa Timur'),
            array('text' => 'JAKARTA', 'type' => 'system', 'default_value' => '200000', 'name' => 'Jakarta'),
            array('text' => 'LAMPUNG', 'type' => 'system', 'default_value' => '300000', 'name' => 'Lampung'),
            array('text' => 'MADURA', 'type' => 'system', 'default_value' => '300000', 'name' => 'Madura'),
            array('text' => 'LUAR JAWA', 'type' => 'custom', 'name' => 'Luar Jawa'),
            array('text' => 'SPECIAL PACKS (SP)', 'type' => 'custom', 'name' => 'Special Pack'),
            array('text' => 'REPACK (RP)', 'type' => 'custom', 'name' => 'Repack'),
        )),
    array(
        'section_text' => 'AGENT COMMISSION (PER ORDER)',
        'currency' => 'SGD',
        'geographical' => 'domestic',
        'section' => 'agent_commission',
        'scheme' => 'per_order',
        'has_counter_overseas' => false,
        'data' => array(
            array('text' => 'GIANT (G)', 'type' => 'system', 'default_value' => '10', 'name' => 'Giant')
        )),
    array(
        'section_text' => "SHIPMENT - 40' HC CONTAINER (PER SHP)",
        'currency' => 'SGD',
        'geographical' => 'domestic',
        'section' => 'shipment_container_40',
        'scheme' => 'per_ship',
        'container_type' => '40HC',
        'has_counter_overseas' => true,
        'data' => array(
            array('text' => 'TRUCKING', 'type' => 'custom', 'name' => 'Trucking'),
            array('text' => 'LIFTING', 'type' => 'custom', 'name' => 'Lifting'),
            array('text' => 'PORTNET', 'type' => 'custom', 'name' => 'Portnet'),
            array('text' => 'FREIGHT', 'type' => 'custom', 'name' => 'Freight'),
            array('text' => 'DOCUMENTATION', 'type' => 'custom', 'name' => 'Documentation'),
            array('text' => 'SEAL', 'type' => 'custom', 'name' => 'Seal'),
            array('text' => 'FUEL', 'type' => 'custom', 'name' => 'Fuel'),
            array('text' => 'LOADING', 'type' => 'system', 'default_value' => '350', 'name' => 'Loading'),
            array('text' => 'MANPOWER', 'type' => 'custom', 'name' => 'Mainpower'),
            array('text' => 'COURIER', 'type' => 'custom', 'name' => 'Courier'),
            array('text' => 'STAFF', 'type' => 'custom', 'name' => 'Staff'),
        )),
    array(
        'section_text' => "SHIPMENT - 40' HC CONTAINER OVERSEAS COSTING (PER SHIP)",
        'currency' => 'IDR',
        'geographical' => 'overseas',
        'section' => 'shipment_container_40',
        'scheme' => 'per_ship',
        'container_type' => '40HC',
        'has_counter_overseas' => false,
        'data' => array(
            array('text' => 'HANDLING LUAR JAWA', 'type' => 'system', 'default_value' => '350000', 'name' => 'Handling Luar Jawa'),
            array('text' => 'CUSTOMS TAX', 'type' => 'system', 'default_value' => '19000000', 'name' => 'Customs Tax'),
        )),
    array(
        'section_text' => "SHIPMENT - 20' GP CONTAINER (PER SHP)",
        'currency' => 'SGD',
        'geographical' => 'domestic',
        'section' => 'shipment_container_20',
        'scheme' => 'per_ship',
        'container_type' => '20GP',
        'has_counter_overseas' => true,
        'data' => array(
            array('text' => 'TRUCKING', 'type' => 'custom', 'name' => 'Trucking'),
            array('text' => 'LIFTING', 'type' => 'custom', 'name' => 'Lifting'),
            array('text' => 'PORTNET', 'type' => 'custom', 'name' => 'Pornet'),
            array('text' => 'FREIGHT', 'type' => 'custom', 'name' => 'Freight'),
            array('text' => 'DOCUMENTATION', 'type' => 'custom', 'name' => 'Documentation'),
            array('text' => 'SEAL', 'type' => 'custom', 'name' => 'Seal'),
            array('text' => 'FUEL', 'type' => 'custom', 'name' => 'Fuel'),
            array('text' => 'LOADING', 'type' => 'system', 'default_value' => '250', 'name' => 'Loading'),
            array('text' => 'MANPOWER', 'type' => 'custom', 'name' => 'Manpower'),
            array('text' => 'COURIER', 'type' => 'custom', 'name' => 'Courier'),
            array('text' => 'STAFF', 'type' => 'custom', 'name' => 'Staff')
        )),
    array(
        'section_text' => "SHIPMENT - 20' HC CONTAINER OVERSEAS COSTING (PER SHIP)",
        'currency' => 'IDR',
        'geographical' => 'overseas',
        'section' => 'shipment_container_20',
        'scheme' => 'per_ship',
        'container_type' => '20GP',
        'has_counter_overseas' => false,
        'data' => array(
            array('text' => 'HANDLING LUAR JAWA', 'type' => 'system', 'default_value' => '350000', 'name' => 'Handling Luar Jawa'),
            array('text' => 'CUSTOMS TAX', 'type' => 'system', 'default_value' => '11000000', 'name' => 'Customs Tax'),
        )),
);

$config['shipment_cost_eligible_locations'] = array(
    11,  //Jakarta
    2,  //Jawa Barat
    3,  //Jawa Tengah
    4,  //Jawa Timur
    5,  //Lampung
    12,  //Madura
    6,  //Luar Jawa
    ); 

$config['shipment_cost_eligible_boxes'] = array(
    1,  //Half 
    2,  //Jumbo
    3,  //Giant
    55,  //KPRIG
    ); 
$config['shipment_cost_report_eligible_boxes'] = array(
    1,  //Half 
    2,  //Jumbo
    3,  //Giant
    20,  //SPECIAL PACKS
    18,  //REPACK
    ); 

$config['master_locations_line_items'] = array(
        'Jawa Barat','Jawa Tengah','Jawa Timur','Lampung','Luar Jawa','Jakarta','Madura'
    );
$config['master_locations_costing_line_items'] = array(
        'Jakarta','Lampung','Madura','Luar Jawa','Special Pack','Repack'
    );
$config['master_special_pack_line_items'] = array(
        'Special Pack'
    );
$config['master_freight_line_items'] = array(
        'Handling Luar Jawa','Customs Tax'
    );

$config['master_locations_local_costing_line_items'] = array(
        'DELIVERY & COLLECTION','COLLECTION SP','COMPENSATION',
    );
$config['master_freight_local_costing_line_items'] = array(
        "Trucking","Lifting","Portnet","Freight","Documentation",",Seal","Fuel","Loading","Mainpower","Courier","Staff"
    );
$config['custom_field_exchange_rate'] = 10000;