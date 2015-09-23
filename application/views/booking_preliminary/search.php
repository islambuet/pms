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
                <?php
                $current_year=date("Y",time());
                for($i=$this->config->item("start_year");$i<=($current_year+1);$i++)
                {?>
                    <option value="<?php echo $i;?>" <?php if($i==$current_year){ echo "selected";}?>><?php echo $i;?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row show-grid" id="zone_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ZONE_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="zone_id" class="form-control" tabindex="-1">
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
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TERRITORY_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="territory_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div style="display: none;" class="row show-grid" id="district_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DISTRICT_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="district_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div style="display: none;" class="row show-grid" id="upazila_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UPAZILA_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="upazila_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div style="display: none;" class="row show-grid" id="union_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UNION_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="union_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
            </select>
        </div>
    </div>
    <div style="display: none;" class="row show-grid" id="customer_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="customer_id" class="form-control" tabindex="-1">
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
            $("#zone_id").val("");
            $("#territory_id").val("");
            $("#district_id").val("");
            $("#upazila_id").val("");
            $("#union_id").val("");
            $("#customer_id").val("");
            var year=$(this).val();
            if(year>0)
            {
                $("#customer_id_container").hide();
                $('#union_id_container').hide();
                $('#upazila_id_container').hide();
                $('#district_id_container').hide();
                $('#territory_id_container').hide();
                $('#zone_id_container').show();
            }
            else
            {
                $("#customer_id_container").hide();
                $('#union_id_container').hide();
                $('#upazila_id_container').hide();
                $('#district_id_container').hide();
                $('#territory_id_container').hide();
                $('#zone_id_container').hide();
            }
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

            var customer_id=$(this).val();
            var year=$('#year').val();
            if(customer_id>0)
            {
                $.ajax({
                    url: base_url+"<?php echo $CI->controller_url;?>/index/edit/",
                    type: 'POST',
                    datatype: "JSON",
                    data:{customer_id:customer_id,year:year},
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
        $(document).on("click", ".system_add_more_button", function(event)
        {
            var current_id=parseInt($(this).attr('data-current-id'));
            current_id=current_id+1;
            $(this).attr('data-current-id',current_id);

            $('#system_add_more_content .variety').attr('name','booked_varieties['+current_id+'][id]');
            $('#system_add_more_content .quantity').attr('name','booked_varieties['+current_id+'][quantity]');
            var html=$('#system_add_more_content').html();
            $("#system_add_more_container").append(html);

        });
        // Delete more button
        $(document).on("click", ".system_add_more_delete", function(event)
        {
//            console.log('allah is one');
            $(this).closest('.show-grid').remove();
        });


    });
</script>
