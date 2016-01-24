<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($other_allocated_varieties);
//echo '</PRE>';
//return;
//echo '<PRE>';
//print_r(($booking_info));
//echo '</PRE>';
//echo '<PRE>';
//print_r($allocated_varieties);
//echo '</PRE>';
//return;
//echo '<PRE>';
//print_r($bookings);
//echo '</PRE>';
//return;
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">

    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>">
    <input type="hidden" name="year" value="<?php echo $year; ?>">
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_NO_OF_VEHICLES');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="no_of_vehicles" class="form-control" value="<?php echo $no_of_vehicles;?>"/>
        </div>
    </div>

</form>

