<?php
/**
  * @version     3.0 +
  * @package       Open Source Excellence Security Suite
  * @subpackage    Open Source Excellence CPU
  * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
  * @author        Created on 30-Sep-2010
  * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
  *
  *
  *  This program is free software: you can redistribute it and/or modify
  *  it under the terms of the GNU General Public License as published by
  *  the Free Software Foundation, either version 3 of the License, or
  *  (at your option) any later version.
  *
  *  This program is distributed in the hope that it will be useful,
  *  but WITHOUT ANY WARRANTY; without even the implied warranty of
  *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  *  GNU General Public License for more details.
  *
  *  You should have received a copy of the GNU General Public License
  *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
  *  @Copyright Copyright (C) 2008 - 2010- ... Open Source Excellence
*/
defined('_JEXEC') or die("Direct Access Not Allowed");
?>
<script type="text/javascript" >
Ext.ns('oseATH','oseATHReportL1');
oseATH.msg = new Ext.App();
function viewdetail(id)
{
	var win = new Ext.Window({
			id:'attackdetail',
			title: Joomla.JText._('Attack_information'),
            layout:'fit',
            width:1024,
            height:500,
            closeAction:'close',
            closable:'true',
            collapsible:'true',
            autoScroll:'true'
	})

	Ext.Ajax.request({
				url : 'index.php' ,
				params : {
					option : 'com_ose_antihacker',
					task:'viewAttackDetail',
					controller:'report',
					id: id,
					layer: 'l1'
				},
				method: 'POST',
				success: function ( result, request ) {
					msg = Ext.decode(result.responseText);
					if (msg.status!='ERROR')
					{
						win.show();
						win.update(msg.result);
					}
					else
					{
						Ext.Msg.alert(Joomla.JText._('ERROR'));
					}
				}
		});
}
Ext.onReady(function(){
		oseATHReportL1.cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: false
        },
        columns: [
            new Ext.grid.RowNumberer({header:'#'})
            ,{id: 'id', header: Joomla.JText._('ID'),  hidden:false, dataIndex: 'id', width: 20, sortable:true}
            ,{id: 'signature', header: Joomla.JText._('Signature'),  hidden:false, dataIndex: 'signature', sortable:true}
            ,{id: 'signatureaction', header: Joomla.JText._('Signature_Action'),  hidden:false, dataIndex: 'signatureaction', sortable:true}
            ,{id: 'target', header: Joomla.JText._('Target'),  hidden:false, dataIndex: 'target', sortable:true}
            ,{id: 'targetaction', header: Joomla.JText._('Target_Action'),  hidden:false, dataIndex: 'targetaction', sortable:true}
            ,{id: 'view', header: Joomla.JText._('View_Detail'),  hidden:false, dataIndex: 'view', width: 20}
	    ],
	    sortInfo:{field: 'id', direction: "ASC"}
    });

		oseATHReportL1.store = new Ext.data.GroupingStore({
		  proxy: new Ext.data.HttpProxy({
	            url: 'index.php?option=com_ose_antihacker&controller=report',
	            method: 'POST'
	      }),
		  baseParams:{task: "getlayer1AttackList",limit: 25},
		  reader: new Ext.data.JsonReader({
		    root: 'results',
		    totalProperty: 'total'
		  },[
		    {name: 'id', type: 'int', mapping: 'id'},
		    {name: 'signature', type: 'string', mapping: 'signature'},
		    {name: 'signatureaction', type: 'string', mapping: 'signatureaction'},
		    {name: 'target', type: 'string', mapping: 'target'},
		    {name: 'targetaction', type: 'string', mapping: 'targetaction'},
		    {name: 'view', type: 'string', mapping: 'view'}
		  ]),
		  groupField:'signature',
		  autoLoad:{}
	});

    oseATHReportL1.filters = new Ext.ux.grid.GridFilters({
        encode: true,
        local: false,
        filters: [ {
            type: 'list',
            dataIndex: 'Status',
            options: [[1,Joomla.JText._('Blacklisted')],[2,Joomla.JText._('Monitored')], [3,Joomla.JText._('Whitelisted')]],
            phpMode: true
        }]
    });

	oseATH.oseATHReportL1 = new Ext.grid.GridPanel({
		id: 'oseATHReportL1'
		,cm: oseATHReportL1.cm
		,store: oseATHReportL1.store
		,view: new Ext.grid.GroupingView({
			forceFit:true,
	        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
	 	})
		,viewConfig: {forceFit: true}
		,plugins: [oseATHReportL1.filters]
		,height: 500
		,region: 'west'
		,margins: {top:5, right:5, bottom:5, left:3}
		,tbar: new Ext.Toolbar({
			items: [{
					text: Joomla.JText._('Signature_Action')
		    		,menu:[
					    	{
					        	id: 'blSignature',
					        	xtype: 'button',
					            text: Joomla.JText._('Block_Attacks_Triggering_Selected_Signature'),
					            handler: function(){
					            	Ext.Msg.confirm(Joomla.JText._('Block_attacks_confirmation'), Joomla.JText._('Please_confirm_that_you_would_like_to_block_the_attacks_with_the_selected_signature'), function(btn, text){
										if (btn == 'yes'){
											var sel = oseATH.oseATHReportL1.getSelectionModel();
											var selections = sel.getSelections();
											athAjax('com_ose_antihacker','blSignature','report', selections);
								      	}
					            	})
					            }
					        },{
					        	id: 'wlSignature',
					        	xtype: 'button',
					            text: Joomla.JText._('Ignore_Attacks_Triggering_Selected_Signature'),
					            handler: function(){
					            	Ext.Msg.confirm(Joomla.JText._('Ignore_attacks_confirmation'), Joomla.JText._('Please_confirm_that_you_would_like_to_ignore_the_attacks_with_the_selected_signature'), function(btn, text){
										if (btn == 'yes'){
											var sel = oseATH.oseATHReportL1.getSelectionModel();
											var selections = sel.getSelections();
											athAjax('com_ose_antihacker','wlSignature','report', selections);
								      	}
					            	})
					            }
					        }
				         ]
				    },{
				       text: Joomla.JText._('Target_Action')
					   ,menu:[
					        {
					        	id: 'blTarget',
					            text: Joomla.JText._('Monitor_Selected_Target'),
					            xtype: 'button',
					            handler: function(){
					            	Ext.Msg.confirm(Joomla.JText._('Monitor_target_confirmation'), Joomla.JText._('Please_confirm_that_you_would_like_to_monitor_the_selected_target'), function(btn, text){
										if (btn == 'yes'){
											var sel = oseATH.oseATHReportL1.getSelectionModel();
											var selections = sel.getSelections();
											athAjax('com_ose_antihacker','blTargetlayer1','report', selections);
								      	}
					            	})
					            }
					        },{
					        	id: 'wlTarget',
					            text: Joomla.JText._('Ignore_Selected_Target'),
					            xtype: 'button',
					            handler: function(){
					            	Ext.Msg.confirm(Joomla.JText._('Ignore_target_confirmation'), Joomla.JText._('Please_confirm_that_you_would_like_to_ignore_the_selected_target'), function(btn, text){
										if (btn == 'yes'){
											var sel = oseATH.oseATHReportL1.getSelectionModel();
											var selections = sel.getSelections();
											athAjax('com_ose_antihacker','wlTargetlayer1','report', selections);
								      	}
					            	})
					            }
					        }
				        ]
				     },
				     {
							text: Joomla.JText._('WHITELIST_KEYS')
				    		,menu:[
							    	{
						            id: 'addWLbutton',
						            text: Joomla.JText._('ADD_WHITELIST_KEY'),
						            xtype: 'button',
						            handler: function(){
						            	oseATH.oseATHReportL1.addWin = new Ext.Window({
					            			title: Joomla.JText._('ADD_WHITELIST_KEY')
					            			,modal: true
					            			,border: false
					            			,width: 600
					            			,autoHeight: true
					            			,autoLoad: {
					            				url: 'index.php?option=com_ose_antihacker'
					            				,params:{controller: 'antihacker', task: 'getMod', mod:'addwhitelistkey',type:'addwhitelistkey',name:'form'}
					            				,scripts: true
					            				,callback: function(el,success,response,opt)	{
					            					oseATH.oseATHReportL1.addWin.add(oseATH.oseATHAddWLKEY.form);
					            					oseATH.oseATHReportL1.addWin.doLayout();
					            				}
					            			}
							           	});
						            	oseATH.oseATHReportL1.addWin.show().alignTo(Ext.getBody(),'t-t');
						            }
						        },
						        {
						            id: 'viewWLbutton',
						            text: Joomla.JText._('VIEW_WHITELIST_KEY'),
						            xtype: 'button',
						            handler: function(){
						            	oseATH.oseATHReportL1.addWin = new Ext.Window({
					            			title: Joomla.JText._('VIEW_WHITELIST_KEY')
					            			,modal: true
					            			,border: false
					            			,width: 600
					            			,autoHeight: true
					            			,autoLoad: {
					            				url: 'index.php?option=com_ose_antihacker'
					            				,params:{controller: 'antihacker', task: 'getMod', mod:'addwhitelistkey',type:'addwhitelistkey',name:'grid'}
					            				,scripts: true
					            				,callback: function(el,success,response,opt)	{
					            					oseATH.oseATHReportL1.addWin.add(oseATH.oseATHViewWLKEY.panel);
					            					oseATH.oseATHReportL1.addWin.doLayout();
					            				}
					            			}
							           	});
						            	oseATH.oseATHReportL1.addWin.show().alignTo(Ext.getBody(),'t-t');
						            }
						        }

						        ]
				        },
				     {
				        	id: 'removeRules',
				            text: Joomla.JText._('Remove'),
				            xtype: 'button',
				            handler: function(){
				            	Ext.Msg.confirm(Joomla.JText._('Remove_Record_confirmation'), Joomla.JText._('Please_confirm_that_you_would_like_to_remove_the_selected_record'), function(btn, text){
									if (btn == 'yes'){
										var sel = oseATH.oseATHReportL1.getSelectionModel();
										var selections = sel.getSelections();
										athAjax('com_ose_antihacker','removeL1rules','report', selections);
							      	}
				            	})
				            }
				        },
				        
				        '->',Joomla.JText._('Search'),
				        new Ext.ux.form.SearchField({
			                store: oseATHReportL1.store,
			                paramName: 'search'
			            })
				    ]
		})
		,bbar:new Ext.PagingToolbar({
    		pageSize: 25,
    		store: oseATHReportL1.store,
    		plugins: new Ext.ux.grid.limit({}),
    		displayInfo: true,
    		displayMsg: Joomla.JText._('Displaying_topics')+' {0} - {1} '+Joomla.JText._('of')+' {2}',
		    emptyMsg: Joomla.JText._("No_topics_to_display")

	    })
       });

	    oseATH.oseATHReportL1.panel = new Ext.Panel({
		id: 'oseATHReportL1-panel'
		,border: false
		,layout: 'fit'
		,items:[
			oseATH.oseATHReportL1
		]
		,height: 'auto'
		,width: '100%'
		,renderTo: 'oseantihackerReport'
	});

function athAjax(option, task, controller,selections)
  {
	var i=0;
    ids=new Array();
	for (i=0; i < selections.length; i++)
	{
        ids [i] = selections[i].id;
	}
	ids = Ext.encode(ids);
	// Ajax post scanning request;
	Ext.Ajax.request({
				url : 'index.php' ,
				params : {
					option : option,
					task:task,
					controller:controller,
					ids: ids
				},
				method: 'POST',
				success: function ( result, request ) {
					msg = Ext.decode(result.responseText);
					if (msg.status!='ERROR')
					{
						oseATH.msg.setAlert(msg.status, Joomla.JText._('The_action')+task+Joomla.JText._('was_executed_successfully'));
						oseATHReportL1.store.reload();
					}
					else
					{
						oseATH.msg.setAlert(Joomla.JText._('Error'), msg.result);
						oseATHReportL1.store.reload();
					}
				}
			});
	}
})
</script>