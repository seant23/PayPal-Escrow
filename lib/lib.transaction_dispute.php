<?php

//------------------------------------------------
// Check if user has access to this page
//------------------------------------------------
if (!$SESSION->auth)
{
  $TEMPLATE->set_message("error", ($LANG['core']['not_loggedin']), 0, 0);
  redirect(VIR_PATH . ($PREFS->conf['fancy_urls'] ? "account/login/" : "index.php?m=account_login"), 1);
}

$TEMPLATE->set_template('transaction_dispute.tpl');

include SYS_PATH . 'includes/core/core.email.php';
include SYS_PATH . 'includes/fns/fns.validate.php';

//--------------------------------------------------
// Get Offers Id if selected
//--------------------------------------------------
$offer_id = isset($_POST['offer_id']) ? $_POST['offer_id'] : '';
if (isset($_POST['issubmit'])) {
  // The from submited
  $error = false;
  $result = $DB->query('
SELECT o.id id,
    o.title title,
    c.title category,
    o.mustdo must_do,
    o.proofrequired proof_required,
    o.amount offer_amount,
    o.status status,
    mf.username gp_username,
    mt.username accepted_by,
    e.id escrow_id,
    e.amount escrow_amount,
    e.bonus fees_paid
FROM ' . DB_PREFIX . 'offer o
    LEFT JOIN ' . DB_PREFIX . 'categories c ON o.category_id = c.category_id
    LEFT JOIN ' . DB_PREFIX . 'members mf ON o.creatorid = mf.member_id
    LEFT JOIN ' . DB_PREFIX . 'members mt ON o.offerto = mt.member_id
    LEFT JOIN escrow e ON o.id = e.offer_ID
    LEFT JOIN escrow_transaction et ON e.id = et.escrow_ID
WHERE (o.offerto = "' . $SESSION->conf['member_id'] . '" OR
    o.creatorid = "' . $SESSION->conf['member_id'] . '") AND
    o.provider_archive = 0 AND
    o.id = "' . mysql_real_escape_string($offer_id) . '" LIMIT 1');

	$valid_email =  validate_email($_POST['email']);
  if (!($row = $DB->fetch_array($result, MYSQL_ASSOC))) {
    $TEMPLATE->set_message('error', 'Please choose one of offer in or offer out.', 0, 0);
    $error = true;
  }
  elseif (!$row['escrow_id']) {
    $TEMPLATE->set_message('error', 'Form cannot be submitted.', 0, 0);
    $error = true;
  }
  elseif (!isset($_POST['how_negotiated']) || trim($_POST['how_negotiated']) == '') {
    $TEMPLATE->set_message('error', 'Please choose how negotiated.', 0, 0);
    $error = true;
  }
  elseif (!isset($_POST['name']) || trim($_POST['name']) == '') {
    $TEMPLATE->set_message('error', 'Name cannot be empty.', 0, 0);
    $error = true;
  }
  elseif (!isset($_POST['phone']) || trim($_POST['phone']) == '') {
    $TEMPLATE->set_message('error', 'Phone cannot be empty.', 0, 0);
    $error = true;
  }
	elseif ($valid_email == 1) {
		$TEMPLATE->set_message("error", 'Email is not vaild.', 0, 0);
    $error = true;
	}
	elseif ($valid_email == 2) {
		$TEMPLATE->set_message("error", 'Email does not appear to be valid.', 0, 0);
    $error = true;
	}
  elseif (trim($_POST['dispute_comments']) == '') {
    $TEMPLATE->set_message('error', 'Dispute comments cannot be empty.', 0, 0);
    $error = true;
  }

  $row['accepted_by'] = ($row['status'] == 2) ? htmlspecialchars($row['gp_username']) : '-';
  $row['offer_amount'] = '$' . number_format($row['offer_amount'], 2);
  $row['escrow_amount'] = $row['escrow_id'] ? '$' . number_format($row['escrow_amount'], 2) : '-';
  $row['fees_paid'] = $row['escrow_id'] ? ($row['fees_paid'] ? 'Yes' : 'No') : '-';
  $row['how_negotiated'] = trim($_POST['how_negotiated']);
  $row['name'] = trim($_POST['name']);
  $row['phone'] = trim($_POST['phone']);
  $row['email'] = trim($_POST['email']);
  $row['dispute_comments'] = trim($_POST['dispute_comments']);

  if (!$error) {
    send_email_template('arbitration@greedypeople.com', 'transaction_dispute', $row);
    $TEMPLATE->set_message("info", "An Email has been sent to the arbitration administrator.", 0, 0);
    redirect(VIR_PATH . 'index.php?m=transaction_dispute');
  }
}

$TEMPLATE->assign('offer_id', $offer_id);

//--------------------------------------------------
// Get Offers In
//--------------------------------------------------
$offers_in = array();
$result = $DB->query('
SELECT o.id id,
    o.title title,
    c.title category,
    o.mustdo must_do,
    o.proofrequired proof_required,
    o.amount offer_amount,
    o.status status,
    mf.username gp_username,
    mt.username accepted_by,
    e.id escrow_id,
    e.amount escrow_amount,
    e.bonus fees_paid
FROM ' . DB_PREFIX . 'offer o
    LEFT JOIN ' . DB_PREFIX . 'categories c ON o.category_id = c.category_id
    LEFT JOIN ' . DB_PREFIX . 'members mf ON o.creatorid = mf.member_id
    LEFT JOIN ' . DB_PREFIX . 'members mt ON o.offerto = mt.member_id
    LEFT JOIN escrow e ON o.id = e.offer_ID
    LEFT JOIN escrow_transaction et ON e.id = et.escrow_ID
WHERE o.offerto = "' . $SESSION->conf['member_id'] . '" AND
    o.provider_archive = 0
');

while ($row = $DB->fetch_array($result, MYSQL_ASSOC)) {
  $row['title'] = htmlspecialchars($row['title']);
  $row['tgp_usernameitle'] = htmlspecialchars($row['gp_username']);
  $row['accepted_by'] = ($row['status'] == 2) ? htmlspecialchars($row['gp_username']) : '-';
  $row['category'] = htmlspecialchars($row['category']);
  $row['must_do'] = htmlspecialchars(str_replace(array("\r\n", "\r", "\n"), '\r\n', $row['must_do']));
  $row['proof_required'] = htmlspecialchars($row['proof_required']);
  $row['offer_amount'] = '$' . number_format($row['offer_amount'], 2);
  $row['escrow_amount'] = $row['escrow_id'] ? '$' . number_format($row['escrow_amount'], 2) : '-';
  $row['fees_paid'] = $row['escrow_id'] ? ($row['fees_paid'] ? 'Yes' : 'No') : '-';
  $offers_in[] = $row;
}
$TEMPLATE->assign('offers_in', $offers_in);

//--------------------------------------------------
// Get Offers Out
//--------------------------------------------------
$offers_out = array();
$result = $DB->query('
SELECT o.id id,
    o.title title,
    c.title category,
    o.mustdo must_do,
    o.proofrequired proof_required,
    o.amount offer_amount,
    o.status status,
    mf.username gp_username,
    mt.username accepted_by,
    e.id escrow_id,
    e.amount escrow_amount,
    e.bonus fees_paid
FROM ' . DB_PREFIX . 'offer o
    LEFT JOIN ' . DB_PREFIX . 'categories c ON o.category_id = c.category_id
    LEFT JOIN ' . DB_PREFIX . 'members mf ON o.creatorid = mf.member_id
    LEFT JOIN ' . DB_PREFIX . 'members mt ON o.offerto = mt.member_id
    LEFT JOIN escrow e ON o.id = e.offer_ID
    LEFT JOIN escrow_transaction et ON e.id = et.escrow_ID
WHERE o.creatorid = "' . $SESSION->conf['member_id'] . '" AND
    o.provider_archive = 0
');

while ($row = $DB->fetch_array($result, MYSQL_ASSOC)) {
  $row['title'] = htmlspecialchars($row['title']);
  $row['tgp_usernameitle'] = htmlspecialchars($row['gp_username']);
  $row['accepted_by'] = ($row['status'] == 2) ? htmlspecialchars($row['gp_username']) : '-';
  $row['category'] = htmlspecialchars($row['category']);
  $row['must_do'] = htmlspecialchars(str_replace(array("\r\n", "\r", "\n"), '\r\n', $row['must_do']));
  $row['proof_required'] = htmlspecialchars($row['proof_required']);
  $row['offer_amount'] = '$' . number_format($row['offer_amount'], 2);
  $row['escrow_amount'] = $row['escrow_id'] ? '$' . number_format($row['escrow_amount'], 2) : '-';
  $row['fees_paid'] = $row['escrow_id'] ? ($row['fees_paid'] ? 'Yes' : 'No') : '-';
  $offers_out[] = $row;
}
$TEMPLATE->assign('offers_out', $offers_out);

//--------------------------------------------------
// Generate Javascript Array
//--------------------------------------------------
$offers = array_merge($offers_in, $offers_out);
$TEMPLATE->assign('offers', $offers);

//--------------------------------------------------
// Assign other variables
//--------------------------------------------------
$TEMPLATE->assign('how_negotiated', (isset($_POST['how_negotiated']) ? htmlspecialchars($_POST['how_negotiated']) : ''));
$TEMPLATE->assign('name', (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''));
$TEMPLATE->assign('phone', (isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''));
$TEMPLATE->assign('email', (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''));
$TEMPLATE->assign('dispute_comments', (isset($_POST['dispute_comments']) ? htmlspecialchars($_POST['dispute_comments']) : ''));

?>