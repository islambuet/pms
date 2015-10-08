<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url.'/index/search/'.$container['consignment_id']);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $container['id']; ?>" />
    <input type="hidden" id="consignment_id" name="container[consignment_id]" value="<?php echo $container['consignment_id']; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NAME');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="container[container_name]" id="container_name" class="form-control" value="<?php echo $container['container_name'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="container[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $container['remarks'];?>"/>
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $CI->lang->line('STATUS');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="container[status]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="<?php echo $CI->config->item('system_status_active'); ?>"
                        <?php
                        if ($container['status'] == $CI->config->item('system_status_active')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('ACTIVE') ?>
                    </option>
                    <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"
                        <?php
                        if ($container['status'] == $CI->config->item('system_status_inactive')) {
                            echo "selected='selected'";
                        }
                        ?> ><?php echo $CI->lang->line('INACTIVE') ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="widget-header">
        <div class="title">
            <?php echo $CI->lang->line('LABEL_VARIETY_QUANTITY'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="system_add_more_container">
        <div style="" class="row show-grid">
            <div class="col-xs-2">

            </div>
            <div class="col-xs-3">
                <label class="control-label text-center"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?></label>
            </div>
            <div class="col-xs-3">
                <label class="control-label text-center"><?php echo $CI->lang->line('LABEL_QUANTITY');?></label>
            </div>
            <div class="col-xs-2">
            </div>
        </div>
        <?php
        foreach($container_varieties as $i=>$container_variety)
        {
            ?>
            <div style="" class="row show-grid">
                <div class="col-xs-2">

                </div>
                <div class="col-xs-3">
                    <select name="container_varieties[<?php echo $i+1;?>][id]" class="form-control variety" tabindex="-1">
                        <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        <?php
                        foreach($varieties as $variety)
                        {
                            ?>
                            <option value="<?php echo $variety['id']?>" <?php if ($variety['id'] == $container_variety['variety_id']) {echo "selected='selected'";}?>><?php echo $variety['text'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-3">
                    <input type="text" name="container_varieties[<?php echo $i+1;?>][quantity]" class="form-control quantity" value="<?php echo $container_variety['quantity']; ?>"/>
                </div>
                <div class="col-xs-2">
                    <button type="button" class="btn btn-danger system_add_more_delete"><?php echo $CI->lang->line('DELETE'); ?></button>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">
            <button type="button" class="btn btn-warning system_add_more_button" data-current-id="<?php echo sizeof($container_varieties);?>"><?php echo $CI->lang->line('LABEL_ADD_MORE');?></button>
        </div>
        <div class="col-xs-4">

        </div>
    </div>

    <div class="clearfix"></div>
</form>
<div id="system_add_more_content" style="display: none;">
    <div style="" class="row show-grid">
        <div class="col-xs-2">

        </div>
        <div class="col-xs-3">
            <select name="container_varieties[<?php echo sizeof($container_varieties);?>][id]" class="form-control variety" tabindex="-1">
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
        <div class="col-xs-3">
            <input type="text" name="container_varieties[<?php echo sizeof($container_varieties);?>][quantity]" class="form-control quantity" value=""/>
        </div>
        <div class="col-xs-2">
            <button type="button" class="btn btn-danger system_add_more_delete"><?php echo $CI->lang->line('DELETE'); ?></button>
        </div>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(document).on("click", ".system_add_more_button", function(event)
        {
            var current_id=parseInt($(this).attr('data-current-id'));
            current_id=current_id+1;
            $(this).attr('data-current-id',current_id);

            $('#system_add_more_content .variety').attr('name','container_varieties['+current_id+'][id]');
            $('#system_add_more_content .quantity').attr('name','container_varieties['+current_id+'][quantity]');
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
