<?php
/*
=====================================================
vldPersonals - by VLD Interactive
----------------------------------------------------
http://www.vldpersonals.com/
http://www.vldinteractive.com/
-----------------------------------------------------
Copyright (c) 2005-2008 VLD Interactive
=====================================================
THIS IS COPYRIGHTED SOFTWARE
PLEASE READ THE LICENSE AGREEMENT
http://www.vldpersonals.com/agreement/
=====================================================
*/


//------------------------------------------------
// Includes
//------------------------------------------------
include SYS_PATH . 'includes/languages/' . SYS_LANG . '/lang.cp.pictures.php';
include SYS_PATH . 'includes/languages/' . SYS_LANG . '/lang.cp.members.php';

$p = isset($_GET['p']) ? $_GET['p'] : '';

switch ($p) {

  case 'delete':
    delete_oustanding();
    break;
  default:
    view_oustanding();
    break;
}

function delete_oustanding() {
	global $DB, $TEMPLATE, $LANG, $SESSION;

	if (isset($_GET['id'])) {
		$DB->query('UPDATE escrow SET status = "E" WHERE id = ' . intval($_GET['id']));
    $TEMPLATE->set_message("info", "Escrow has been succesfully deleted.", 0, 0);
	}	else {
    $TEMPLATE->set_message("error", "Wrong parameters.", 0, 0);
	}
  redirect(VIR_CP_PATH . "index.php?m=escrow&p=view_oustanding");
}



//------------------------------------------------
// Picture's properties switch
//------------------------------------------------
function view_oustanding()
{
	global $DB, $TEMPLATE, $LANG, $SESSION;

	if(isset($_REQUEST['doneWith'])) {
		$DB->query($q = "UPDATE `escrow` SET `status`='S' WHERE `id` = '{$_REQUEST['doneWith']}'");
	}

	//------------------------------------------------
	// Set template file
	//------------------------------------------------
	$TEMPLATE->set_template("escrow.tpl");

	$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // page

  $sql = 'SELECT COUNT(*) total_rows FROM escrow WHERE status IN ("P", "S")';
  $result = $DB->query($sql);

  $obj = $DB->fetch_object($result);
  $total_rows = $obj->total_rows;


  //------------------------------------------------
  // Get total pages
  //------------------------------------------------
  $total_pages = ceil($total_rows / 25);


  //------------------------------------------------
  // Get current page
  //------------------------------------------------
  $pages = ($LANG['members']['pages']);
  $pages = str_replace("%1%", $page, $pages);
  $pages = str_replace("%2%", $total_pages, $pages);

  //------------------------------------------------
  // Assign template vars
  //------------------------------------------------
  $TEMPLATE->assign("page",		$page);
  $TEMPLATE->assign("prevpage",	$page > 1 ? $page - 1 : 0);
  $TEMPLATE->assign("nextpage",	$page < $total_pages ? $page + 1 : 0);
  $TEMPLATE->assign("pages",	   $pages);

  $q = '
SELECT escrow. * , DATE_FORMAT(escrow.release_date, "%m/%d/%Y") release_date, paypal_transaction. *
FROM escrow
LEFT JOIN `escrow_transaction` ON ( `escrow_transaction`.`escrow_ID` = `escrow`.`id` )
LEFT JOIN `paypal_transaction` ON ( `escrow_transaction`.`paypal_transaction_ID` = `paypal_transaction`.`paypal_transaction_ID` )
WHERE `escrow`.`status` IN ("P", "S")
LIMIT ' . (($page - 1) * 25) . ', 25';

	$payments = array();

	$result = $DB->query($q);

	if ($DB->num_rows($result)) {
		while($escrow = $DB->fetch_array($result, MYSQL_ASSOC)) {
			$escrow['offer'] = $DB->fetch_array($DB->query($q = "SELECT * FROM `vld_offer` WHERE `id` = '{$escrow['offer_ID']}'"));
			$escrow['buyer'] = $DB->fetch_array($DB->query("SELECT * FROM `vld_members` WHERE `member_id` = '{$escrow['offer']['creatorid']}'"));
			$escrow['provider'] = $DB->fetch_array($DB->query("SELECT * FROM `vld_members` WHERE `member_id` = '{$escrow['offer']['offerto']}'"));


			$escrow['fees'] = number_format((($escrow['amount'] + .30) / (1 - .029)) - $escrow['amount'], 2);
			$escrow['total'] = number_format((($escrow['amount'] + .30) / (1 - .029)), 2);

			$escrow['nice_amount'] = number_format($escrow['amount'], 2);
			$payments[] = $escrow;


		}


	}


	$TEMPLATE->assign("escrows", $payments);

	return 1;
}
// End Function