<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['user'] = 'postki';
$config['password'] = 'postkisms';
$config['option'] = 'send';

$config['tracing_pic_url'] = "http://postki.com/tracking";

$config['msg_for_luar_jawa'] = "Dear <CUSTOMER_NAME>. Box no. <ORDER_NUMBER> was received by <RECEIPENT_NAME>, on the <TIME>, Thank You For Your Support.";

$config['msg_for_other_dc'] = "Dear <CUSTOMER_NAME>. Box no. <ORDER_NUMBER> was received by <RECEIPENT_NAME>, on the <TIME>. Photo available at https://postkiapp.com/smstracking. Thank You For Your Support.";

$config['msg_for_order_collection_assigned'] = "YTH <CUSTOMER_NAME>, box <ORDER_NUMBER> mohon disiapkan dan fomulir diisi lengkap hari ini <DATE> utk pengambilan. Telp 62974805 utk penerangan. Terima kasih POS TKI";

$config['msg_for_ship_onboard'] = "Dear Ship onboard is updated, Thank You For Your Support.";

$config['CURLOPT_RETURNTRANSFER'] = '1';
$config['CURLOPT_URL'] = 'http://www.sms.sg/http/sendmsg';
$config['CURLOPT_USERAGENT'] = 'Codular Sample cURL Request';
$config['CURLOPT_POST'] = '1';
$config['senderid'] = 'POSTKI';

$config['luar_jawa_location_name'] = "Luar Jawa";