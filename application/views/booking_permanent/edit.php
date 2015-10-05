<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $booking['id']; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CUSTOMER_NAME');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <label class="control-label"><?php echo $booking['customer_name'];?></label>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PRELIMINARY_BOOKING_DATE');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <label class="control-label"><?php echo System_helper::display_date($booking['preliminary_booking_date']);?></label>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TOTAL_PRELIMINARY_PAYMENT');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <label class="control-label"><?php echo $payment_preliminary['amount'];?></label>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-2">
            </div>
            <div class="col-xs-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?></th>
                            <th class="text-center"><?php echo $CI->lang->line('LABEL_QUANTITY');?></th>
<!--                            <th class="text-center">--><?php //echo $CI->lang->line('LABEL_UNIT_PRICE');?><!--</th>-->
<!--                            <th class="text-center">--><?php //echo $CI->lang->line('LABEL_TOTAL');?><!--</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total=0;
                            foreach($booked_varieties as $variety)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $variety['text']; ?></td>
                                    <td class="text-right"><?php echo $variety['quantity']; ?></td>
<!--                                    <td class="text-right">--><?php //echo $variety['unit_price']; ?><!--</td>-->
<!--                                    <td class="text-right">--><?php //echo $variety['quantity']*$variety['unit_price']; ?><!--</td>-->
                                </tr>
                                <?php
                                //$total+=$variety['quantity']*$variety['unit_price'];
                            }
                        ?>
<!--                        <tr>-->
<!--                            <td colspan="3" class="text-right">--><?php //echo $CI->lang->line('LABEL_TOTAL'); ?><!--</td>-->
<!--                            <td class="text-right">--><?php //echo $total; ?><!--</td>-->
<!--                        </tr>-->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="widget-header">
            <div class="title">
                <?php echo $CI->lang->line('LABEL_PERMANENT_PAYMENT'); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <input type="hidden" name="payment_id" value="<?php echo $payment_permanent['id']; ?>" />
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_AMOUNT');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[amount]" id="amount" class="form-control" value="<?php echo $payment_permanent['amount'];?>"/>
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
                        <option value="<?php echo $key;?>" <?php if ($payment_permanent['payment_method'] == $key) {echo "selected='selected'";}?>><?php echo $payment_method;?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PAYMENT_NUMBER');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[payment_number]" id="payment_number" class="form-control" value="<?php echo $payment_permanent['payment_number'];?>"/>
            </div>
        </div>
        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BANK_NAME');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[bank_name]" id="bank_name" class="form-control" value="<?php echo $payment_permanent['bank_name'];?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[payment_date]" class="form-control datepicker" value="<?php echo System_helper::display_date($payment_permanent['payment_date']);?>"/>
            </div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="payment[remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $payment_permanent['remarks'];?>"/>
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