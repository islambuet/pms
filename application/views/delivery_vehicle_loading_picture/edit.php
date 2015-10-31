<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$images=array();
$remarks='';
if(isset($vehicle_info['images']))
{
    $images=json_decode($vehicle_info['images'],true);
}
if(isset($vehicle_info['remarks']))
{
    $remarks=$vehicle_info['remarks'];

}
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            Vehicle Loading Picture
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="container_id" value="<?php echo $container_id; ?>" />
    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>" />
    <input type="hidden" name="vehicle_no" value="<?php echo $vehicle_no; ?>" />
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_VEHICLE_NUMBER');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="vehicle[vehicle_number]" class="form-control" value="<?php echo isset($vehicle_info['vehicle_number'])?$vehicle_info['vehicle_number']:'' ?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DRIVER_NAME');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="vehicle[driver_name]" class="form-control" value="<?php echo isset($vehicle_info['driver_name'])?$vehicle_info['driver_name']:'' ?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTACT_NO');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="vehicle[contact_no]" class="form-control" value="<?php echo isset($vehicle_info['contact_no'])?$vehicle_info['contact_no']:'' ?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_INVOICE_NUMBER');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="vehicle[invoice_number]" class="form-control" value="<?php echo isset($vehicle_info['invoice_number'])?$vehicle_info['invoice_number']:'' ?>"/>
        </div>
    </div>
    <?php
        foreach($pictures as $picture)
        {
            $image='no_image.jpg';
            if(isset($images[$picture['id']]))
            {
                $image=$images[$picture['id']];
            }
            ?>
            <div class="row show-grid">
                <div class="col-xs-4">
                    <label class="control-label pull-right"><?php echo $picture['picture_name']; ?></label>
                </div>
                <div class="col-xs-4">
                    <input type="file" class="browse_button" data-preview-container="#image_<?php echo $picture['id']; ?>" name="image_<?php echo $picture['id']; ?>">
                    <input type="hidden" name="previous_image_<?php echo $picture['id']; ?>" value="<?php echo $image; ?>">
                </div>
                <div class="col-xs-4" id="image_<?php echo $picture['id'];?>">
                    <img style="max-width: 250px;" src="<?php echo base_url().'images/delivery/'.$image;?>">
                </div>
            </div>
            <?php
        }
    ?>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="vehicle[remarks]" id="remarks" class="form-control" value="<?php echo $remarks; ?>"/>
        </div>
    </div>

</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        //$(".datepicker").datepicker({dateFormat : display_date_format});
        $(":file").filestyle({input: false,buttonText: "<?php echo $CI->lang->line('UPLOAD');?>", buttonName: "btn-danger"});
    });
</script>
