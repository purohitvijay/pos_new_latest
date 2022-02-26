<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['user'] = 'postki';
$config['password'] = 'postkisms';
$config['option'] = 'send';

$config['tracing_pic_url'] = "http://postki.com/tracking";

$config['msg_for_luar_jawa'] = "Dear <CUSTOMER_NAME>. Box no. <ORDER_NUMBER> was received by <RECEIPENT_NAME>, on the <TIME>, Thank You For Your Support.";

$config['msg_for_other_dc'] = "YTH <CUSTOMER_NAME>.Box <ORDER_NUMBER> sudah diterima <RECEIPENT_NAME>,pada <TIME>.Download foto dalam 2 hari di https://postkiapp.com/smstracking.Terima kasih.";

$config['msg_for_order_collection_assigned'] = "YTH <CUSTOMER_NAME>, box <ORDER_NUMBER> mohon disiapkan dan fomulir diisi lengkap hari ini <DATE> utk pengambilan. Telp 62974805 utk penerangan. Terima kasih POS TKI";

$config['msg_for_ship_onboard'] = "Yang Terhormat <CUSTOMER_NAME>, kotak nomor <ORDER_NUMBER> sudah dilayarkan pada tanggal <SOB_DATE>. Terimakasih";

$config['CURLOPT_RETURNTRANSFER'] = '1';
$config['CURLOPT_URL'] = 'http://www.sms.sg/http/sendmsg';
$config['CURLOPT_USERAGENT'] = 'Codular Sample cURL Request';
$config['CURLOPT_POST'] = '1';
$config['senderid'] = 'POSTKI';

$config['luar_jawa_location_name'] = "Luar Jawa";