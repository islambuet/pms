<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" id="id" name="id" value="<?php echo $booking['id']; ?>" />
    <input type="hidden" name="booking[customer_id]" value="<?php echo $booking['customer_id']; ?>" />
    <input type="hidden" name="booking[year]" value="<?php echo $booking['year']; ?>" />
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
            foreach($booked_varieties as $i=>$booked_variety)
            {
                ?>
                <div style="" class="row show-grid">
                    <div class="col-xs-2">

                    </div>
                    <div class="col-xs-3">
                        <select name="booked_varieties[<?php echo $i+1;?>][id]" class="form-control variety" tabindex="-1">
                            <option value=""><?php echo $this->lang->line('SELECT');?></option>
                            <?php
                            foreach($varieties as $variety)
                            {
                                ?>
                                <option value="<?php echo $variety['id']?>" <?php if ($variety['id'] == $booked_variety['variety_id']) {echo "selected='selected'";}?>><?php echo $variety['text'];?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <input type="text" name="booked_varieties[<?php echo $i+1;?>][quantity]" class="form-control quantity" value="<?php echo $booked_variety['quantity']; ?>"/>
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
            <button type="button" class="btn btn-warning system_add_more_button" data-current-id="<?php echo sizeof($booked_varieties);?>"><?php echo $CI->lang->line('LABEL_ADD_MORE');?></button>
        </div>
        <div class="col-xs-4">

        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="booking[preliminary_booking_date]" id="preliminary_booking_date" class="form-control datepicker" value="<?php echo System_helper::display_date($booking['preliminary_booking_date']);?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="booking[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $booking['remarks'];?>"/>
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
    <div class="widget-header">
        <div class="title">
            <?php echo $CI->lang->line('LABEL_PRELIMINARY_PAYMENT'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_AMOUNT');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[amount]" id="amount" class="form-control" value="<?php echo $payment['amount'];?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PAYMENT_METHOD');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select name="payment[payment_method]" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($CI->config->item('payment_method') as $key=>$payment_method)
                {
                    ?>
                    <option value="<?php echo $key;?>" <?php if ($payment['payment_method'] == $key) {echo "selected='selected'";}?>><?php echo $payment_method;?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PAYMENT_NUMBER');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[payment_number]" id="payment_number" class="form-control" value="<?php echo $payment['payment_number'];?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BANK_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[bank_name]" id="bank_name" class="form-control" value="<?php echo $payment['bank_name'];?>"/>
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
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $( ".datepicker" ).datepicker({dateFormat : display_date_format});
    });
</script>
