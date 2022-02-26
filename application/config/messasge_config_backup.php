<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['user'] = 'postki';
$config['password'] = 'postkisms';
$config['option'] = 'send';

$config['tracing_pic_url'] = "http://postki.com/tracking";

$config['msg_for_luar_jawa'] = "Dear <CUSTOMER_NAME>. Box no. <ORDER_NUMBER> was received by <RECEIPENT_NAME>, on the <TIME>, Thank You For Your Support.";

$config['msg_for_other_dc'] = "Dear <CUSTOMER_NAME>. Box no. <ORDER_NUMBER> was received by <RECEIPENT_NAME>, on the <TIME>. Photo available at http://postkicrm.com/smstracking. Thank You For Your Support.";

$config['msg_for_order_collection_assigned'] = "Dear <CUSTOMER_NAME>, please ensure your box <ORDER_NUMBER> and form are ready for collection today <DATE>. For enquiries, please call 62974805. Thank you.";

$config['CURLOPT_RETURNTRANSFER'] = '1';
$config['CURLOPT_URL'] = 'http://www.sms.sg/http/sendmsg';
$config['CURLOPT_USERAGENT'] = 'Codular Sample cURL Request';
$config['CURLOPT_POST'] = '1';
$config['senderid'] = 'POSTKI';

$config['luar_jawa_location_name'] = "Luar Jawa";