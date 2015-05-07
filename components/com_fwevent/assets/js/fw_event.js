(function($)
{
	$(document).ready(function()
	{
		var no_passengers = $("#no_passengers");
		var passengers = $("#passengers");
		
		if($("#input_passenger").attr('checked'))
		{
			no_passengers.hide();
			passengers.show();
		}
		
		$("#input_no_passenger").change(function(){
			if($(this).attr('checked'))
			{
				no_passengers.show();
				passengers.hide();
			}
		})
		
		$("#input_passenger").change(function(){
			if($(this).attr('checked'))
			{
				no_passengers.hide();
				passengers.show();
			}
		})
	});

})(jQuery);


var numPassenger = 0;
function addMorePassenger()
{
	var input_name = jQuery("input.passenger_"+numPassenger);
	var name = input_name.val();
	if(jQuery.trim(name) == '')
	{
		alert("Please, input your name");
		return;
	}
	input_name.attr('readonly',true);
	
	numPassenger++;
	
	var hmtl='';
	hmtl+="<table id='passenger_"+numPassenger+"'>";
	hmtl+="	<tr class='name'>";
	hmtl+="		<td class='label'>Name:</td>";
	hmtl+="		<td><input type='text' name='passenger[]' value='' class='name passenger_"+numPassenger+"' /></td>";
	hmtl+="	</tr>";

	hmtl+="	<tr class='price'>";
	hmtl+=	jQuery("#passenger_0 tr.price").html();	
	hmtl+="	</tr>";
	hmtl+="</table>";
	
	jQuery("#list_passenger").append(hmtl);
}

function onSubmitPassenger()
{
	var input_name = jQuery("#passenger_"+numPassenger+" input.name");
	var name = input_name.val();
	if(jQuery.trim(name) == '')
	{
		alert("Please, input your name");
		return;
	}
	document.frm_passenger.submit();
}

function onValidateQuatity(evt)
{
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	}
}

function onChangeQuatity(id,num)
{
	var quantityId = "#quantity_"+id;
	var priceId = "#price_"+id;
	var amountId = "#amount_"+id;
	var quatity = Number(jQuery(quantityId).val());
	var price = Number(jQuery(priceId).html());
	
	if(num != 0)
	{	
		if(num==-1 && quatity==0)
			return;
		
		quatity+=num;
		jQuery(quantityId).val(quatity);
	}
	
	var amount = quatity*price;
	if(isNaN(amount) || amount==0)
	{	
		jQuery(amountId).html(0);
		jQuery(quantityId).val(0)
	}
	else
		jQuery(amountId).html(amount);
	
	updateTotal();
}
function onUpQuantity(id)
{
	onChangeQuatity(id,1);
}
function onDownQuantity(id)
{
	onChangeQuatity(id,-1);
}
function updateTotal()
{
	var total = 0;
	jQuery("span.amount").each(function(index) {
	   total+=Number($(this).text());
	});
	jQuery("#total_addons").html(total);
}