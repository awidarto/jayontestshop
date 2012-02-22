
<link rel="stylesheet" type="text/css" href="<?php print base_url();?>assets/css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php print base_url();?>assets/css/jquery-ui/flick/jquery-ui-1.8.16.custom.css" media="screen" />

<script type="text/javascript" src="<?php print base_url();?>assets/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php print base_url();?>assets/js/jquery-ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php print base_url();?>assets/js/jquery-ui/jquery-ui-timepicker-addon.js"></script>

<script>

	var json_string = <?php print $dateblock;?>;
	var dateBlock = eval("(" + json_string + ")");

	$(document).ready(function() {
		$( '#buyerdeliverytime' ).datetimepicker({
			dateFormat:'yy-mm-dd',
			showSecond: false,
			timeFormat: 'hh:mm:ss',
			stepHour: 2,
			stepMinute: 30,
			onSelect:function(dateText, inst){
				//console.log(dateBlock);
				if(dateBlock[dateText] == 'weekend'){
					alert('no delivery on weekend');
				}else{
					$('#assign_deliverytime').val(dateText);
				}
			},
			beforeShowDay:getBlocking

		});
		
		$( '#buyerdeliveryzone' ).autocomplete({
			source: '<?php print site_url('buy/getzone')?>',
			method: 'post',
			minLength: 2
		});

	} );
	
	function toggleForm(){
		$('#orderform').toggle();
	}

	function setcity(){
		var city = $('#buyerdeliveryzone').val();

		var c = city.split(',');

		$('#buyerdeliverycitytxt').html(c[1]);
		$('#buyerdeliverycity').val(c[1]);
	}
	
	function ajaxpost(status){

		var data = {
			trx_id:$('#trx_id').val(),
			buyerdeliveryzone:$('#buyerdeliveryzone option:selected').val(),
			buyerdeliverytime:$('#buyerdeliverytime').val(),
			shipping_address:$('#shipping_address').val(),
			directions:$('#directions').val(),
			buyer_name:$('#buyer_name').val(),
			recipient_name:$('#recipient_name').val(),
			zip:$('#zip').val(),
			phone:$('#phone').val(),
			email:$('#email').val(),
			status:status
		};
		
		$.ajax({
		  type: 'POST',
		  url: '<?php print site_url('gantibaju/ajaxpost/'.$api_key);?>',
		  data: data,
		  success: function(data){
				alert(data.status);
			},
		  dataType: 'json'
		});
	}

	function getBlocking(d){
		/*
			$.datepicker.formatDate('yy-mm-dd', d);
		*/
		var curr_date = d.getDate();
		var curr_month = d.getMonth() + 1; //months are zero based
		var curr_year = d.getFullYear();
	
		curr_date = (curr_date < 10)?"0" + curr_date : curr_date;
		curr_month = (curr_month < 10)?"0" + curr_month : curr_month;
		var indate = curr_year + '-' + curr_month + '-' + curr_date;

		var select = 1;
		var css = 'open';
		var popup = 'working day';
		
		//console.log(indate);
		console.log(window.dateBlock);

		//console.log(window.dateBlock[indate]);

		if(window.dateBlock[indate] == 'weekend'){
			select = 0;
			css = 'weekend';
			popup = 'weekend';
		}else if(window.dateBlock[indate] == 'holiday'){
			select = 0;
			css = 'weekend';
			popup = 'holiday';
		}else if(window.dateBlock[indate] == 'blocked'){
			select = 0;
			css = 'blocked';
			popup = 'zero time slot';
		}else if(window.dateBlock[indate] == 'full'){
			select = 0;
			css = 'blocked';
			popup = 'zero time slot';
		}else{
			select = 1;
			css = '';
			popup = 'working day';
		}
		return [select,css,popup];
	}

	
</script>

<p>
	<span style="cursor:pointer;padding:8px;border:thin solid maroon;margin-top:35px;display:block;width:85px;" onClick="javascript:toggleForm();">COD By Jayon</span>
</p>

<div id="orderform" style="display:none;">
<?php print form_open();?>

<h2>Delivery Order</h2>

<p>Please specify your intended delivery zone and delivery time, and maybe modify your shipping address as well.<br />
Order will be processed within 3(three) working days.
</p>

<table border="0" cellpadding="4" cellspacing="0" id="mainInfo">
<tbody>
<tr>
	<td>Delivery Zone:</td>
	<td>
		<input type="hidden" name="trx_id" id="trx_id" value="<?php echo $trx_id; ?>" size="50" class="form" /><br /><br />
		<?php echo form_dropdown('buyerdeliveryzone',$this->config->item('zonecity'),null,'id="buyerdeliveryzone" onChange="javascript:setcity();"')?><br /><br />
	</td>
</tr>
<tr>
	<td>City:</td>
	<td>
		<input type="hidden" name="buyerdeliverycity" id="buyerdeliverycity" value="" size="50" >
		<span id="buyerdeliverycitytxt"></span>
	</td>
</tr>
<tr>
	<td>Delivery Date & Time:</td>
	<td>
		<input type="text" name="buyerdeliverytime" id="buyerdeliverytime" value="" size="50" class="form" /><br /><br />
	</td>
</tr>
<tr>
	<td>Delivery Address:</td>
	<td>
		<textarea name="shipping_address" id="shipping_address" cols="60" rows="10"></textarea>	
	</td>
</tr>
<tr>
	<td>ZIP / Postal Code:</td>
	<td>
		<input type="text" name="zip" id="zip" value="" size="50" class="form" /><br /><br />
	</td>
</tr>
<tr>
	<td>Directions<br />( Please tell us how to get there ):</td>
	<td>
		<textarea name="directions" id="directions" cols="60" rows="10"></textarea>	
	</td>
</tr>
<tr>
	<td>Buyer Name:</td>
	<td>
		<input type="text" name="buyer_name" id="buyer_name" value="" size="50" class="form" /><br /><br />
	</td>
</tr>
<tr>
	<td>Email:</td>
	<td>
		<input type="text" name="email" id="email" value="" size="50" class="form" /><br /><br />
	</td>
</tr>
<tr>
	<td>Recipient Name:</td>
	<td>
		<input type="text" name="recipient_name" id="recipient_name" value="" size="50" class="form" /><br /><br />
	</td>
</tr>

<tr>
	<td>Contact Number:</td>
	<td>
		<input type="text" name="phone" id="phone" value="" size="50" class="form" /><br /><br />
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="button" value="Confirm Order" name="confirm" onClick="javascript:ajaxpost('pending');" />
		<input type="button" value="Cancel Order" name="cancel" onClick="javascript:ajaxpost('cancel');" />
	</td>
</tr>
</tbody>
</table>


<?php print form_close()?>


</div>
<br /><br />

<div id="deliverytable">
</div>


<script>
</script>
