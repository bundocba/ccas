<?php
/**
 * @version    $Id: index.php 21720 2011-07-01 08:31:15Z chdemko $
 * @package    Joomla.Site
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */ 

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHtml::_('behavior.framework', true);
include_once (dirname(__FILE__).DS.'tplhelper.php');
$tpl     = new tplhelper($this);
$class     = $tpl->getClass();
$page_title = $tpl->getPageTitle();

$menus    =& JSite::getMenu();
$menu     = $menus->getActive();
if (is_object($menu)) {
  $params   = $menus->getParams($menu->id);
  $pageclass   = $params->get( 'pageclass_sfx' );
}

/* The following line gets the application object for things like displaying the site name */
$app    = JFactory::getApplication();
$RightColumn        = $this->countModules('right');


$app = JFactory::getApplication();
$this->setTitle($this->getTitle().' :: '.$app->getCfg('sitename'));
$doc =& JFactory::getDocument();
$doc->setMetaData( 'Title',$this->getTitle() );

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
  <head>
    <!-- The following JDOC Head tag loads all the header and meta information from your site config and content. -->
    
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/global.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/fw.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/menu.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo JURI::root();?>fancybox/jquery.fancybox-1.3.4.css" type="text/css" />

  <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/ie7.css" type="text/css" />
  <![endif]-->
  
  <!--[if IE 8]>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/ie8.css" type="text/css" />
  <![endif]-->
 
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/scripts/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/scripts/default.js"></script>

    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/scripts/js/jquery-1.7.2.min.js"></script>    
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/scripts/js/jquery.selectbox-0.2.js"></script>  
  
  <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/jquery.selectbox.css" type="text/css" />
    
    <script type="text/javascript" src="<?php echo JURI::root();?>fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="<?php echo JURI::root();?>js/js.js"></script>    
   <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery("#Send").selectbox();
      jQuery("#Salutation").selectbox();
      jQuery("#Designation").selectbox();
      jQuery("#Country").selectbox();
      jQuery("#Country2").selectbox();
      jQuery("#Designation2").selectbox();
      jQuery("#Industry").selectbox();
      jQuery("#Type1").selectbox();
      jQuery("#Type2").selectbox();
      jQuery("#No_of_seats").selectbox();

      
      jQuery(".topmenu ul.menu li:first-child").addClass('first');
          
      jQuery(".topmenu ul.menu li:last-child").addClass("end");  
    });
  </script>
 


<script type="text/javascript">

        jQuery(function(){
            //Salutation
            jQuery("#other_reason_main_salutation").hide();
            jQuery(".cl_other_salutation").hide(); 

      
      var othersOption_1 = jQuery('.rsform-block-salutation .sbSelector');
      
      if(othersOption_1.html()== "Other"){
        jQuery("#other_reason_main_salutation").toggle();
        

        jQuery(".cl_other_salutation").toggle();

      }    

            jQuery('#Salutation').change(function() {
              if(jQuery(this).find('option:selected').val() == "Other"){
                jQuery("#other_reason_main_salutation").toggle();

        jQuery(".cl_other_salutation").toggle();
              }else{
                jQuery("#other_reason_main_salutation").hide();
        jQuery(".cl_other_salutation").hide();
              }
            });
      
          /*   jQuery("#other_reason_main_salutation").keyup(function(ev){
        jQuery("#other_reason_main_salutation").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-salutation .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Salutation').find('option:selected').attr('value',jQuery(this).val());
                
            });  
       */
            //Designation      
      jQuery("#other_reason_main_designation").hide();
            jQuery(".cl_other_designation").hide();

      var othersOption_1 = jQuery('.rsform-block-designation .sbSelector');
      
      if(othersOption_1.html()== "Others"){
        jQuery("#other_reason_main_designation").toggle();
        jQuery(".cl_other_designation").toggle();
      }
       
            jQuery('#Designation').change(function() {
              if(jQuery(this).find('option:selected').val() == "Others"){
                jQuery("#other_reason_main_designation").toggle();
        jQuery(".cl_other_designation").toggle();
              }else{
                jQuery("#other_reason_main_designation").hide();
        jQuery(".cl_other_designation").hide();
              }
            });  
/*       
            jQuery("#other_reason_main_designation").keyup(function(ev){
        jQuery("#other_reason_main_designation").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-designation .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Designation').find('option:selected').attr('value',jQuery(this).val());
                
            });  
 */

           //Designation2      
      jQuery("#other_reason_main_designation2").hide();
            jQuery(".cl_other_designation2").hide();

      var othersOption_1 = jQuery('.rsform-block-designation2 .sbSelector');
      
      if(othersOption_1.html()== "Others"){
        jQuery("#other_reason_main_designation2").toggle();
        jQuery(".cl_other_designation2").toggle();
      }
       
            jQuery('#Designation2').change(function() {
              if(jQuery(this).find('option:selected').val() == "Others"){
                jQuery("#other_reason_main_designation2").toggle();
        jQuery(".cl_other_designation2").toggle();
              }else{
                jQuery("#other_reason_main_designation2").hide();
        jQuery(".cl_other_designation2").hide();
              }
            });  
      
/*             jQuery("#other_reason_main_designation2").keyup(function(ev){
        jQuery("#other_reason_main_designation2").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-designation2 .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Designation2').find('option:selected').attr('value',jQuery(this).val());
                
            }); */  


          //Industry      
      jQuery("#other_reason_main_industry").hide();
            jQuery(".cl_other_industry").hide();

      var othersOption_1 = jQuery('.rsform-block-industry .sbSelector');
      
      if(othersOption_1.html()== "If others, please specify"){
        jQuery("#other_reason_main_industry").toggle();
        jQuery(".cl_other_industry").toggle();
      }
       
            jQuery('#Industry').change(function() {
              if(jQuery(this).find('option:selected').val() == "If others, please specify"){
                jQuery("#other_reason_main_industry").toggle();
        jQuery(".cl_other_industry").toggle();
              }else{
                jQuery("#other_reason_main_industry").hide();
        jQuery(".cl_other_industry").hide();
              }
            });  
      
/*             jQuery("#other_reason_main_industry").keyup(function(ev){
        jQuery("#other_reason_main_industry").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-industry .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Industry').find('option:selected').attr('value',jQuery(this).val());
                
            });  
 */

         //Type1      
      jQuery("#other_reason_main_type1").hide();
            jQuery(".cl_other_type1").hide();

      var othersOption_1 = jQuery('.rsform-block-type1 .sbSelector');
      
      if(othersOption_1.html()== "If others, please specify"){
        jQuery("#other_reason_main_type1").toggle();
        jQuery(".cl_other_type1").toggle();
      }
       
            jQuery('#Type1').change(function() {
              if(jQuery(this).find('option:selected').val() == "If others, please specify"){
                jQuery("#other_reason_main_type1").toggle();
        jQuery(".cl_other_type1").toggle();
              }else{
                jQuery("#other_reason_main_type1").hide();
        jQuery(".cl_other_type1").hide();
              }
            });  
      
/*             jQuery("#other_reason_main_type1").keyup(function(ev){
        jQuery("#other_reason_main_type1").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-type1 .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Type1').find('option:selected').attr('value',jQuery(this).val());
                
            });  
 */
      
      
         //Type2      


      jQuery("#other_reason_main_type2").hide();
            jQuery(".cl_other_type2").hide();

      var othersOption_1 = jQuery('.rsform-block-type2 .sbSelector');
      
      if(othersOption_1.html()== "If others, please specify"){
        jQuery("#other_reason_main_type2").toggle();
        jQuery(".cl_other_type2").toggle();
      }
       
            jQuery('#Type2').change(function() {
              if(jQuery(this).find('option:selected').val() == "If others, please specify"){
                jQuery("#other_reason_main_type2").toggle();
        jQuery(".cl_other_type2").toggle();
              }else{
                jQuery("#other_reason_main_type2").hide();
        jQuery(".cl_other_type2").hide();
              }
            });  
      
/*             jQuery("#other_reason_main_type2").keyup(function(ev){
        jQuery("#other_reason_main_type2").css('border','1px solid #A79F9F');
   
                var othersOption = jQuery('.rsform-block-type2 .sbSelector');
        othersOption.html(jQuery(this).val());
        jQuery('#Type2').find('option:selected').attr('value',jQuery(this).val());
                
            });  
 */


      
      // Submit
      
            jQuery('#userForm').submit(function() {
      
        //Salutation        
        var othersOption =  jQuery('.rsform-block-salutation .sbHolder').find('sbHolder');
        if(othersOption.val() == "Other")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_salutation").val());
        }
        var othersOption1 = jQuery('.rsform-block-salutation .sbSelector');
        if(othersOption1.html()== "Other"){
          if(jQuery('#other_reason_main_salutation').val()== ''){
            alert('Please fill the Salutation Others');
            jQuery('#other_reason_main_salutation').focus();
            return false;
          }
        }

              
        //Designation        
        var othersOption =  jQuery('.rsform-block-designation .sbHolder').find('sbHolder');
        if(othersOption.val() == "Others")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_designation").val());
        }
        var othersOption1 = jQuery('.rsform-block-designation .sbSelector');
        if(othersOption1.html()== "Others"){
          if(jQuery('#other_reason_main_designation').val()== ''){
            alert('Please fill the Designation Others');
            jQuery('#other_reason_main_designation').focus();
            return false;
          }
        }
        
        //Designation2      
        var othersOption =  jQuery('.rsform-block-designation2 .sbHolder').find('sbHolder');
        if(othersOption.val() == "Others")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_designation2").val());
        }
        var othersOption1 = jQuery('.rsform-block-designation2 .sbSelector');
        if(othersOption1.html()== "Others"){
          if(jQuery('#other_reason_main_designation2').val()== ''){
            alert('Please fill the Designation Others');
            jQuery('#other_reason_main_designation2').focus();
            return false;
          }
        }        
        
        //Industry      
        var othersOption =  jQuery('.rsform-block-industry .sbHolder').find('sbHolder');
        if(othersOption.val() == "If others, please specify")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_industry").val());
        }
        var othersOption1 = jQuery('.rsform-block-industry .sbSelector');
        if(othersOption1.html()== "If others, please specify"){
          if(jQuery('#other_reason_main_industry').val()== ''){
            alert('Please fill the industry others');
            jQuery('#other_reason_main_industry').focus();
            return false;
          }
        }    
        
        //Type 1      
        var othersOption =  jQuery('.rsform-block-type1 .sbHolder').find('sbHolder');
        if(othersOption.val() == "If others, please specify")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_type1").val());
        }
        var othersOption1 = jQuery('.rsform-block-type1 .sbSelector');
        if(othersOption1.html()== "If others, please specify"){
          if(jQuery('#other_reason_main_type1').val()== ''){
            alert('Please fill the type1 others');
            jQuery('#other_reason_main_type1').focus();
            return false;
          }
        }    
        
        //Type 2      
        var othersOption =  jQuery('.rsform-block-type2 .sbHolder').find('sbHolder');
        if(othersOption.val() == "If others, please specify")
        {
          // replace select value with text field value
          othersOption.val(jQuery("#other_reason_main_type2").val());
        }
        var othersOption1 = jQuery('.rsform-block-type2 .sbSelector');
        if(othersOption1.html()== "If others, please specify"){
          if(jQuery('#other_reason_main_type2').val()== ''){
            alert('Please fill the type2 others');
            jQuery('#other_reason_main_type2').focus();
            return false;
          }
        }          
        
            });
        });
    </script>

  
  

<script type="text/javascript">
var checkboxHeight = "25";
var radioHeight = "25";
var selectWidth = "190";


/* No need to change anything after this */


document.write('<style type="text/css">input.styled { display: none; } select.styled { position: relative; width: ' + selectWidth + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; } .disabled { opacity: 0.5; filter: alpha(opacity=50); }</style>');

var Custom = {
  init: function() {
    var inputs = document.getElementsByTagName("input"), span = Array(), textnode, option, active;
    for(a = 0; a < inputs.length; a++) {
      if((inputs[a].type == "checkbox" || inputs[a].type == "radio") && inputs[a].className.indexOf("styled") > -1) {
        span[a] = document.createElement("span");
        span[a].className = inputs[a].type;

        if(inputs[a].checked == true) {
          if(inputs[a].type == "checkbox") {
            position = "0 -" + (checkboxHeight*2) + "px";
            span[a].style.backgroundPosition = position;
          } else {
            position = "0 -" + (radioHeight*2) + "px";
            span[a].style.backgroundPosition = position;
          }
        }
        inputs[a].parentNode.insertBefore(span[a], inputs[a]);
        inputs[a].onchange = Custom.clear;
        if(!inputs[a].getAttribute("disabled")) {
          span[a].onmousedown = Custom.pushed;
          span[a].onmouseup = Custom.check;
        } else {
          span[a].className = span[a].className += " disabled";
        }
      }
    }
    inputs = document.getElementsByTagName("select");
    for(a = 0; a < inputs.length; a++) {
      if(inputs[a].className.indexOf("styled") > -1) {
        option = inputs[a].getElementsByTagName("option");
        active = option[0].childNodes[0].nodeValue;
        textnode = document.createTextNode(active);
        for(b = 0; b < option.length; b++) {
          if(option[b].selected == true) {
            textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
          }
        }
        span[a] = document.createElement("span");
        span[a].className = "select";
        span[a].id = "select" + inputs[a].name;
        span[a].appendChild(textnode);
        inputs[a].parentNode.insertBefore(span[a], inputs[a]);
        if(!inputs[a].getAttribute("disabled")) {
          inputs[a].onchange = Custom.choose;
        } else {
          inputs[a].previousSibling.className = inputs[a].previousSibling.className += " disabled";
        }
      }
    }
    document.onmouseup = Custom.clear;
  },
  pushed: function() {
    element = this.nextSibling;
    if(element.checked == true && element.type == "checkbox") {
      this.style.backgroundPosition = "0 -" + checkboxHeight*3 + "px";
    } else if(element.checked == true && element.type == "radio") {
      this.style.backgroundPosition = "0 -" + radioHeight*3 + "px";
    } else if(element.checked != true && element.type == "checkbox") {
      this.style.backgroundPosition = "0 -" + checkboxHeight + "px";
    } else {
      this.style.backgroundPosition = "0 -" + radioHeight + "px";
    }
  },
  check: function() {
    element = this.nextSibling;
    if(element.checked == true && element.type == "checkbox") {
      this.style.backgroundPosition = "0 0";
      element.checked = false;
    } else {
      if(element.type == "checkbox") {
        this.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
      } else {
        this.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
        group = this.nextSibling.name;
        inputs = document.getElementsByTagName("input");
        for(a = 0; a < inputs.length; a++) {
          if(inputs[a].name == group && inputs[a] != this.nextSibling) {
            inputs[a].previousSibling.style.backgroundPosition = "0 0";
          }
        }
      }
      element.checked = true;
    }
  },
  clear: function() {
    inputs = document.getElementsByTagName("input");
    for(var b = 0; b < inputs.length; b++) {
      if(inputs[b].type == "checkbox" && inputs[b].checked == true && inputs[b].className.indexOf("styled") > -1) {
        inputs[b].previousSibling.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
      } else if(inputs[b].type == "checkbox" && inputs[b].className.indexOf("styled") > -1) {
        inputs[b].previousSibling.style.backgroundPosition = "0 0";
      } else if(inputs[b].type == "radio" && inputs[b].checked == true && inputs[b].className.indexOf("styled") > -1) {
        inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
      } else if(inputs[b].type == "radio" && inputs[b].className.indexOf("styled") > -1) {
        inputs[b].previousSibling.style.backgroundPosition = "0 0";
      }
    }
  },
  choose: function() {
    option = this.getElementsByTagName("option");
    for(d = 0; d < option.length; d++) {
      if(option[d].selected == true) {
        document.getElementById("select" + this.name).childNodes[0].nodeValue = option[d].childNodes[0].nodeValue;
      }
    }
  }
}
window.onload = Custom.init;

</script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50116628-1', 'ccas.org.sg');
  ga('send', 'pageview');

</script>
  </head>
  
  <body <?php echo $pageclass?'class="'.$pageclass.'"':'' ?>>
 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <div id="container1">
      <div id="head_menu"  class="Centered">
        <div class="topmenu">
          <jdoc:include type="modules" name="login"   />
          <jdoc:include type="modules" name="topmenu"   />
      <div class="fw_search">
        <jdoc:include type="modules" name="search"   />
      </div>
        </div>
    
      </div>
    </div>  
  <div id="container2">
    <div id="header" class="Centered">
      <h1>
        <a id="logo" href="<?php echo JURI::root(); ?>" title="<?php echo $app->getCfg('sitename');?>">
          <jdoc:include type="modules" name="logo"   style="xhtml"/>
        </a>
      </h1>
      <div id="pos_0">   
        <div class="fw_social">
        <jdoc:include type="modules" name="social"   />
        </div>

      </div>
    </div>
  </div>
    

    
    <div id="container3">
      <?php if ($this->countModules('banner')){?>
      <div id="banner" class="Centered" >
        <div class="tw_banner">
          <jdoc:include type="modules" name="banner"  style="xhtml" />
        </div>
      </div>
    
      <div id="main" class="Centered">
      
        <?php if ($this->countModules('left')): ?>
        <div class="left">
          <jdoc:include type="modules" name="left" style="xhtml" />
        </div>
        <?php endif;?>
        <?php if ($this->countModules('user1')): ?>
        <div class="tw_center">
          <jdoc:include type="modules" name="user1" style="xhtml"  />
        </div>
        <?php endif;?>

        <div class="<?php echo $RightColumn ? 'content' : 'contentfull'; ?>">
      <jdoc:include type="message" />
      <jdoc:include type="component" />
        </div>
    
    <?php if ($this->countModules('right')): ?>
        <div class="sidebar">
          <jdoc:include type="modules" name="right" style="fwhtml"  />
        </div>
        <?php endif;?>

      </div>
<?php    } else{  ?>
     <div id="mains" class="Centered">
      
        <?php if ($this->countModules('left')): ?>
        <div class="left">
          <jdoc:include type="modules" name="left" style="xhtml" />
        </div>
        <?php endif;?>
        <?php if ($this->countModules('user1')): ?>
        <div class="tw_center">
          <jdoc:include type="modules" name="user1" style="xhtml"  />
        </div>
        <?php endif;?>

        <div class="<?php echo $RightColumn ? 'content' : 'contentfull'; ?>">
      <jdoc:include type="message" />
      <jdoc:include type="component" />
        </div>
    
    <?php if ($this->countModules('right')): ?>
        <div class="sidebar">
          <jdoc:include type="modules" name="right" style="fwhtml"  />
        </div>
        <?php endif;?>

      </div>
<?php } ?>
    
      <?php if ($this->countModules('position-5')): ?>
    <div id="group">
      <div class="Centered">
        <div class="box">
        <jdoc:include type="modules" name="position-5" style="xhtml"  />
        <div class="clear"></div>
        </div>
      </div>
    </div>
      <?php endif;?>
    <div id="footer">
      <div class="Centered">
        <div class="box">
        <div class="footer-a">
          <jdoc:include type="modules" name="footer-a" style="xhtml"  />
        </div>
        <div class="footer-b">
          <jdoc:include type="modules" name="footer-b" style="xhtml"   />
        </div>
        </div>
      </div>
    </div>
    </div>
  </body>
</html>
