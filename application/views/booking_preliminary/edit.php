<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
?>


    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $booking['id']; ?>" />
    <input type="hidden" name="booking[customer_id]" value="<?php echo $booking['customer_id']; ?>" />
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
            if(sizeof($booked_varieties)>0)
            {

            }
            else
            {
                ?>
                    <div style="" class="row show-grid">
                        <div class="col-xs-2">

                        </div>
                        <div class="col-xs-3">
                            <select name="booked_varieties[0][id]" class="form-control variety" tabindex="-1">
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
                            <input type="text" name="booked_varieties[0][quantity]" class="form-control quantity" value=""/>
                        </div>
                        <div class="col-xs-2">
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
            <button type="button" class="btn btn-warning system_add_more_button" data-current-id="<?php echo sizeof($booked_varieties);?>"><?php echo $CI->lang->line('LABEL_ADD_MORE');?></button>
        </div>
        <div class="col-xs-4">

        </div>
    </div>
    <div style="" class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $CI->lang->line('STATUS');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="status" name="booking[status]" class="form-control" tabindex="-1">
                <!--<option value=""></option>-->
                <option value="<?php echo $CI->config->item('system_status_active'); ?>"
                    <?php
                    if ($booking['status'] == $CI->config->item('system_status_active')) {
                        echo "selected='selected'";
                    }
                    ?> ><?php echo $CI->lang->line('ACTIVE') ?>
                </option>
                <option value="<?php echo $CI->config->item('system_status_inactive'); ?>"
                    <?php
                    if ($booking['status'] == $CI->config->item('system_status_inactive')) {
                        echo "selected='selected'";
                    }
                    ?> ><?php echo $CI->lang->line('INACTIVE') ?></option>
            </select>
        </div>
    </div>
</form>
<div id="system_add_more_content" style="display: none;">
    <div style="" class="row show-grid">
        <div class="col-xs-2">

        </div>
        <div class="col-xs-3">
            <select name="booked_varieties[<?php echo sizeof($booked_varieties);?>][id]" class="form-control variety" tabindex="-1">
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
            <input type="text" name="booked_varieties[<?php echo sizeof($booked_varieties);?>][quantity]" class="form-control quantity" value=""/>
        </div>
        <div class="col-xs-2">
            <button type="button" class="btn btn-danger system_add_more_delete"><?php echo $CI->lang->line('DELETE'); ?></button>
        </div>
    </div>
</div>