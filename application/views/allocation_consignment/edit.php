<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($varieties);
//print_r($booked_varieties);
//echo '</PRE>';
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" id="id" name="id" value="<?php echo $booking['id']; ?>" />

    <table class="table table-bordered" id="system_add_more_container">
        <thead>
            <tr>
                <th><?php echo $CI->lang->line('LABEL_DATE');?></th>
                <th><?php echo $CI->lang->line('LABEL_VARIETY_NAME');?></th>
                <th><?php echo $CI->lang->line('LABEL_QUANTITY');?></th>
                <th><?php echo $CI->lang->line('LABEL_UNIT_PRICE');?></th>
                <th><?php echo $CI->lang->line('LABEL_DISCOUNT');?></th>
                <th><?php echo $CI->lang->line('LABEL_TOTAL');?></th>
                <th><?php echo $CI->lang->line('LABEL_ACTION');?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total=0;
        foreach($booked_varieties as $i=>$booked_variety)
        {
            ?>
            <tr>
                <td><input type="text" name="booked_varieties[<?php echo $i+1;?>][date]" class="form-control date datepicker" value="<?php echo System_helper::display_date($booked_variety['date']); ?>"/></td>
                <td>
                    <select name="booked_varieties[<?php echo $i+1;?>][id]" data-index="<?php echo $i+1;?>" class="form-control variety" tabindex="-1">
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
                </td>
                <td><input type="text" name="booked_varieties[<?php echo $i+1;?>][quantity]" id="booked_varieties_<?php echo $i+1;?>_quantity" data-index="<?php echo $i+1;?>" class="form-control quantity" value="<?php echo $booked_variety['quantity']; ?>"/></td>
                <td><input type="text" name="booked_varieties[<?php echo $i+1;?>][unit_price]" id="booked_varieties_<?php echo $i+1;?>_unit_price" data-index="<?php echo $i+1;?>" class="form-control unit_price" readonly value="<?php echo $booked_variety['unit_price']; ?>"/></td>
                <td><input type="text" name="booked_varieties[<?php echo $i+1;?>][discount]" id="booked_varieties_<?php echo $i+1;?>_discount" data-index="<?php echo $i+1;?>" class="form-control discount" value="<?php echo $booked_variety['discount']; ?>"/></td>
                <td>
                    <?php
                        $sub_total=($booked_variety['unit_price']-$booked_variety['discount'])*$booked_variety['quantity'];
                        $total+=$sub_total;
                    ?>
                    <input type="text" id="booked_varieties_<?php echo $i+1;?>_total" data-index="<?php echo $i+1;?>" class="form-control total" readonly value="<?php echo number_format($sub_total); ?>"/></td>
                </td>
                <td><button type="button" class="btn btn-danger system_add_more_delete"><?php echo $CI->lang->line('DELETE'); ?></button></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="5" class="text-center"><button type="button" class="btn btn-warning system_add_more_button" data-current-id="<?php echo sizeof($booked_varieties);?>"><?php echo $CI->lang->line('LABEL_ADD_MORE');?></button></td>
            <td id="total_price"><?php echo number_format($total); ?></td>
            <td></td>
        </tr>
        </tbody>

    </table>

    <div class="row show-grid">
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>
        <div class="col-xs-4">

        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_DATE');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="booking[permanent_booking_date]" id="permanent_booking_date" class="form-control datepicker" value="<?php echo System_helper::display_date($booking['permanent_booking_date']);?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="booking[permanent_remarks]" id="remarks" class="form-control validate[required]" value="<?php echo $booking['permanent_remarks'];?>"/>
        </div>
    </div>
    <div class="widget-header">
        <div class="title">
            <?php echo $CI->lang->line('LABEL_PERMANENT_PAYMENT'); ?>
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
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PAYMENT_NUMBER');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[payment_number]" id="payment_number" class="form-control" value="<?php echo $payment['payment_number'];?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BANK_NAME');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[bank_name]" id="bank_name" class="form-control" value="<?php echo $payment['bank_name'];?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BRANCH_NAME');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="payment[branch_name]" id="branch_name" class="form-control" value="<?php echo $payment['branch_name'];?>"/>
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
</form>
<div id="system_add_more_content" style="display: none;">
    <table>
        <tbody>
            <tr>
                <td><input type="text" name="booked_varieties[<?php echo sizeof($booked_varieties);?>][date]" class="form-control date" value="<?php echo System_helper::display_date(time()); ?>"/></td>
                <td>
                    <select name="booked_varieties[<?php echo sizeof($booked_varieties);?>][id]" data-index="<?php echo sizeof($booked_varieties);?>" class="form-control variety">
                        <option value=""><?php echo $this->lang->line('SELECT');?></option>
                        <?php
                        foreach($varieties as $variety)
                        {?>
                            <option value="<?php echo $variety['id']?>"><?php echo $variety['text'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td><input type="text" name="booked_varieties[<?php echo sizeof($booked_varieties);?>][quantity]" id="booked_varieties_<?php echo sizeof($booked_varieties);?>_quantity" data-index="<?php echo sizeof($booked_varieties);?>" class="form-control quantity" value="0"/></td>
                <td><input type="text" name="booked_varieties[<?php echo sizeof($booked_varieties);?>][unit_price]" id="booked_varieties_<?php echo sizeof($booked_varieties);?>_unit_price" data-index="<?php echo sizeof($booked_varieties);?>" readonly class="form-control unit_price" value="0"/></td>
                <td><input type="text" name="booked_varieties[<?php echo sizeof($booked_varieties);?>][discount]" id="booked_varieties_<?php echo sizeof($booked_varieties);?>_discount" data-index="<?php echo sizeof($booked_varieties);?>" class="form-control discount" value="0"/></td>
                <td><input type="text" id="booked_varieties_<?php echo sizeof($booked_varieties);?>_total" data-index="<?php echo sizeof($booked_varieties);?>" class="form-control total" readonly value="0"/></td></td>
                <td><button type="button" class="btn btn-danger system_add_more_delete"><?php echo $CI->lang->line('DELETE'); ?></button></td>

            </tr>
        </tbody>
    </table>
</div>
<?php
foreach($varieties as $variety)
{
    ?>
    <input type="hidden" value="<?php echo $variety['unit_price'] ?>" id="variety_unit_price_<?php echo $variety['id'];?>">
    <?php
}
?>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".datepicker").datepicker({dateFormat : display_date_format});
    });
</script>
