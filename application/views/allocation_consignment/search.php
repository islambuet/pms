<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$action_data=array();
$action_data["action_save"]='#save_form';
$CI->load->view("action_buttons",$action_data);
?>

<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_YEAR');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="year" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                $current_year=date("Y",time());
                for($i=$this->config->item("start_year");$i<=($current_year+1);$i++)
                {?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="row widget" id="detail_container">

</div>
<div class="clearfix"></div>

<script type="text/javascript">

jQuery(document).ready(function()
{
    turn_off_triggers();
    $(document).on("change","#year",function()
    {
        $("#detail_container").html("");
        var year=$(this).val();
        if(year>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/list/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    });
    $(document).on("change","#booking_id",function()
    {
        $("#edit_container").html("");
        var year=$('#year').val();
        var booking_id=$('#booking_id').val();

        if(year>0 && booking_id>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/edit/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year,booking_id:booking_id},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    });
});
</script>
