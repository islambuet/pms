<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$images=array();
$remarks='';
if(isset($container_info['images']))
{
    $images=json_decode($container_info['images'],true);
}
if(isset($container_info['remarks']))
{
    $remarks=$container_info['remarks'];

}
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            Opening Pictures
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="container_id" value="<?php echo $container_id; ?>" />
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
            <input type="text" name="remarks" id="remarks" class="form-control" value="<?php echo $remarks; ?>"/>
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
