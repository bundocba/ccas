<?php
function FwEventBuildRoute(&$query) {

  $segments = array();

  if(isset($query['view'])) 
  {
    $segments[] = $query['view'];        
    unset($query['view']);
  }
 //custom url   
  if(isset($query['eventid']))
  {
    $segments[] = $query['eventid'];
    unset($query['eventid']);
  }

  return $segments;
}


function FwEventParseRoute($segments) 
{
    //get param
    $vars = array();
    $vars['view']   = $segments[0];
    if(isset($segments[1]))
    	$vars['eventid']  = $segments[1];
    return $vars;
} 