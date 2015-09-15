<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
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
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CROP_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="crop_id" name="variety[crop_id]" class="form-control" tabindex="-1">
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
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="classification_id" name="variety[classification_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                </select>
            </div>
        </div>
        <div style="display: none;" class="row show-grid" id="type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="type_id" name="variety[type_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                </select>
            </div>
        </div>
        <div style="display: none;" class="row show-grid" id="skin_type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SKIN_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="skin_type_id" name="variety[skin_type_id]" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                </select>
            </div>
        </div>
        <div style="display: none;" class="row show-grid" id="variety_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_VARIETY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="variety_id" name="variety[variety_id]" class="form-control" tabindex="-1">
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
            var variety_id=$(this).val();
            if(variety_id>0)
            {

                $.ajax({
                    url: base_url+"<?php echo $CI->controller_url; ?>/index/edit",
                    type: 'POST',
                    dataType: "JSON",
                    data:{variety_id:variety_id},
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
