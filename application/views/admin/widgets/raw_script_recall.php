<?php
$widget_instance_id	 	= $content['widget_values']['data-widgetinstanceid'];

?>
<div class="raw_recall_<?php print $widget_instance_id?>">
<script>
var widget_id="<?php print $widget_instance_id ?>";
setTimeout(function() {
	var zmt_mtag;
	function zd_get_placements(){
		zmt_mtag = zmt_get_tag(791,"277610"); 
		p277610_1 = zmt_mtag.zmt_get_placement("zt_277610_1", "277610", "1" , "660" , "9" , "10" ,"300", "250");
		p277610_2 = zmt_mtag.zmt_get_placement("zt_277610_2", "277610", "2" , "678" , "9" , "10" ,"300", "250"); 
		p277610_3 = zmt_mtag.zmt_get_placement("zt_277610_3", "277610", "3" , "610" , "9" , "10" ,"300", "250"); 
		p277610_4 = zmt_mtag.zmt_get_placement("zt_277610_4", "277610", "4" , "680" , "9" , "10" ,"300", "250"); 
		zmt_mtag.zmt_set_async(); zmt_mtag.zmt_load(zmt_mtag);  
	}
	var script_element = document.createElement("script");
    script_element.type = "text/javascript";
    script_element.src = "http://xp1.zedo.com/client/xp1/fmos.js";
    script_element.async = true;
    $(".raw_recall_"+widget_id).append(script_element);
	//alert('method triggered');
	console.log('method triggered');
	},10000);

</script>
</div>

