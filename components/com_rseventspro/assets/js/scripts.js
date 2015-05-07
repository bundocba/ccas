var rsepro_timer;function isset () {    // http://kevin.vanzonneveld.net    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // +   improved by: FremyCompany    // +   improved by: Onno Marsman    // +   improved by: Rafał Kukawski    // *     example 1: isset( undefined, true);    // *     returns 1: false    // *     example 2: isset( 'Kevin van Zonneveld' );    // *     returns 2: true    var a = arguments,        l = a.length,        i = 0,        undef;    if (l === 0) {        throw new Error('Empty isset');    }    while (i !== l) {        if (a[i] === undef || a[i] === null) {            return false;        }        i++;    }    return true;}function rse_calculatetotal() {	if ($('from').value == 0) {		var numberOfTickets = $('numberinp').value;	} else {		var numberOfTickets = $('number').value;	}	var ticketId = isset($('RSEProTickets')) ? $('RSEProTickets').value : $('ticket').value;	var params = 'task=total&idevent=' + parseInt($('eventID').innerHTML);		// Multiple tickets	if (isset($('hiddentickets'))) {		var ticketsstring = '';		$$('#hiddentickets input').each(function (el) {			ticketsstring += '&'+el.name+'='+el.value;		});				params += ticketsstring;	} else {		params += '&tickets['+ticketId+']='+numberOfTickets;	}		if (isset($('payment'))) params += '&payment=' + $('payment').value;	if (isset($('coupon'))) params += '&coupon=' + $('coupon').value;	if (isset($('RSEProPayment'))) params += '&payment=' + $('RSEProPayment').value;	if (isset($('RSEProCoupon'))) params += '&coupon=' + $('RSEProCoupon').value;		params += '&randomTime=' + Math.random();		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: params,		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						if (response != 0) {				$('grandtotalcontainer').style.display = '';				$('grandtotal').innerHTML = response;			} else {				$('grandtotalcontainer').style.display = 'none';				$('grandtotal').innerHTML = 0;			}		}	});	req.send();}function rs_stop() {	clearTimeout(rsepro_timer);}function rsepro_description_on(id) {	document.getElementById('rsehref'+id).style.display = 'none';	document.getElementById('rsedescription'+id).className = 'rsepro_extra_on';}function rsepro_description_off(id) {	document.getElementById('rsehref'+id).style.display = 'inline';	document.getElementById('rsedescription'+id).className = 'rsepro_extra_off';}function rs_add_option(theoption) {	$('rseprosearch').value = theoption;	$('rs_results').style.display = 'none';}function rs_add_filter() {	if ($('rseprosearch').value != '')		document.adminForm.submit();}function rs_clear_filters() {	$('rs_clear').value = 1;	document.adminForm.submit();}function rs_remove_filter(key) {	$('rs_remove').value = key;	document.adminForm.submit();}function rse_verify_coupon(ide, coupon) {	if (coupon == '')		return false;		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: 'task=verify&id=' + ide + '&coupon=' + coupon,		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);			alert(response);		}	});	req.send();}/** *	Search events */function rs_search() {	if ($('filter_from').value == 'description') return;	if ($('rseprosearch').value == '') return;		rsepro_timer = setTimeout( function() {		var req = new Request({			method: 'post',			url: 'index.php?option=com_rseventspro',			data: 'task=filter&type=' + $('filter_from').value + '&search=' + $('rseprosearch').value + '&condition=' + $('filter_condition').value,			onSuccess: function(responseText, responseXML) {				var response = responseText;				var start = response.indexOf('RS_DELIMITER0') + 13;				var end = response.indexOf('RS_DELIMITER1');				response = response.substring(start, end);								if (response != '') {					$('rs_results').innerHTML = response;					$('rs_results').style.display = 'block';				} else $('rs_results').style.display = 'none';			}		});		req.send();	}, 1000);}/** *	Events pagination */function rspagination(tpl,limitstart,ide) {	$('rs_loader').style.display = '';		if (tpl == 'day' || tpl == 'week')		var params = '&view=calendar&layout=items&tpl='+tpl+'&format=raw&limitstart='+ limitstart;	else		var params = 'view=rseventspro&layout=items&tpl='+tpl+'&format=raw&limitstart='+ limitstart;		if (isset($('parent')) && parseInt($('parent').innerHTML) > 0) params += '&parent=' + parseInt($('parent').innerHTML);	if (isset($('date')) && $('date').innerHTML != '') params += '&date=' + $('date').innerHTML;	if (ide) params += '&id=' + parseInt(ide);	params += '&Itemid=' + parseInt($('Itemid').innerHTML);	params += '&randomTime=' + Math.random();		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: params,		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);			var code = response.toDOM();						for (i=0; i< code.length; i++)				$('rs_events_container').appendChild(code[i]);						$('rs_loader').style.display = 'none';						if ($$('#rs_events_container li').length > 0 && (tpl == 'events' || tpl == 'locations' || tpl == 'subscribers' || tpl == 'day' || tpl == 'week')) 			{				$$('#rs_events_container li').addEvents({					mouseenter: function(){ 						if (isset($(this).getElement('div.rs_options')))							$(this).getElement('div.rs_options').style.display = '';					},					mouseleave: function(){      						if (isset($(this).getElement('div.rs_options')))							$(this).getElement('div.rs_options').style.display = 'none';					}				});			}						if ( ($('rs_events_container').getElements('li').length) >= parseInt($('total').innerHTML)) $('rsepro_loadmore').style.display = 'none';		}	});	req.send();}/** *	Rate event */function rsepro_feedback(val,id) {	for (var i=1; i<=5; i++)		document.getElementById('rsepro_feedback_' + i).onclick = function() { return false; }	$('rs_loading_img').style.display = '';		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: 'task=rseventspro.rate&id=' + id + '&feedback=' + val + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						response = response.split('|');			$('rs_loading_img').style.display = 'none';						if (parseInt(response[0]) > 0)				document.getElementById('rs_rating').innerHTML = '<li id="rsepro_current_rating" class="rsepro_feedback_selected_'+ parseInt(response[0]) +'">&nbsp;</li>';			$('rs_rating_info').innerHTML = response[1];						window.setTimeout(function () {				$('rs_rating_info').tween('opacity', '0');			},2000);		}	});	req.send();}/** *	Get ticket information */function rs_get_ticket(val) {	$('rs_loader').style.display = '';		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: 'task=tickets&id=' + val + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						response = response.split('|');			$('rs_loader').style.display = 'none';						if (parseInt(response[0]) == 0) {				$('numberinp').style.display = '';				$('number').style.display = 'none';				$('numberinp').value = 1;				$('from').value = 0;			} else {				$('numberinp').style.display = 'none';				$('number').style.display = '';				$('from').value = 1;				$('number').options.length = 0;				for(i=1; i <= parseInt(response[0]); i++)					$('number').options[i-1] = new Option(i,i);			}						$('tdescription').innerHTML = response[1];			rse_calculatetotal();		}	});	req.send();}/** *	Subscriber validation */function svalidation() {	ret = true;	msg = new Array();		if ($('name').value == '') { ret = false; $('name').className = 'rs_edit_inp_error_small'; msg.push(smessage[0]); } else { $('name').className = 'rs_edit_inp_small'; }	if ($('email').value == '') { ret = false; $('email').className = 'rs_edit_inp_error_small'; msg.push(smessage[1]); } else { $('email').className = 'rs_edit_inp_small'; }	if (isset($('hiddentickets')) && $('hiddentickets').innerHTML == '') { ret = false; msg.push(smessage[3]); }	if (!rse_validateEmail($('email').value)) { ret = false; $('email').className = 'rs_edit_inp_error_small'; msg.push(smessage[4]); } else { $('email').className = 'rs_edit_inp_small'; }	if (ret)		return true;	else {		alert(msg.join("\n"));		return false;	}}/** *	Email validation */	function rse_validateEmail(email) {	var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;	return regex.test(email);}/** *	Add ticket to subscription */function rs_add_ticket() {	var container		= $('tickets');	var hidden_tickets	= $('hiddentickets');	var ticket			= isset($('RSEProTickets')) ? $('RSEProTickets') : $('ticket');	var ticket_number	= parseInt($('from').value) == 0 ? parseInt($('numberinp').value) : parseInt($('number').value);	var ticket_id		= ticket.value;	var ticket_name		= ticket.options[ticket.selectedIndex].text;		if (ticket_number == 0) ticket_number = 1;		if (parseInt($('from').value) == 1) {		var available_per_user = $('number').options.length;				if (isset(document.getElementById('tickets'+ticket_id))) {			if (parseInt(document.getElementById('tickets'+ticket_id).value) + ticket_number > available_per_user) {				alert(smessage[6].replace('%d',available_per_user));				return;			}		}	}		if (isset($('hiddentickets')) && typeof maxtickets != 'undefined') {		var total = 0;		$('hiddentickets').getElements('input').each(function (el){			total += parseInt(el.value);		});				total += ticket_number;				if (total > maxtickets) {			alert(smessage[5]);			return;		}	}		if (!isset(document.getElementById('tickets'+ticket_id))) {			input = document.createElement('input');		input.setAttribute('type', 'hidden');		input.setAttribute('name', 'tickets['+ticket_id+']');		input.setAttribute('id', 'tickets'+ticket_id);		input.setAttribute('value', ticket_number);		hidden_tickets.appendChild(input);				span = document.createElement('span');		span.setAttribute('id', 'content'+ticket_id);		span.innerHTML = '<span id="ticketq'+ ticket_id +'">' + ticket_number + '</span>' + ' x ' + ticket_name + ' <a href="javascript:void(0);" onclick="rs_remove_ticket('+ ticket_id +')"> ('+smessage[2]+')</a><br/>';				container.appendChild(span);	} else {		$('ticketq'+ticket_id).innerHTML = parseInt($('ticketq'+ticket_id).innerHTML) + parseInt(ticket_number);		$('tickets'+ticket_id).value = parseInt($('tickets'+ticket_id).value) + parseInt(ticket_number);	}		rse_calculatetotal();}/** *	Remove ticket from subscription */function rs_remove_ticket(theid) {	if (isset($('tickets'+theid))) {		var container		= $('tickets');		var hidden_tickets	= $('hiddentickets');				container.removeChild($('content'+theid));		hidden_tickets.removeChild($('tickets'+theid));				rse_calculatetotal();	}}/** *	Send message to guests validation */function rs_send_guests() {	ret = true;	if (document.getElementById('subject').value == '') {ret = false; document.getElementById('subject').className = 'rs_edit_inp_error_small'; } else { document.getElementById('subject').className = 'rs_edit_inp_small'; }		var people = document.getElementById('subscribers');		peopletrue = false;	for (i=0;i<people.length;i++)		if (people[i].selected)			peopletrue = true;		if (document.getElementById('denied').checked == false && document.getElementById('pending').checked == false && document.getElementById('accepted').checked == false && !peopletrue) {		ret = false;		document.getElementById('a_option').className = 'rs_error';		document.getElementById('d_option').className = 'rs_error';		document.getElementById('p_option').className = 'rs_error';	} else {		document.getElementById('a_option').className = '';		document.getElementById('d_option').className = '';		document.getElementById('p_option').className = '';	}	return ret;}/** *	Display Yahoo! or Google invite */function rs_load(type) {	if (type == 'gmail') {		document.getElementById('rs_gmail_u').style.display = '';		document.getElementById('rs_gmail_p').style.display = '';		document.getElementById('rs_gmail_b').style.display = '';		document.getElementById('rs_yahoo_u').style.display = 'none';		document.getElementById('rs_yahoo_p').style.display = 'none';		document.getElementById('rs_yahoo_b').style.display = 'none';		document.getElementById('ypassword').value = '';		document.getElementById('yusername').value = '';	} else {		document.getElementById('rs_yahoo_u').style.display = '';		document.getElementById('rs_yahoo_p').style.display = '';		document.getElementById('rs_yahoo_b').style.display = '';		document.getElementById('rs_gmail_u').style.display = 'none';		document.getElementById('rs_gmail_p').style.display = 'none';		document.getElementById('rs_gmail_b').style.display = 'none';		document.getElementById('gpassword').value = '';		document.getElementById('gusername').value = '';	}}/** *	Display Yahoo! or Google invite */function rs_load_close(type) {	if (type == 'gmail') {		document.getElementById('rs_gmail_u').style.display = 'none';		document.getElementById('rs_gmail_p').style.display = 'none';		document.getElementById('rs_gmail_b').style.display = 'none';		document.getElementById('gpassword').value = '';		document.getElementById('gusername').value = '';	} else {		document.getElementById('rs_yahoo_u').style.display = 'none';		document.getElementById('rs_yahoo_p').style.display = 'none';		document.getElementById('rs_yahoo_b').style.display = 'none';		document.getElementById('ypassword').value = '';		document.getElementById('yusername').value = '';	}}/** *	Invite validation */function rs_invite() {	ret = true;		if (document.getElementById('from').value == '') { ret = false; document.getElementById('from').className = 'rs_edit_inp_error_small'; } else { document.getElementById('from').className = 'rs_edit_inp_small'; }	if (document.getElementById('from_name').value == '') { ret = false; document.getElementById('from_name').className = 'rs_edit_inp_error_small'; } else { document.getElementById('from_name').className = 'rs_edit_inp_small'; }	if (document.getElementById('emails').value == '') { ret = false; document.getElementById('emails').className = 'rs_edit_txt_error'; } else { document.getElementById('emails').className = 'rs_edit_txt'; }		if (ret)		document.adminForm.submit();}/** *	Get Yahoo! or Gmail email addresses */function rs_connect(type) {	var iuser = '';	var ipass = '';		if (type == 'gmail') {		iuser = document.getElementById('gusername').value;		ipass = document.getElementById('gpassword').value;	} else if (type == 'yahoo') {		iuser = document.getElementById('yusername').value;		ipass = document.getElementById('ypassword').value;	}		if (iuser == '' || ipass == '') {		if (type == 'gmail') {			if (document.getElementById('gpassword').value == '')				document.getElementById('gpassword').className = 'rs_edit_inp_error_small';			if (document.getElementById('gusername').value == '')				document.getElementById('gusername').className = 'rs_edit_inp_error_small';		} else {			if (document.getElementById('ypassword').value == '')				document.getElementById('ypassword').className = 'rs_edit_inp_error_small';			if (document.getElementById('yusername').value == '')				document.getElementById('yusername').className = 'rs_edit_inp_error_small';		}		alert(invitemessage[0]);		return;	} else {		if (type == 'gmail') {			document.getElementById('gpassword').className = 'rs_edit_inp_small';			document.getElementById('gusername').className = 'rs_edit_inp_small';		} else {			document.getElementById('ypassword').className = 'rs_edit_inp_small';			document.getElementById('yusername').className = 'rs_edit_inp_small';		}	}		if (type == 'gmail')		document.getElementById('img_gmail').style.display = '';	else		document.getElementById('img_yahoo').style.display = '';		var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: 'task=connect&type=' + type + '&username='+ iuser + '&password='+ ipass + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						if (document.getElementById('emails').value == '')				document.getElementById('emails').value = response;			else				document.getElementById('emails').value += "\n" + response;						if (type == 'gmail')				document.getElementById('img_gmail').style.display = 'none';			else				document.getElementById('img_yahoo').style.display = 'none';		}	});	req.send();}/** *	Verify captcha */function checkcaptcha() {	var req = new Request({		method: 'post',		url: 'index.php?option=com_rseventspro',		data: 'task=checkcaptcha&secret=' + document.getElementById('secret').value + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						if (parseInt(response)) {				document.getElementById('secret').className = 'rs_edit_inp_small';				rs_invite();			} else  {				document.getElementById('secret').className = 'rs_edit_inp_error_small';			}		}	});	req.send();}/** *	Reload captcha */	function reloadCaptcha() {	document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + Math.random();}/** *	Add calendar filter */	function rs_calendar_add_filter(name) {	if (name != 0) {		$('filter_from').value = 'categories';		$('filter_condition').value = 'is';		$('rseprosearch').value = name;				}		$$('#rs_filters li input[value=categories]').each(function(el) {		el.getParent().dispose();	});		document.adminForm.submit();}/** *	Credit card validation */	function cc_validate(card_message,ccv_message) {	var ret = true;	var message = '';	var cc_number = document.getElementById('cc_number');	var cc_ccv = document.getElementById('cc_ccv');	var firstname = document.getElementById('firstname');	var lastname = document.getElementById('lastname');		if (cc_number.value.length < 13 || cc_number.value.length > 16) { ret = false; message += card_message+"\n"; cc_number.className += " rs_error"; } else { cc_number.className.replace(new RegExp(" rs_error\\b"), ''); }	if (cc_ccv.value.length < 3 || cc_ccv.value.length > 4) { ret = false; message += ccv_message+"\n"; cc_ccv.className += " rs_error"; } else { cc_ccv.className.replace(new RegExp(" rs_error\\b"), ''); }	if (firstname.value == '') { ret = false; firstname.className += " rs_error"; } else { firstname.className.replace(new RegExp(" rs_error\\b"), ''); }	if (lastname.value == '') { ret = false; lastname.className += " rs_error"; } else { lastname.className.replace(new RegExp(" rs_error\\b"), ''); }		if (message.length != 0)		alert(message);		return ret;}	/** *	Allow only numeric values */	function rs_check_card(what) {	what.value = what.value.replace(/[^0-9]+/g, '');}/** *	Credit card validation */	function rs_cc_form() {	var has_error = false;		var cc_number = document.getElementById('cc_number');	var cc_length = cc_number.value.length;	if (cc_length < 14 || cc_length > 19) {		if (cc_number.className.indexOf(' rs_error') == -1) cc_number.className += " rs_error";		has_error = true;	} else {		cc_number.className = cc_number.className.replace(new RegExp(" rs_error\\b"), '');	}		var csc_number = document.getElementById('cc_csc');	if (csc_number.value.length < 3) {		if (csc_number.className.indexOf(' rs_error') == -1) csc_number.className += " rs_error";		has_error = true;	} else {		csc_number.className = csc_number.className.replace(new RegExp(" rs_error\\b"), '');	}		var cc_fname   = document.getElementById('cc_fname');	if (cc_fname.value.length == 0) {		if (cc_fname.className.indexOf(' rs_error') == -1) cc_fname.className += " rs_error";		has_error = true;	} else {		cc_fname.className = cc_fname.className.replace(new RegExp(" rs_error\\b"), '');	}			var cc_lname   = document.getElementById('cc_lname');	if (cc_lname.value.length == 0) {		if (cc_lname.className.indexOf(' rs_error') == -1) cc_lname.className += " rs_error";		has_error = true;	} else {		cc_lname.className = cc_lname.className.replace(new RegExp(" rs_error\\b"), '');	}		return has_error ? false : true;}/** *	Calendar month change */	function rs_calendar(root,month,year,module) {	document.getElementById('rscalendarmonth'+module).style.display = 'none';	document.getElementById('rscalendar'+module).style.display = '';		var req = new Request({		method: 'post',		url: root + 'index.php?option=com_rseventspro',		data: 'view=calendar&layout=module&format=raw&month=' + month + '&year=' + year + '&mid=' + module + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						document.getElementById('rs_calendar_module'+module).innerHTML = response;			document.getElementById('rscalendarmonth'+module).style.display = '';			document.getElementById('rscalendar'+module).style.display = 'none';						$$('.hasTip').each(function(el) {				var title = el.get('title');				if (title) {					var parts = title.split('::', 2);					el.store('tip:title', parts[0]);					el.store('tip:text', parts[1]);				}			});			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});		}	});	req.send();}/** *	Close ajax search window */	function rsepro_ajax_close() {	RSEProSearch.slideOut();}/** *	Ajax search */	function rsepro_search(root,itemid,opener) {	var req = new Request({		method: 'post',		url: root + 'index.php?option=com_rseventspro',		data: 'task=ajax&search=' + $('rsepro_ajax').value + '&iid=' + itemid + '&opener=' + opener + '&randomTime='+Math.random(),		onSuccess: function(responseText, responseXML) {			var response = responseText;			var start = response.indexOf('RS_DELIMITER0') + 13;			var end = response.indexOf('RS_DELIMITER1');			response = response.substring(start, end);						if (response != '') {				$('rsepro_ajax_list').set('html', response);				RSEProSearch.slideIn();			} else RSEProSearch.slideOut();		}	});	req.send();}/** *	Check dates for the search module */	function rs_check_dates() {	if (isset(document.getElementById('enablestart'))) {		if (document.getElementById('enablestart').checked) {			document.getElementById('rsstart').disabled = false;			document.getElementById('rsstart_img').style.display = '';		} else {			document.getElementById('rsstart').disabled = true;			document.getElementById('rsstart_img').style.display = 'none';		}	}			if (isset(document.getElementById('enableend'))) {		if (document.getElementById('enableend').checked) {			document.getElementById('rsend').disabled = false;			document.getElementById('rsend_img').style.display = '';		} else {			document.getElementById('rsend').disabled = true;			document.getElementById('rsend_img').style.display = 'none';		}	}}/** *	Check search module */	function rsepro_search_form_verification() {	if (document.getElementById('rskeyword').value == '')		return false;		return true;}/** *	Add selected location */	function rs_add_loc() {	if (isset($('rs_location_window'))) {		$('rs_location_window').reveal({duration: 'short'});		$('rs_new_location').innerHTML = $('rs_location').value;	}}/** *	Show more details */	function show_more() {	$('less').style.display = '';	$('more').style.display = 'none';	$('rs_repeats').style.height = 'auto';}/** *	Show less details */		function show_less() {	$('less').style.display = 'none';	$('more').style.display = '';	$('rs_repeats').style.height = '70px';}window.addEvent('domready', function(){	if (isset($('rs_repeats_control')))	{		if (parseInt($('rs_repeats').scrollHeight) > 70)			$('rs_repeats_control').style.display = '';	}});	var rs_tooltip=function(){	var id = 'rs_tt';	var top = 3;	var left = 3;	var maxw = 400;	var speed = 10;	var timer = 20;	var endalpha = 95;	var alpha = 0;	var tt,t,c,b,h;	var ie = document.all ? true : false;	return{		show:function(v,w){			if(tt == null){				tt = document.createElement('div');				tt.setAttribute('id',id);				t = document.createElement('div');				t.setAttribute('id',id + 'top');				c = document.createElement('div');				c.setAttribute('id',id + 'cont');				b = document.createElement('div');				b.setAttribute('id',id + 'bot');				tt.appendChild(t);				tt.appendChild(c);				tt.appendChild(b);				document.body.appendChild(tt);				tt.style.opacity = 0;				tt.style.filter = 'alpha(opacity=0)';				document.onmousemove = this.pos;			}			tt.style.display = 'block';			c.innerHTML = document.getElementById(v).innerHTML;			tt.style.width = w ? w + 'px' : 'auto';			if(!w && ie){				t.style.display = 'none';				b.style.display = 'none';				tt.style.width = tt.offsetWidth;				t.style.display = 'block';				b.style.display = 'block';			}			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}			h = parseInt(tt.offsetHeight) + top;			clearInterval(tt.timer);			tt.timer = setInterval(function(){rs_tooltip.fade(1)},timer);		},		pos:function(e){			var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;			tt.style.top = (u - h) + 'px';			tt.style.left = (l + left) + 'px';		},		fade:function(d){			var a = alpha;			if((a != endalpha && d == 1) || (a != 0 && d == -1)){				var i = speed;				if(endalpha - a < speed && d == 1){					i = endalpha - a;				}else if(alpha < speed && d == -1){					i = a;				}				alpha = a + (i * d);				tt.style.opacity = alpha * .01;				tt.style.filter = 'alpha(opacity=' + alpha + ')';			}else{				clearInterval(tt.timer);				if(d == -1){tt.style.display = 'none'}			}		},		hide:function(){			clearInterval(tt.timer);			tt.timer = setInterval(function(){rs_tooltip.fade(-1)},timer);		}	};}();