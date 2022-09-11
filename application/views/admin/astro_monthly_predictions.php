<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>	
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href="<?php echo base_url(); ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

<script> 
	var base_url = '<?php echo base_url(); ?>';	
</script>
 <!-- tool tip begins-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.style-my-tooltips.js"></script>
<script>
  jQuery.noConflict();
  (function($){
   $(document).ready(function(){
    $("[title]").style_my_tooltips({ 
     tip_follows_cursor:false, //boolean
     tip_delay_time:700, //milliseconds
     tip_fade_speed:500 //milliseconds
    });
    //dynamically added elements demo function
    $("a[rel='add new element']").click(function(e){
     e.preventDefault();
     $(this).attr("title","Add another element").parent().after("<p title='New paragraph title'>This is a new paragraph! Hover to see the title.</p>");
    });
   });
  })(jQuery);
  

 </script>
 <!-- tool tip ends -->    
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>	
<script type="text/javascript" src="<?php echo base_url(); ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datetimepicker.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!--calendar begind-->
<link href="<?php echo base_url(); ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<?php /*?><script src="<?php echo base_url(); ?>js/jquery.js"></script><?php */?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.js"></script>
<!--calendar ends-->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/w2ui-fields-1.0.min.js"></script>
<link href="<?php echo base_url(); ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/astro_monthly_predictions.js"></script>

<div class="Container">
<div class="BodyWhiteBG">
<div class="BodyHeadBg Overflow clear">
<div class="FloatLeft  BreadCrumbsWrapper PollResult">
<div class="breadcrumbs"><a href="<?php echo base_url().folder_name; ?>">Dashboard</a> > <a href="javscript:;"><?php echo $title; ?></a></div>
<h2><?php echo $title; ?></h2>
</div> 

<?php if(($this->session->flashdata("success"))) { ?>
 <div id="flash_msg_id" class="FloatLeft SessionSuccess"><?php echo $this->session->flashdata("success");?></div>
<?php } ?> 
<?php if(($this->session->flashdata("error"))) { ?>
 <div id="flash_msg_id" class="FloatLeft SessionError"><?php echo $this->session->flashdata("error");?></div>
<?php } ?>
 
 <div id="activatedmessage" class="FloatLeft SessionSuccess" style="display:none">Activated Successfully.</div>
 <div id="deactivatedmessage" class="FloatLeft SessionSuccess" style="display:none">Deactivated Successfully.</div>
 <div id="deletedmessage" class="FloatLeft SessionSuccess" style="display:none ">Deleted Successfully.</div>
<?php if(($language=='english')){ 
$query = '?lang='.$language; 
$menu_name = 'Monthly Predictions';
}else{
$query = '';
$menu_name = 'Monthly Predictions Tamil';
} ?>
<?php $data['Menu_id'] = get_menu_details_by_menu_name($menu_name); ?>
<?php if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD".$data['Menu_id']) == '1') { ?>

 <p class="FloatRight SaveBackTop"><a href="<?php echo base_url().folder_name;?>/astro_monthly_prediction/add_monthly_predictions<?php echo $query;?>" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Add New</a></p>
<?php } ?>
</div>

<div class="Overflow DropDownWrapper">

<div class="FloatLeft TableColumn astrology-search">  

<!--<div class="FloatLeft w2ui-field">
<p class="CalendarWrapper" >
	<input type="text" placeholder="Date From" class="SearchInput" id="date_timepicker_start" value="">
</p>
</div>
<div class="FloatLeft w2ui-field">
<p class="CalendarWrapper" >
	<input type="text" placeholder="Date To" class="SearchInput" id="date_timepicker_end" value="">
</p>
</div>-->
<div class="FloatLeft w2ui-field">
  <select id="rasi_id" class="controls">
        <option value="">Select Zodiac Name</option>
 <?php
	  foreach($raasi_lists as $key => $raasi_list)
	  {
		
	  ?>
		<option value="<?php echo $raasi_list['Section_id'];?>" <?php if(isset($fetch_values['section_id']) && $fetch_values['section_id'] != "") { if($raasi_list['Section_id'] == $fetch_values['section_id']) { echo 'selected'; } } ?> ><?php echo $raasi_list['Sectionname'];?></option>
		<?php
	  }
	  ?>
    </select>
</div>

<!--<i class="fa fa-search FloatLeft" id="article_search_id"></i>-->
<button class="btn btn-primary" type="button" id="article_search_id">Search</button>
<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>

<p id="srch_error" style="clear: left; color:#F00"></p>
</div>


<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Date</th>
						<th>Zodiac Name</th>
                        <th>Submitted By</th>
                        <th>Created On</th>
                          <?php if((defined("USERACCESS_DELETE".$data['Menu_id']) && constant("USERACCESS_DELETE".$data['Menu_id']) == '1') || (defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1' )) { ?>
                        <th>Action</th>
                        <?php } ?>
					</tr>
				</thead>
	</table>
    
</div>                            
</div>                            
</div>     
<script>
function get_astro_predictions() {
		
	$("#example_length").hide();
	
	var rasi_id	= $("#rasi_id").val();
	/*var check_in	= $("#date_timepicker_start").val();
	var check_out   = $("#date_timepicker_end").val();*/
	var check_in  ='';
	var check_out ='';
	
	
	
	
	var Status		= '';
	
	$('#example').dataTable({
		oLanguage: {
			sProcessing: "<img src='<?php echo base_url(); ?>images/admin/loadingroundimage.gif' style='width:40px; height:40px;'>"
		},
		"processing": true,
		"autoWidth": false,
		"bServerSide": true,
		"bDestroy": true,
		"searching": false,
		"iDisplayLength": 10,
		"aaSorting": [[2,'desc']], // Default sorting for table
		"fnDrawCallback":function(oSettings){
		 $("html, body").animate({ scrollTop: 0 }, "slow");
			if($('span a.paginate_button').length <= 1) {
				$("#example_paginate").hide();
			} else {
				$("#example_paginate").show();
			}
			
			if($(this).find('tbody tr').text()== "No matching records found")
			{
				$(oSettings.nTHead).hide(); 
				$('.dataTables_info').hide();
				$("#example").find('tbody tr').append($('<td class="BackArrow"><a href="<?php echo base_url(folder_name); ?>/astro_monthly_prediction" data-toggle="tooltip" title="Back to list"><i class="fa fa-reply fa-2x"></i></a></td>'));
			}
			else
			{
				$(oSettings.nTHead).show(); 
			}
		
		},
		"aoColumnDefs": [ { "bSortable": false, "aTargets": [-1] } ] ,		
		"ajax": {
			"url": "<?php echo base_url(folder_name); ?>/astro_monthly_prediction/get_astro_monthlypredictions",
			"type" : "POST",
			"data" : {
			"rasi_id" : rasi_id, "from_date" : check_in, "to_date" : check_out, "lang" : "<?php echo $language;?>"}
		}
	});

}

	$("#clear_search").click(function() {
		$("#rasi_id").val('');
		/*$("#date_timepicker_start").val('');
		$("#date_timepicker_end").val('');*/
		get_astro_predictions();
	});

	$("#article_search_id").click(function(){
		/*if($('#date_timepicker_end').val() != '')
		{
			if($('#date_timepicker_start').val() == '')
			{
				$("#srch_error").html("Please select from date first!");
				return false;
			}
			else
			{
				get_astro_predictions();
				$("#srch_error").html("");
			}
		}
		else
		{*/
			get_astro_predictions();
			$("#srch_error").html("");
		/*}*/
	});
</script>
<script>
function get_date(input) {
if(input == '') {
return false;
}	else {
// Split the date, divider is '/'
var parts = input.match(/(\d+)/g);
return parts[2]+'/'+parts[1]+'/'+parts[0];
} 
}

$(document).ready(function()
{
	get_astro_predictions();
});
</script>