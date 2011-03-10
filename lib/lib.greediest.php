<?php

$rows_on_page = 25;

//------------------------------------------------
// GET values
//------------------------------------------------
$page = isset($_GET['page']) ? trim($_GET['page']) : 1;  // page

//------------------------------------------------
// Set template file
//------------------------------------------------
$TEMPLATE->set_template('greediest.tpl');
$TEMPLATE->assign('app_page', 'Greediest People on Earth');

//------------------------------------------------
// Get all data for table
//------------------------------------------------
$query = '
SELECT m.member_id member_id,
       m.username username,
       (SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.creatorid = m.member_id) most_offers_made,
       (SELECT SUM(vo.weight) FROM ' . DB_PREFIX . 'offer o, ' . DB_PREFIX . 'voting v, ' . DB_PREFIX . 'votes vo WHERE o.creatorid = m.member_id AND v.offer_id = o.id AND v.vote_id = vo.vote_id) most_outrageous,
       (SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.offerto = m.member_id) most_offers_received,
       (SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.offerto = m.member_id AND o.status = 1) most_offers_accepted,
       (SELECT SUM(e.amount) FROM ' . DB_PREFIX . 'offer o, escrow e WHERE o.offerto = m.member_id AND o.id = e.offer_ID) most_money_earned,
       m.commission_amount most_commissions,

       ((SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.creatorid = m.member_id) +
       IFNULL((SELECT SUM(vo.weight) FROM ' . DB_PREFIX . 'offer o, ' . DB_PREFIX . 'voting v, ' . DB_PREFIX . 'votes vo WHERE o.creatorid = m.member_id AND v.offer_id = o.id AND v.vote_id = vo.vote_id), 0) +
       (SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.offerto = m.member_id) +
       (SELECT COUNT(*) FROM ' . DB_PREFIX . 'offer o WHERE o.offerto = m.member_id AND o.status = 1) +
       IFNULL((SELECT SUM(e.amount) FROM ' . DB_PREFIX . 'offer o, escrow e WHERE o.offerto = m.member_id AND o.id = e.offer_ID), 0) +
       m.commission_amount) ranking_score
FROM ' . DB_PREFIX . 'members m
WHERE m.username != "admin"
ORDER BY ranking_score DESC';
//HAVING ranking_score != 0

$result = $DB->query('SELECT COUNT(*) total FROM ' . DB_PREFIX . 'members WHERE username != "admin"');
$row = $DB->fetch_array($result, MYSQL_ASSOC);
$total_members = $row['total'];

//------------------------------------------------
// Get total pages
//------------------------------------------------
$total_pages = ceil($total_members / $rows_on_page);

//------------------------------------------------
// Get current page
//------------------------------------------------
$pages = "Page %1% out of %2%";
$pages = str_replace("%1%", $page, $pages);
$pages = str_replace("%2%", $total_pages, $pages);

//------------------------------------------------
// Set previous page
//------------------------------------------------
$previous_page = $page > 1 ? $page - 1 : 0;
$previous_page_link = 'index.php?m=greediest&page=' . $previous_page;

//------------------------------------------------
// Set next page
//------------------------------------------------
$next_page = $page < $total_pages ? $page + 1 : 0;
$next_page_link = 'index.php?m=greediest&page=' . $next_page;

$i = ($page - 1) * $rows_on_page;

$result = $DB->query($query . ' LIMIT ' . $i . ', ' . $rows_on_page);

$result_array = array();
while ($row = $DB->fetch_array($result, MYSQL_ASSOC)) {
  if (empty($row['most_outrageous'])) {
    $row['most_outrageous'] = 0;
  }
  if (empty($row['most_money_earned'])) {
    $row['most_money_earned'] = 0;
  }
  $row['most_money_earned'] = number_format($row['most_money_earned'], 2);
  $row['ranking'] = ++$i;
  $result_array[] = $row;
}
$result_array = array_merge($result_array, array());

//------------------------------------------------
// Assign template vars
//------------------------------------------------
$TEMPLATE->assign('members', $result_array);
$TEMPLATE->assign("page_number", $page);
$TEMPLATE->assign("previous_page", $previous_page);
$TEMPLATE->assign("next_page", $next_page);
$TEMPLATE->assign("previous_page_link", $previous_page_link);
$TEMPLATE->assign("next_page_link", $next_page_link);
$TEMPLATE->assign("total_pages", $total_pages);
$TEMPLATE->assign("pages_info", $pages);

?>