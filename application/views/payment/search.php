<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
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
                    <option value="<?php echo $i;?>" <?php if($i==$booking['year']){ echo "selected";}?>><?php echo $i;?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="" class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ZONE_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="zone_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($zones as $zone)
                {?>
                    <option value="<?php echo $zone['id']?>" <?php if($zone['id']==$booking['zone_id']){ echo "selected";}?>><?php echo $zone['zone_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="<?php if(!($booking['territory_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="territory_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TERRITORY_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="territory_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($territories as $territory)
                {?>
                    <option value="<?php echo $territory['id']?>" <?php if($territory['id']==$booking['territory_id']){ echo "selected";}?>><?php echo $territory['territory_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="<?php if(!($booking['district_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="district_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DISTRICT_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="district_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($districts as $district)
                {?>
                    <option value="<?php echo $district['id']?>" <?php if($district['id']==$booking['district_id']){ echo "selected";}?>><?php echo $district['district_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="<?php if(!($booking['upazila_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="upazila_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UPAZILA_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="upazila_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($upazilas as $upazila)
                {?>
                    <option value="<?php echo $upazila['id']?>" <?php if($upazila['id']==$booking['upazila_id']){ echo "selected";}?>><?php echo $upazila['upazila_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="<?php if(!($booking['union_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="union_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_UNION_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="union_id" name="customer[union_id]" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($unions as $union)
                {?>
                    <option value="<?php echo $union['id']?>" <?php if($union['id']==$booking['union_id']){ echo "selected";}?>><?php echo $union['union_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div style="<?php if(!($booking['customer_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="customer_id_container">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="customer_id" class="form-control" tabindex="-1">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($customers as $customer)
                {?>
                    <option value="<?php echo $customer['id']?>" <?php if($customer['id']==$booking['customer_id']){ echo "selected";}?>><?php echo $customer['customer_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>

</div>
<div class="row widget" id="detail_container">
    <?php
    if($booking['id'])
    {
        $payments=Query_helper::get_info($CI->config->item('table_payments'),'*',array('booking_id ='.$booking['id']));
        if((sizeof($payments))>0)
        {
            $data['title']="Payment History";
            $data['payments']=$payments;
            $data['booking_id']=$booking['id'];
            $CI->load->view('payment/list',$data);
        }
    }
    ?>
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
                url: base_url+"<?php echo $CI->controller_url;?>/index/list/",
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

});
</script>
