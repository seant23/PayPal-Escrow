<!-- INCLUDE header.tpl -->
<div id="filters" style="display: none">

</div>
<!-- IF app_message_type == "info" -->
	<!-- INCLUDE info.tpl -->
<!-- ELSEIF app_message_type == "error" -->
	<!-- INCLUDE error.tpl -->
<!-- ENDIF -->
<!-- IF hide_content != "1" -->
	<table cellpadding="4" cellspacing="1" width="100%" class="gridTable">
		<tr>
			<td class="gridHeader">Type</td>
			<td class="gridHeader">Dep Date</td>
			<td class="gridHeader">Trans ID</td>
			<td class="gridHeader">From</td>
			<td class="gridHeader">To</td>
			<td class="gridHeader" width="250" >Offer Title</td>
			<td class="gridHeader">Offer Amt</td>
			<td class="gridHeader">PP Fee</td>
			<td class="gridHeader">Total</td>
			<td class="gridHeader">Release<BR>Approved</td>
			<td class="gridHeader">Seller PP</td>
			<td class="gridHeader" colspan="2">Pay To Seller</td>
		</tr>
		<!-- BEGIN escrows -->
		<tr class="gridTr{rownum}">
		<td class="gridOptions" style="text-align: left; padding: 4px">
				Escrow
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{payment_date}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{id}
			</td>
		<td class="gridOptions" style="text-align: left; padding: 4px">
				{buyer.username}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{provider.username}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{offer.title}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				${amount}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				${fees}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				${total}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{release_date}
			</td>
			<td class="gridOptions" style="text-align: left; padding: 4px">
				{provider.paypal_email}
			</td>

			<!-- IF status = 'P' -->
			<td class="gridOptions" style="text-align: left; padding: 4px">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="{provider.paypal_email}">
					<input type="hidden" name="item_name" value="Escrow Payment From {buyer.username} For Project {offer.title}">
					<input type="hidden" name="amount" value="{amount}">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="return" value="http://www.greedypeople.com/cp/index.php?m=escrow&amp;p=view_oustanding&amp;doneWith={id}">
					<input type="hidden" name="cancel_return" value="http://www.greedypeople.com/cp/index.php?m=escrow&amp;p=view_oustanding">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="lc" value="US">
					<input type="hidden" name="bn" value="PP-BuyNowBF">

					<input type="hidden" name="notify_url" value="http://www.greedypeople.com/ipn.php">
					<input type="hidden" name="custom" value="doneWith={id}">
					<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</td>
			<!-- ELSE -->
			<td class="gridOptions" style="text-align: left; padding: 4px">
				Paid
			</td>
			<!-- ENDIF -->
			<td>
				<a class="grid" href="#" onclick="javascript:confirmLink('Are you sure you want to delete this escrow?', '{top.virtual_cp_path}index.php?m=escrow&p=delete&id={id}')" title="Delete Escrow">
				<img src="{top.virtual_path}includes/templates/media/{top.cp_theme}/actions/delete.gif" alt="Delete Escrow" border="0" /></a>
			</td>
		</tr>
		<!-- END escrows -->
		<tr>
			<td class="gridFooter" colspan="13">
				{pages}
				&nbsp;
				<!-- IF prevpage != "0" -->
					<a href="{virtual_cp_path}index.php?m=escrow&p=view_oustanding&page={prevpage}">&laquo; {lang:"members","previous_page"}</a>
				<!-- ELSE -->
					<span class="gridFaded">&laquo; {lang:"members","previous_page"}</span>
				<!-- ENDIF -->
				<span class="gridFaded">|</span>
				<!-- IF nextpage != "0" -->
					<a href="{virtual_cp_path}index.php?m=escrow&p=view_oustanding&page={nextpage}">{lang:"members","next_page"} &raquo;</a>
				<!-- ELSE -->
					<span class="gridFaded">{lang:"members","next_page"} &raquo;</span>
				<!-- ENDIF -->
			</td>
		</tr>
	</table>
<!-- ENDIF -->
<!-- INCLUDE footer.tpl -->