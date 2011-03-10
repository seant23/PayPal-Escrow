<?php

$mysql_host = 'localhost'; //Leave at localhost
$mysql_user = 'greedymaster'; //DB User
$mysql_pass = 'greedyaccess1'; //DB Pass
$mysql_db = 'greedy_1'; //DB Name


$debug = true;

if($debug) {
	@ini_set("display_errors", "on");
	@ini_set("display_startup_errors", "on");
	@error_reporting(E_ALL);
	ob_start();
}
  
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
mysql_select_db($mysql_db, $db);


$_POST['cmd'] = '_notify-validate';
$req = http_build_query($_POST);

$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: ".strlen($req)."\r\n\r\n";
$fp = fsockopen("www.paypal.com", 80, $errno, $errstr, 30);


if(!$fp) {
  mail("sean@lookin3d.com", "IPN - GreedyPeople - Failed to open HTTP connection!", print_r($GLOBALS, 1));
  $res = "FAILED";
} else {
  fputs($fp, $header . $req);
  
  while(!feof($fp)) {
    $res = fgets($fp, 1024);
    
    if(strcmp($res, "VERIFIED") == 0) {
      
	$query = <<<SQL
INSERT INTO `paypal_transaction` ( 
	`invoice` , 
	`receiver_email` , 
	`item_name` , 
	`item_number` , 
	`quantity` , 
	`payment_status` , 
	`pending_reason` , 
	`payment_date` , 
	`mc_gross` , 
	`mc_fee` , 
	`tax` , 
	`mc_currency` , 
	`txn_id` , 
	`txn_type` , 
	`first_name` , 
	`last_name` , 
	`address_street` , 
	`address_city` , 
	`address_state` , 
	`address_zip` , 
	`address_country` , 
	`address_status` , 
	`payer_email` , 
	`payer_status` , 
	`payment_type` , 
	`notify_version` , 
	`verify_sign` , 
	`referrer_id`,
	`custom`
) VALUES (
	"{$_POST['invoice']}",
	"{$_POST['receiver_email']}",
	"{$_POST['item_name']}",
	"{$_POST['item_number']}",
	"{$_POST['quantity']}",
	"{$_POST['payment_status']}",
	"{$_POST['pending_reason']}",
	"{$_POST['payment_date']}",
	"{$_POST['mc_gross']}" , 
	"{$_POST['mc_fee']}" , 
	"{$_POST['tax']}" , 
	"{$_POST['mc_currency']}" , 
	"{$_POST['txn_id']}" , 
	"{$_POST['txn_type']}" , 
	"{$_POST['first_name']}" , 
	"{$_POST['last_name']}" , 
	"{$_POST['address_street']}" , 
	"{$_POST['address_city']}" , 
	"{$_POST['address_state']}" , 
	"{$_POST['address_zip']}" , 
	"{$_POST['address_country']}" , 
	"{$_POST['address_status']}" , 
	"{$_POST['payer_email']}" , 
	"{$_POST['payer_status']}" , 
	"{$_POST['payment_type']}" , 
	"{$_POST['notify_version']}" , 
	"{$_POST['verify_sign']}" , 
	"{$_POST['referrer_id']}",
	"{$_POST['custom']}"
)
SQL;

			$result = mysql_query($query);
			$transId = mysql_insert_id();
			
			$custom = array();
			parse_str($_POST['custom'], $custom);
			
			if(isset($custom['doneWith'])) {
				mysql_query($q3 = "UPDATE `escrow` SET `status`='S' WHERE `id` = '{$custom['doneWith']}'");
			} else {
				if(isset($custom['escrow_creation'])) {
					if(!isset($custom['bonus'])) {
						$escrow_payment = round(($_POST['payment_gross'] - .3) - ($_POST['payment_gross'] * 0.029), 2);
						$status = 'D';
						$bonus = '0';
					} else {
						$status = 'S';
						$bonus = '1';
						$escrow_payment = $_POST['payment_gross'];
					}
					
					
					mysql_query($q3 = "INSERT INTO `escrow` (offer_ID, status, amount, bonus) VALUES('{$custom['escrow_creation']}','$status', '$escrow_payment', '$bonus')");
					$escrowID = mysql_insert_id();
					
					mysql_query($q2 = "INSERT INTO `escrow_transaction` VALUES('{$escrowID}', '$transId')");
				}
			}
		}
	}
}

if($debug) {
	$ob = ob_get_contents();
	mail("sean@lookin3d.com", "IPN LOG", "\n\nOB:\n\n$ob" . "\n\nGLOBAL:\n\n" . print_r($GLOBALS, 1));
}

fclose($fp);
mysql_close($db);
