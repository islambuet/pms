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
    <div class="row show-grid" style="display: none;" id="consignment_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONSIGNMENT_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="consignment_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div class="row show-grid" style="display: none;" id="container_variety_type_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_VARIETY_TYPE');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="container_variety_type" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($varieties as $variety)
                {?>
                    <option value="<?php echo $variety['id']?>"><?php echo $variety['text'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row show-grid" style="display: none;" id="container_no_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NO');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="container_no" class="form-control">
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
        $("#container_variety_type").val("");
        $("#container_no").val("");
        $('#container_no_container').hide();
        $('#container_variety_type_container').hide();
        $('#consignment_id_container').hide();

        var year=$(this).val();
        if(year>0)
        {
            $('#consignment_id_container').show();
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

        $("#container_variety_type").val("");
        $("#container_no").val("");
        $('#container_no_container').hide();
        $('#container_variety_type_container').hide();

        var consignment_id=$(this).val();
        if(consignment_id>0)
        {
            $('#container_variety_type_container').show();
        }
    });
    $(document).on("change","#container_variety_type",function()
    {
        $("#detail_container").html("");


        $("#container_no").val("");
        $('#container_no_container').hide();

        var consignment_id=$('#consignment_id').val();
        var container_variety_type=$('#container_variety_type').val();
        if(container_variety_type>0)
        {
            $('#container_no_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_container_nos_by_consignments_year/",
                type: 'POST',
                datatype: "JSON",
                data:{consignment_id:consignment_id,container_variety_type:container_variety_type},
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
    $(document).on("change","#container_no",function()
    {
        $("#detail_container").html("");
        var year=$('#year').val();
        var consignment_id=$('#consignment_id').val();
        var container_no=$('#container_no').val();
        var container_variety_type=$('#container_variety_type').val();
        if(container_no>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/edit/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year,consignment_id:consignment_id,container_no:container_no,container_variety_type:container_variety_type},
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
