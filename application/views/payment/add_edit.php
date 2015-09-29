<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url.'/index/search/'.$payment['booking_id']);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $payment['id']; ?>" />
    <input type="hidden" id="booking_id" name="payment[booking_id]" value="<?php echo $payment['booking_id']; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
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
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[payment_date]" id="payment_date" class="form-control datepicker" value="<?php echo System_helper::display_date($payment['payment_date']);?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $payment['remarks'];?>"/>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $( ".datepicker" ).datepicker({dateFormat : display_date_format});

    });
</script>
