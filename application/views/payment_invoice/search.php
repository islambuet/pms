<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$action_data=array();
$CI->load->view("action_buttons",$action_data);
?>

<div class="row widget hidden-print">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <input type="text" id="invoice_date" class="form-control datepicker" value="<?php echo System_helper::display_date(time());?>"/>
        </div>
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
    <div class="row show-grid" id="booking_id_container" style="display: none;">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="booking_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
</div>
<div class="row widget" id="detail_container" style="border: none;">

</div>
<div class="clearfix"></div>

<script type="text/javascript">

jQuery(document).ready(function()
{
    turn_off_triggers();
    $(document).on("change","#year",function()
    {
        $("#detail_container").html("");
        $("#booking_id").val("");
        var year=$(this).val();
        if(year>0)
        {
            $("#booking_id_container").show();
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/booking_list/",
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
        else
        {
            $("#booking_id_container").hide();
        }
    });
    $(document).on("change","#booking_id",function()
    {
        $("#edit_container").html("");
        var year=$('#year').val();
        var booking_id=$('#booking_id').val();
        var invoice_date=$('#invoice_date').val();

        if(year>0 && booking_id>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/invoice/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year,booking_id:booking_id,invoice_date:invoice_date},
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
    $(".datepicker").datepicker({dateFormat : display_date_format});
});
</script>
