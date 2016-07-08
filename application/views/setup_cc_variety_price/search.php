<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $action_data["action_save_new"]='#save_form';
    $action_data["action_clear"]='#save_form';
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
                    for($i=$this->config->item("system_pms_start_year");$i<=max($current_year+1,$price['year']);$i++)
                    {?>
                        <option value="<?php echo $i;?>" <?php if($i==$price['year']){ echo "selected";}?>><?php echo $i;?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
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
                        <option value="<?php echo $crop['value']?>" <?php if($crop['value']==$price['crop_id']){ echo "selected";}?>><?php echo $crop['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($price['classification_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="classification_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="classification_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($classifications as $classification)
                    {?>
                        <option value="<?php echo $classification['value']?>" <?php if($classification['value']==$price['classification_id']){ echo "selected";}?>><?php echo $classification['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($price['type_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="type_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($types as $type)
                    {?>
                        <option value="<?php echo $type['value']?>" <?php if($type['value']==$price['type_id']){ echo "selected";}?>><?php echo $type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($price['skin_type_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="skin_type_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_SKIN_TYPE_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="skin_type_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($skin_types as $skin_type)
                    {?>
                        <option value="<?php echo $skin_type['value']?>" <?php if($skin_type['value']==$price['skin_type_id']){ echo "selected";}?>><?php echo $skin_type['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div style="<?php if(!($price['variety_id']>0)){echo 'display:none';} ?>" class="row show-grid" id="variety_id_container">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="variety_id" class="form-control" tabindex="-1">
                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                    <?php
                    foreach($varieties as $variety)
                    {?>
                        <option value="<?php echo $variety['value']?>" <?php if($variety['value']==$price['variety_id']){ echo "selected";}?>><?php echo $variety['text'];?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row widget" id="detail_container">

    </div>
    <div class="clearfix"></div>

<script type="text/javascript">
    function load_price()
    {
        var variety_id=$("#variety_id").val();
        var year=$("#year").val();
        if(variety_id>0 && year>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url; ?>/index/load_price",
                type: 'POST',
                dataType: "JSON",
                data:{variety_id:variety_id,year:year},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    }
    jQuery(document).ready(function()
    {
        turn_off_triggers();
        load_price();
        $(document).on("change","#year",function()
        {
            $("#detail_container").html("");
            $("#variety_id").val("");
            $("#skin_type_id").val("");
            $("#type_id").val("");
            $("#classification_id").val("");
            $("#crop_id").val("");
            $('#variety_id_container').hide();
            $('#skin_type_id_container').hide();
            $('#type_id_container').hide();
            $('#classification_id_container').hide();
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
            load_price();
        });

    });
</script>
