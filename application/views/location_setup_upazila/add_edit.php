<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $upazila['id']; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ZONE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="zone_id" name="upazila[zone_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($zones as $zone)
                    {?>
                        <option value="<?php echo $zone['id']?>" <?php if($zone['id']==$upazila['zone_id']){ echo "selected";}?>><?php echo $zone['zone_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($upazila['territory_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="territory_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TERRITORY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="territory_id" name="upazila[territory_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($territories as $territory)
                    {?>
                        <option value="<?php echo $territory['id']?>" <?php if($territory['id']==$upazila['territory_id']){ echo "selected";}?>><?php echo $territory['territory_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($upazila['district_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="district_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_DISTRICT_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="district_id" name="upazila[district_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($districts as $district)
                    {?>
                        <option value="<?php echo $district['id']?>" <?php if($district['id']==$upazila['district_id']){ echo "selected";}?>><?php echo $district['district_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_UPAZILA_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="upazila[upazila_name]" id="zone_name" class="form-control validate[required]" value="<?php echo $upazila['upazila_name'];?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="upazila[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $upazila['remarks'];?>"/>
            </div>
        </div>


        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="upazila[ordering]" id="ordering" class="form-control" value="<?php echo $upazila['ordering'] ?>" >
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="upazila[status]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>"
                        <?php
                        if ($upazila['status'] == $CI->config->item('system_status_active')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('ACTIVE') ?>
                    </option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"
                        <?php
                        if ($upazila['status'] == $CI->config->item('system_status_inactive')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('INACTIVE') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(document).on("change","#zone_id",function()
        {
            $("#territory_id").val("");
            $("#district_id").val("");
            var zone_id=$(this).val();
            if(zone_id>0)
            {
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
                $('#district_id_container').hide();
                $('#territory_id_container').hide();
            }
        });
        $(document).on("change","#territory_id",function()
        {
            $("#district_id").val("");
            var territory_id=$(this).val();
            if(territory_id>0)
            {
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
                $('#district_id_container').hide();
            }
        });


    });
</script>
