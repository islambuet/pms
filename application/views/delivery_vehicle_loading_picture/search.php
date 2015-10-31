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
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONSIGNMENT_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="consignment_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div class="row show-grid" style="display: none;" id="container_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="container_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div class="row show-grid" style="display: none" id="booking_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="booking_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div class="row show-grid" style="display: none" id="vehicle_no_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_VEHICLE_NO');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="vehicle_no" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
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
        $("#consignment_id").val("");
        $("#container_id").val("");
        $("#booking_id").val("");
        $("#vehicle_no").val("");
        $('#container_id_container').hide();
        $('#booking_id_container').hide();
        $('#vehicle_no_container').hide();
        var year=$(this).val();
        if(year>0)
        {
            $.ajax({
                url: base_url+"common_controller/get_dropdown_consignments_by_year/",
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
    $(document).on("change","#consignment_id",function()
    {
        $("#detail_container").html("");
        $("#container_id").val("");
        $("#booking_id").val("");
        $("#vehicle_no").val("");
        $('#container_id_container').hide();
        $('#booking_id_container').hide();
        $('#vehicle_no_container').hide();

        var consignment_id=$(this).val();
        if(consignment_id>0)
        {
            $('#container_id_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_containers_by_consignmentid/",
                type: 'POST',
                datatype: "JSON",
                data:{consignment_id:consignment_id},
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
            $('#container_id_container').hide();
        }
    });
    $(document).on("change","#container_id",function()
    {

        $("#detail_container").html("");
        $("#booking_id").val("");
        $("#vehicle_no").val("");
        $('#booking_id_container').hide();
        $('#vehicle_no_container').hide();

        var container_id=$(this).val();
        if(container_id>0)
        {
            $('#booking_id_container').show();
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/get_bookings/",
                type: 'POST',
                datatype: "JSON",
                data:{container_id:container_id},
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

        }
    });
    $(document).on("change","#booking_id",function()
    {

        $("#detail_container").html("");
        $("#vehicle_no").val("");
        $("#vehicle_no_container").hide();

        var container_id=$('#container_id').val();
        var booking_id=$('#booking_id').val();
        if(container_id>0)
        {
            $('#vehicle_no_container').show();
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/get_driver_nos/",
                type: 'POST',
                datatype: "JSON",
                data:{container_id:container_id,booking_id:booking_id},
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

        }
    });
    $(document).on("change","#vehicle_no",function()
    {

        $("#detail_container").html("");


        var container_id=$('#container_id').val();
        var booking_id=$('#booking_id').val();
        var vehicle_no=$('#vehicle_no').val();
        if(container_id>0&&booking_id>0&& vehicle_no>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/edit/",
                type: 'POST',
                datatype: "JSON",
                data:{container_id:container_id,booking_id:booking_id,vehicle_no:vehicle_no},
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

        }
    });
});
</script>
