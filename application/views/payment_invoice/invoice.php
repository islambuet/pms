<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($consignment_info);
//echo '</PRE>';
//return;
//echo '<PRE>';
//print_r(($booking_info));
//echo '</PRE>';
//echo '<PRE>';
//print_r($allocated_varieties);
//echo '</PRE>';
//return;
?>
<div class="col-xs-12">
    <div class="row" style="background-color: <?php echo $setup_invoice['background_color']; ?>;height: 160px;width: 1000px; border-bottom:15px solid <?php echo $setup_invoice['border_color']; ?>;" >
        <div class="col-xs-2 text-center" style="line-height: 140px;">
            <img style="max-width: 130px;" src="<?php echo base_url().'images/invoice_logo/'.$setup_invoice['logo'];?>">&nbsp;
        </div>
        <div class="col-xs-10">
            <h1 style="color: <?php echo $setup_invoice['border_color']; ?>"><?php echo $setup_invoice['line1']; ?></h1>
            <div><?php echo $setup_invoice['line2']; ?></div>
            <div><?php echo $setup_invoice['line3']; ?></div>
        </div>
    </div>
    <div class="row" style="width: 1000px;">
        <div class="col-xs-12 text-right" style="width: 800px;margin-top: 10px;margin-bottom: 10px;">
            Date : <?php echo $invoice_date; ?>
        </div>
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $total=0;
                        foreach($payments as $payment)
                        {
                            $total+=$payment['amount'];
                            ?>
                            <tr>
                                <td><?php echo System_helper::display_date($payment['payment_date']); ?></td>
                                <td><?php echo $payment['booking_status']; ?></td>
                                <td class="text-right"><?php echo number_format($payment['amount']); ?></td>
                            </tr>
                            <?php
                        }
                    ?>
                    <tr>
                        <td colspan="2" class="text-right">Total</td>
                        <td class="text-right"><?php echo number_format($total); ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
