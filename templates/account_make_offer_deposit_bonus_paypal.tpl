<!-- INCLUDE header.tpl -->
<div class="title_wrap">
    <div class="title">
        <h1>Total Deposit to be Escrowed and Fees</h1>
    </div>

    <div class="clear"></div>
</div>
<!-- INCLUDE message.tpl -->


<STYLE TYPE="text/css">
            <!--
P {
font-size : 12px;
font-family : Lucida Grande, Geneva, Arial, Verdana, sans-serif;
color : #333333;
background:    transparent;
line-height: 14px;
margin : 0;
}

.instructions {
font-size : 10px;
font-family : Lucida Grande, Geneva, Arial, Verdana, sans-serif;
color : #DB6201;
background:    transparent;
line-height: 11px;
margin : 0;
}

.header {
font-size : 14px;
font-family : Lucida Grande, Geneva, Arial, Verdana, sans-serif;
color : #999999;
background:    transparent;
line-height: 14px;
margin : 0;
}

.whitepage-copy {
font-size : 12px;
font-family : Lucida Grande, Geneva, Arial, Verdana, sans-serif;
color : #444444;
background:    transparent;
line-height: 14px;
margin : 0;
}

.caption {
font-size : 10px;
font-family : Lucida Grande, Geneva, Arial, Verdana, sans-serif;
color : #CC0000;
background: transparent;
line-height: 10px;
margin : 0;
}
-->
</STYLE>


		<div id="content">
			<div class="outter page_default">
				<div class="datagrid">
				<div class="form">
					<div class="single">
						<table cellpadding="0" cellspacing="0" class="datagrid">
							<tr>
								<td class="data">
									<div class="entry">
										<div class="paragraph">
										Use this form to deposit money into PayPal Escrow for your projects.
<div class="clear2"></div>

<table border="0" width="750" cellspacing="1" cellpadding="4" align="center" BGCOLOR="#DDDDDD">
	<tr>
		<td WIDTH="200" BGCOLOR="#FCFCFC" align="right" valign="middle">
				<P><b>Total Amount:</b></P>
		</td>
		<td WIDTH="550" BGCOLOR="#FFFFFF" align="left" colspan="2">${bonus_amount} <font size="1" color="#666666" face="Arial"></font></td>
	</tr>
    <tr>
        <td BGCOLOR="#FFFFFF" valign="top" align="right" colspan="3">
        <dd class="submit">
       		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="{provider.paypal_email}">
				<input type="hidden" name="item_name" value="Bonus Payment To {provider.username}">
				<input type="hidden" name="amount" value="{bonus_amount}">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="return" value="http://www.greedypeople.com/index.php?m=account_make_offer&amp;p=deposit_escrow&amp;id=4&amp;success=true&amp;bonus=true">
				<input type="hidden" name="cancel_return" value="http://www.greedypeople.com/index.php?m=account_make_offer&amp;p=deposit_escrow&amp;id=4&amp;success=false">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="lc" value="US">
				
				<input type="hidden" name="custom" value="{escrow_custom}">
				<input type="hidden" name="notify_url" value="http://www.greedypeople.com/ipn.php">				
				
				<input type="hidden" name="bn" value="PP-BuyNowBF">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>        	
        </dd>
    </tr>
</table>
<BR>


										</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>

<div class="clear"></div>

<script type="text/javascript">
function reveal(a){
var e=document.getElementById(a);
if(!e)return true;
if(e.style.display=="none"){
e.style.display="block"
} else {
e.style.display="none"
}
return true;
}
</script>

    <!-- INCLUDE footer.tpl -->