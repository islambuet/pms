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
    <form class="form_valid" id="report_form" action="<?php echo site_url($CI->controller_url.'/index/report');?>" method="post">
        <div class="row show-grid">
            <div class="col-xs-6">
                <div class="row show-grid">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_YEAR');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="year" name="year" class="form-control">
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
                <div style="" class="row show-grid">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="crop_id" name="crop_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                            <?php
                            foreach($crops as $crop)
                            {?>
                                <option value="<?php echo $crop['id']?>"><?php echo $crop['crop_name'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="classification_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="classification_id" name="classification_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="type_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="type_id" name="type_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="skin_type_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SKIN_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="skin_type_id" name="skin_type_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="variety_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_VARIETY_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="variety_id" name="variety_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="row show-grid">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BOOKING_TYPE');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="booking_type" name="booking_type" class="form-control">
                            <option value="<?php echo $CI->config->item('booking_status_preliminary');?>"><?php echo $CI->config->item('booking_status_preliminary');?></option>
                            <option value="<?php echo $CI->config->item('booking_status_permanent');?>"><?php echo $CI->config->item('booking_status_permanent');?></option>
                        </select>
                    </div>
                </div>

                <div class="row show-grid" id="zone_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ZONE_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select name="zone_id" id="zone_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                            <?php
                            foreach($zones as $zone)
                            {?>
                                <option value="<?php echo $zone['id']?>"><?php echo $zone['zone_name'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="territory_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TERRITORY_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="territory_id" name="territory_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="district_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DISTRICT_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="district_id" name="district_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="upazila_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UPAZILA_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="upazila_id" name="upazila_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="union_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UNION_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="union_id" name="union_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
                <div style="display: none;" class="row show-grid" id="customer_id_container">
                    <div class="col-xs-6">
                        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
                    </div>
                    <div class="col-xs-6">
                        <select id="customer_id" name="customer_id" class="form-control" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4"></div>
            <div class="col-xs-4"><input type="button" id="load_report" class="form-control btn-primary" value="<?php echo $this->lang->line('LABEL_VIEW_REPORT');?>" /></div>
            <div class="col-xs-4"></div>
        </div>
    </form>
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
    });
    $(document).on("change","#booking_type",function()
    {
        $("#detail_container").html("");
    });
    $(document).on("change","#zone_id",function()
    {
        $("#detail_container").html("");
        $("#territory_id").val("");
        $("#district_id").val("");
        $("#upazila_id").val("");
        $("#union_id").val("");
        $("#customer_id").val("");
        var zone_id=$(this).val();
        if(zone_id>0)
        {
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').hide();
            $('#district_id_container').hide();
            $('#territory_id_container').show();

            $.ajax({
                url: base_url+"common_controller/get_dropdown_territories_by_zoneid/",
                type: 'POST',
                datatype: "JSON",
                data:{zone_id:zone_id},
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
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').hide();
            $('#district_id_container').hide();
            $('#territory_id_container').hide();

        }
    });
    $(document).on("change","#territory_id",function()
    {
        $("#detail_container").html("");
        $("#district_id").val("");
        $("#upazila_id").val("");
        $("#union_id").val("");
        $("#customer_id").val("");
        var territory_id=$(this).val();
        if(territory_id>0)
        {
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').hide();
            $('#district_id_container').show();

            $.ajax({
                url: base_url+"common_controller/get_dropdown_districts_by_territoryid/",
                type: 'POST',
                datatype: "JSON",
                data:{territory_id:territory_id},
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
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').hide();
            $('#district_id_container').hide();
        }
    });
    $(document).on("change","#district_id",function()
    {
        $("#detail_container").html("");
        $("#upazila_id").val("");
        $("#union_id").val("");
        $("#customer_id").val("");
        var district_id=$(this).val();
        if(district_id>0)
        {
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').show();

            $.ajax({
                url: base_url+"common_controller/get_dropdown_upazilas_by_districtid/",
                type: 'POST',
                datatype: "JSON",
                data:{district_id:district_id},
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
            $("#customer_id_container").hide();
            $('#union_id_container').hide();
            $('#upazila_id_container').hide();

        }
    });
    $(document).on("change","#upazila_id",function()
    {

        $("#detail_container").html("");
        $("#union_id").val("");
        $("#customer_id").val("");
        var upazila_id=$(this).val();
        if(upazila_id>0)
        {
            $("#customer_id_container").hide();
            $('#union_id_container').show();


            $.ajax({
                url: base_url+"common_controller/get_dropdown_unions_by_upazilaid/",
                type: 'POST',
                datatype: "JSON",
                data:{upazila_id:upazila_id},
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
            $("#customer_id_container").hide();
            $('#union_id_container').hide();

        }
    });
    $(document).on("change","#union_id",function()
    {

        $("#detail_container").html("");
        $("#customer_id").val("");
        var union_id=$(this).val();
        if(union_id>0)
        {
            $("#customer_id_container").show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_customers_by_unionid/",
                type: 'POST',
                datatype: "JSON",
                data:{union_id:union_id},
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
            $("#customer_id_container").hide();
        }
    });
    $(document).on("change","#customer_id",function()
    {
        $("#detail_container").html("");
    });
    $(document).on("change","#crop_id",function()
    {
        $("#detail_container").html("");
        $("#variety_id").val("");
        $("#skin_type_id").val("");
        $("#type_id").val("");
        $("#classification_id").val("");
        var crop_id=$(this).val();
        if(crop_id>0)
        {
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
            $('#type_id_container').hide();
            $('#classification_id_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_classifications_by_cropid/",
                type: 'POST',
                dataType: "JSON",
                data:{crop_id:crop_id},
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
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
            $('#type_id_container').hide();
            $('#classification_id_container').hide();
        }
    });
    $(document).on("change","#classification_id",function()
    {
        $("#detail_container").html("");
        $("#variety_id").val("");
        $("#skin_type_id").val("");
        $("#type_id").val("");
        var classification_id=$(this).val();
        if(classification_id>0)
        {
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
            $('#type_id_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_types_by_classificationid/",
                type: 'POST',
                dataType: "JSON",
                data:{classification_id:classification_id},
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
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
            $('#type_id_container').hide();
        }
    });
    $(document).on("change","#type_id",function()
    {
        $("#detail_container").html("");
        $("#variety_id").val("");
        $("#skin_type_id").val("");
        var type_id=$(this).val();
        if(type_id>0)
        {
            $('#variety_id_container').hide();
            $('#skin_type_id_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_skintypes_by_typeid/",
                type: 'POST',
                dataType: "JSON",
                data:{type_id:type_id},
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
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
        }
    });
    $(document).on("change","#skin_type_id",function()
    {
        $("#detail_container").html("");
        $("#variety_id").val("");
        var skin_type_id=$(this).val();
        if(skin_type_id>0)
        {
            $('#variety_id_container').show();
            $.ajax({
                url: base_url+"common_controller/get_dropdown_varieties_by_skintypeid/",
                type: 'POST',
                dataType: "JSON",
                data:{skin_type_id:skin_type_id},
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
            $('#variety_id_container').hide();
        }
    });
    $(document).on("change","#variety_id",function()
    {
        $("#detail_container").html("");
    });
    $(document).on("click", "#load_report", function(event)
    {
        $("#detail_container").html("");
        $("#report_form").submit();

    });
});
</script>
