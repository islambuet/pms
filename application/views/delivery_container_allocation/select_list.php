<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
?>
<div class="row show-grid">
    <div class="col-xs-12 text-center">
        <input type="checkbox" id="select_all" style="margin-right: 5px;">SELECT ALL
    </div>
</div>
<form class="form_valid" id="select_form" action="<?php echo site_url($CI->controller_url.'/index/edit');?>" method="post">
    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>">
    <input type="hidden" name="container_no" value="<?php echo $container_no; ?>">
    <input type="hidden" name="container_variety_type" value="<?php echo $container_variety_type; ?>">
    <div class="row show-grid">

        <table class="table table-hover table-bordered" style="font-size: 13px;">
            <tr>
                <?php
                $i=0;
                foreach($bookings as $booking)
                {
                ?>
                <td><input type="checkbox" class="select_bookings" name="bookings[]" <?php if(in_array($booking['booking_id'],$allocated_booking_ids)){echo 'checked';} ?> value="<?php echo $booking['booking_id'];?>" style="margin-right: 5px;"><?php echo $booking['customer_name'];?></td>
                <?php
                $i++;
                if($i==6)
                {
                $i=0;
                ?>
            </tr><tr>
                <?php
                }
                }
                ?>
            </tr>

        </table>
    </div>
    <div class="row show-grid">
        <div class="col-xs-3">

        </div>
        <div class="col-xs-6">
            <input type="button" id="load_allocation" class="form-control btn-primary" value="Load Allocation" />
        </div>
        <div class="col-xs-3">

        </div>
    </div>

</form>