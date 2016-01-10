<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($bookings);
//echo '</PRE>';
//return;
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="year" value="<?php echo $year; ?>" />
    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>" />
    <input type="hidden" name="container_variety_type" value="<?php echo $container_variety_type; ?>" />
    <input type="hidden" name="container_no" value="<?php echo $container_no; ?>" />
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="delivery_time" class="form-control datepicker" value="<?php echo System_helper::display_date($delivery_time);?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select name="container_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($containers as $container)
                {
                    ?>
                    <option value="<?php echo $container['id'];?>" <?php if($container['id']==$container_id){echo 'selected';} ?>><?php echo $container['container_name'];?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-xs-4">
            <?php
            if($container_id>0)
            {
                ?>
                <label class="alert-danger">You already assigned this container.Now you are changing it.</label>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="remarks" class="form-control" value="<?php echo $remarks;?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <th>Customer</th>
                    <th>Quantity</th>
                </thead>
                <tbody>
                <?php
                if(sizeof($bookings)>0)
                {
                    foreach($bookings as $booking)
                    {
                        ?>
                        <tr>
                            <td><?php echo $booking['customer_name']; ?></td>
                            <td><?php echo $booking['quantity']; ?></td>
                        </tr>
                        <?php
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="20" class="text-center alert-danger">
                            <?php echo $this->lang->line("NO_DATA_FOUND"); ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".datepicker").datepicker({dateFormat : display_date_format});
    });
</script>
