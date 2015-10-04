<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $skin_type['id']; ?>" />
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
                        <option value="<?php echo $crop['id']?>" <?php if($crop['id']==$skin_type['crop_id']){ echo "selected";}?>><?php echo $crop['crop_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($skin_type['classification_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="classification_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="classification_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($classifications as $classification)
                    {?>
                        <option value="<?php echo $classification['id']?>" <?php if($classification['id']==$skin_type['classification_id']){ echo "selected";}?>><?php echo $classification['classification_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($skin_type['type_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="type_id" name="skin_type[type_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($types as $type)
                    {?>
                        <option value="<?php echo $type['id']?>" <?php if($type['id']==$skin_type['type_id']){ echo "selected";}?>><?php echo $type['type_name'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_SKIN_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="skin_type[skin_type_name]" id="crop_name" class="form-control validate[required]" value="<?php echo $skin_type['skin_type_name'];?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="skin_type[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $skin_type['remarks'];?>"/>
            </div>
        </div>


        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="skin_type[ordering]" id="ordering" class="form-control" value="<?php echo $skin_type['ordering'] ?>" >
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="skin_type[status]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>"
                        <?php
                        if ($skin_type['status'] == $CI->config->item('system_status_active')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('ACTIVE') ?>
                    </option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"
                        <?php
                        if ($skin_type['status'] == $CI->config->item('system_status_inactive')) {
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
            $("#type_id").val("");
            $("#classification_id").val("");
            var crop_id=$(this).val();
            if(crop_id>0)
            {
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
                $('#type_id_container').hide();
                $('#classification_id_container').hide();
            }
        });
        $(document).on("change","#classification_id",function()
        {
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
                $('#type_id_container').hide();
            }
        });


    });
</script>
