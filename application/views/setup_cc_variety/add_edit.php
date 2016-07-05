<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $action_data["action_save_new"]='#save_form';
    $action_data["action_clear"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $variety['id']; ?>" />
    <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="crop_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($crops as $crop)
                    {?>
                        <option value="<?php echo $crop['value']?>" <?php if($crop['value']==$variety['crop_id']){ echo "selected";}?>><?php echo $crop['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($variety['classification_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="classification_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="classification_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($classifications as $classification)
                    {?>
                        <option value="<?php echo $classification['value']?>" <?php if($classification['value']==$variety['classification_id']){ echo "selected";}?>><?php echo $classification['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($variety['type_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="type_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($types as $type)
                    {?>
                        <option value="<?php echo $type['value']?>" <?php if($type['value']==$variety['type_id']){ echo "selected";}?>><?php echo $type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($variety['skin_type_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="skin_type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SKIN_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="skin_type_id" name="variety[skin_type_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($skin_types as $skin_type)
                    {?>
                        <option value="<?php echo $skin_type['value']?>" <?php if($skin_type['value']==$variety['skin_type_id']){ echo "selected";}?>><?php echo $skin_type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_VARIETY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="variety[name]" id="name" class="form-control validate[required]" value="<?php echo $variety['name'];?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="variety[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $variety['remarks'];?>"/>
            </div>
        </div>


        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="variety[ordering]" id="ordering" class="form-control" value="<?php echo $variety['ordering'] ?>" >
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="variety[status]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>"
                        <?php
                        if ($variety['status'] == $CI->config->item('system_status_active')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('ACTIVE') ?>
                    </option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"
                        <?php
                        if ($variety['status'] == $CI->config->item('system_status_inactive')) {
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
        $(document).on("change","#crop_id",function()
        {
            $("#skin_type_id").val("");
            $("#type_id").val("");
            $("#classification_id").val("");
            var crop_id=$(this).val();
            if(crop_id>0)
            {
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
                $('#skin_type_id_container').hide();
                $('#type_id_container').hide();
                $('#classification_id_container').hide();
            }
        });
        $(document).on("change","#classification_id",function()
        {
            $("#skin_type_id").val("");
            $("#type_id").val("");
            var classification_id=$(this).val();
            if(classification_id>0)
            {
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
                $('#skin_type_id_container').hide();
                $('#type_id_container').hide();
            }
        });
        $(document).on("change","#type_id",function()
        {
            $("#skin_type_id").val("");
            var type_id=$(this).val();
            if(type_id>0)
            {
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
                $('#skin_type_id_container').hide();
            }
        });


    });
</script>
