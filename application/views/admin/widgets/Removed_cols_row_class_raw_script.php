<?php
$widget_bg_color 		= $content['widget_bg_color'];
$widget_instance_id	 	= $content['widget_values']['data-widgetinstanceid'];
$view_mode              = $content['mode'];
$widget_instance_details= $this->widget_model->getWidgetInstance('', '','', '', $widget_instance_id, $content['mode']);
$widget_position = $content['widget_position'];	
?>

<div class="row">
  <div>
    <div class="ad_script_<?php echo $widget_instance_id;?>" id="ad_script_<?php echo $widget_instance_id;?>" <?php //echo $widget_bg_color;  ?> style="<?php echo ($widget_position=="header")? "": "";?>">
     <?php echo $widget_instance_details['AdvertisementScript']; ?>
    </div>
  </div>
</div>