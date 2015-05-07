<?php defined('_JEXEC') or die;
JHTML::_('behavior.modal');
$Itemid=JRequest::getVar('Itemid',0);
$db = JFactory::getDBO();
$user=&JFactory::getUser();
$user_id=$user->get('id');
$document = & JFactory::getDocument();
$_title_tag=JText::_('INFORMATION_TITLE');
$document->setTitle($_title_tag);
$mainframe = JFactory::getApplication();
if($user_id==''||$user_id==0){
	$mainframe->redirect(JURI::root().'index.php?option=com_fwuser&view=login&Itemid='.$Itemid);
}
echo '<link rel="stylesheet" href="'.JURI::root().'components/com_fwuser/css/css.css" type="text/css" />';
echo '<script type="text/javascript" src="'.JURI::root().'components/com_fwuser/js/js.js"></script>';
$query="select * from #__fwuser_user where user_id=".$user_id." ";
$db->setQuery($query);
$row_user=$db->loadObject();

$options = array();
$options[] = JHTML::_('select.option', '', '');
$options[] = JHTML::_('select.option', 'Mr', 'Mr');
$options[] = JHTML::_('select.option', 'Mrs', 'Mrs');
$options[] = JHTML::_('select.option', 'Ms', 'Mrs');
$options[] = JHTML::_('select.option', 'Company', 'Company');
$dropdown_title = JHTML::_('select.genericlist', $options, 'title', 'class="inputbox" autocomplete="false" ', 'value', 'text', $row_user->title);

$query="select * from #__fwuser_country order by name ";
$db->setQuery($query);
$rows_country=$db->loadObjectList();
$options = array();
$options[] = JHTML::_('select.option', 0, JText::_('FI_COUNTRY'));
for($i=0;$i<count($rows_country);$i++){
	$options[] = JHTML::_('select.option', $rows_country[$i]->id, $rows_country[$i]->name);
}
$dropdown_country = JHTML::_('select.genericlist', $options, 'country', 'class="inputbox" onchange="changeCountry();" autocomplete="false" ', 'value', 'text', $row_user->country);

$query ="select * from #__fwuser_state";
$query.=" where country_id=".$row_user->country." ";
$query.=" order by name ";
$db->setQuery($query);
$rows_state=$db->loadObjectList();
$options = array();
$options[] = JHTML::_('select.option', 0, JText::_('FI_STATE'));
for($i=0;$i<count($rows_state);$i++){
	$options[] = JHTML::_('select.option', $rows_state[$i]->id, $rows_state[$i]->name);
}
$dropdown_state = '<div id="wrap_state">'.JHTML::_('select.genericlist', $options, 'state', 'class="inputbox" id="state"', 'value', 'text', $row_user->state).'</div>';
?>
<script>
function checkRegister(){
	var re=/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	var v = new RegExp();
	v.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");

	if($('email').value==''){
		alert("<?php echo JText::_('ASK_EMAIL'); ?>");
		$('email').focus();
	}else if(re.test($('email').value)==false){
		alert("<?php echo JText::_('ASK_EMAIL_WRONG'); ?>")
		$('email').focus();
	}else if($('username').value==''){
		alert("<?php echo JText::_('ASK_USERNAME'); ?>");
		$('username').focus();
	}else if($('password').value!=''&&$('password').value!=$('re_password').value){
		alert("<?php echo JText::_('ASK_PASSWORD_WRONG'); ?>");
		$('re_password').focus();
	}else if($('title').value==''){
		alert("<?php echo JText::_('ASK_TITLE'); ?>");
		$('title').focus();
	}else if($('title').value=='Company'&&$('company').value==''){
		alert("<?php echo JText::_('ASK_COMPANY'); ?>");
		$('title').focus();
	}else if($('firstname').value==''){
		alert("<?php echo JText::_('ASK_FIRSTNAME'); ?>");
		$('firstname').focus();
	}else if($('lastname').value==''){
		alert("<?php echo JText::_('ASK_LASTNAME'); ?>");
		$('lastname').focus();
	}else if($('address').value==''){
		alert("<?php echo JText::_('ASK_ADDRESS'); ?>");
		$('address').focus();
	}else if($('city').value==''){
		alert("<?php echo JText::_('ASK_CITY'); ?>");
		$('city').focus();
	}else if($('zip').value==''){
		alert("<?php echo JText::_('ASK_ZIP'); ?>");
		$('zip').focus();
	}else if($('country').value==0){
		alert("<?php echo JText::_('ASK_COUNTRY'); ?>");
		$('country').focus();
	}else if($('state').value==''){
		alert("<?php echo JText::_('ASK_STATE'); ?>");
		$('state').focus();
	}else if($('phone').value==''){
		alert("<?php echo JText::_('ASK_PHONE'); ?>");
		$('phone').focus();
	}else{
		$('userinfoform').submit();
	}
}
function changeCountry(){
	var req = new Request({
		method: 'post',
		url: "index.php?option=com_fwuser&view=state&format=raw",
		data: { 
			'id' : $('country').value
		},
		onRequest: function() {
			$('wrap_state').set('text', '<?php echo JText::_('PLEASE_WAIT'); ?>...');
		},
		onComplete: function(response) { 
			$('wrap_state').innerHTML=response;
		}
	}).send();
}
</script>
<div class="fwuser_wrap item-page">
	<h2 class="item_title"><?php echo JText::_('INFORMATION_TITLE'); ?></h2>
	<div class="item_body">
		<p id="form_field_wait" class="form_field_wait" style="display:none;">
			<?php echo JText::_('PLEASE_WAIT'); ?>
		</p>
		<div id="form_field" class="form_field">
<form name="userinfoform" id="userinfoform" enctype="multipart/form-data" action="<?php echo JURI::root().'index.php?option=com_fwuser&task=saveinfo&Itemid='.$Itemid; ?>" method="post">
			<div class="field_title">
				Customer Information
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_EMAIL'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->email; ?>" id="email" name="email"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_USERNAME'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->username; ?>" id="username" name="username"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_PASSWORD'); ?><span>*</span></label>
				<div class="inputbox"><input type="password" size="50" class="" value="" id="password" name="password"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_RE_PASSWORD'); ?><span>*</span></label>
				<div class="inputbox"><input type="password" size="50" class="" value="" id="re_password" name="re_password"></div>
			</div>
			<div class="field_title">
				Bill To Information
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_COMPANY_NAME'); ?></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->company; ?>" id="company" name="company"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_TITLE'); ?></label>
				<div class="listbox"><?php echo $dropdown_title; ?></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_FIRSTNAME'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->firstname; ?>" id="firstname" name="firstname"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_MIDLENAME'); ?></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->lastname; ?>" id="lastname" name="lastname"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_LASTNAME'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->midlename; ?>" id="midlename" name="midlename"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_ADDRESS'); ?> 1<span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->address; ?>" id="address" name="address"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_ADDRESS'); ?> 2</label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->address1; ?>" id="address1" name="address1"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_CITY'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->city; ?>" id="city" name="city"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_ZIP'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->zip; ?>" id="zip" name="zip"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_COUNTRY'); ?><span>*</span></label>
				<div class="listbox"><?php echo $dropdown_country; ?></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_STATE'); ?><span>*</span></label>
				<div class="listbox"><?php echo $dropdown_state; ?></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_PHONE'); ?><span>*</span></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->phone; ?>" id="phone" name="phone"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_MOBILEPHONE'); ?></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->mobilephone; ?>" id="mobilephone" name="mobilephone"></div>
			</div>
			<div class="field">
				<label class="label"><?php echo JText::_('FI_FAX'); ?></label>
				<div class="inputbox"><input type="text" size="50" class="" value="<?php echo $row_user->fax; ?>" id="fax" name="fax"></div>
			</div>
			<div class="field">
				<label class="label">&nbsp;</label>
				<input type="button" class="button" value="<?php echo JText::_('FI_SAVE'); ?>" onclick="checkRegister();">
			</div>
			<div class="field">
				<label class="label">&nbsp;</label>
				<label class="label1"><i><?php echo JText::_('FI_MUSTFILL'); ?></i></label>
			</div>
</form>
		</div>
	</div>
</div>