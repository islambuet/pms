<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Logo</label>
            </div>
            <div class="col-xs-4">
                <input type="file" class="browse_button" data-preview-container="#image_logo" name="image_logo">
                <input type="hidden" name="previous_image_logo" value="<?php echo $setup_invoice['logo']; ?>">
            </div>
            <div class="col-xs-4" id="image_logo">
                <img style="max-width: 250px;" src="<?php echo base_url().'images/invoice_logo/'.$setup_invoice['logo'];?>">
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Line 1</label>
            </div>
            <div class="col-xs-8">
                <input type="text" name="setup_invoice[line1]" class="form-control" value="<?php echo $setup_invoice['line1'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Line 2</label>
            </div>
            <div class="col-xs-8">
                <input type="text" name="setup_invoice[line2]" class="form-control" value="<?php echo $setup_invoice['line2'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right">Line 3</label>
            </div>
            <div class="col-xs-8">
                <input type="text" name="setup_invoice[line3]" class="form-control" value="<?php echo $setup_invoice['line3'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_SELECT_COLOR');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <div id="colorPickerbackground"></div>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BACKGROUND_COLOR');?></label>
            </div>
            <div class="col-xs-4">
                <input type="text" name="setup_invoice[background_color]" id="background_color" class="form-control" value="<?php echo $setup_invoice['background_color'];?>"/>
            </div>
            <div class="col-xs-4">
                <div class="control-label" style="background-color: <?php echo $setup_invoice['background_color'];?>;width: 100px; " ><?php echo $setup_invoice['background_color'];?></div>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_SELECT_COLOR');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <div id="colorPickerborder"></div>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BORDER_COLOR');?></label>
            </div>
            <div class="col-xs-4">
                <input type="text" name="setup_invoice[border_color]" id="border_color" class="form-control" value="<?php echo $setup_invoice['border_color'];?>"/>
            </div>
            <div class="col-xs-4">
                <div class="control-label" style="background-color: <?php echo $setup_invoice['border_color'];?>;width: 100px; " ><?php echo $setup_invoice['border_color'];?></div>
            </div>
        </div>


    </div>

    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(":file").filestyle({input: false,buttonText: "<?php echo $CI->lang->line('UPLOAD');?>", buttonName: "btn-danger"});
        $("#colorPickerbackground").jqxColorPicker({
            width: 300,
            height: 300
        });
        $("#colorPickerbackground").on('colorchange', function (event) {
            $("#background_color").val("#" + event.args.color.hex);
            console.log('clicked');
        });
        $("#colorPickerborder").jqxColorPicker({
            width: 300,
            height: 300
        });
        $("#colorPickerborder").on('colorchange', function (event) {
            $("#border_color").val("#" + event.args.color.hex);
            console.log('clicked');
        });

    });
</script>
