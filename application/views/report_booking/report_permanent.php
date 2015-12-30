<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo "<pre>";
//print_r($bookings);
//echo "</pre>";

?>
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-12" style="overflow-x: auto">
            <table class="table table-hover table-bordered" >
                <thead>
                <tr>
                    <th>Year</th>
                    <th>Customer</th>
                    <th>Variety</th>
                    <th>BQ</th>
                    <th>Price/Box</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Permanent B.Date</th>
                    <th>Preliminary Payment</th>
                    <th>Permanent Payment</th>
                    <th>Other Payment</th>
                    <th>Due</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(sizeof($bookings)>0)
                {
                    foreach($bookings as $booking)
                    {
                        /*echo '<PRE>';
                        print_r($booking);
                        echo '</PRE>';*/
                        $customer_info=$booking['customer_name'];
                        $text_variety='';
                        $text_quantity='';
                        $text_price='';
                        $text_discount='';
                        $total=0;

                        foreach($booking['varieties'] as $variety)
                        {
                            //$customer_info.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['quantity'])."<br>";
                            $text_price.=number_format($variety['unit_price'])."<br>";
                            $text_discount.=number_format($variety['discount'])."<br>";
                            $total=$total+$variety['quantity']*($variety['unit_price']-$variety['discount']);
                        }
                        ?>
                        <tr>
                            <td><?php echo $booking['year'];?></td>
                            <td><?php echo $customer_info;?></td>
                            <td><?php echo $text_variety;?></td>
                            <td><?php echo $text_quantity;?></td>
                            <td><?php echo $text_price;?></td>
                            <td><?php echo $text_discount;?></td>
                            <td><?php echo number_format($total);?></td>
                            <td><?php echo System_helper::display_date($booking['permanent_booking_date']);?></td>
                            <td><?php echo number_format($booking['preliminary_amount']);?></td>
                            <td><?php echo number_format($booking['permanent_amount']);?></td>
                            <td><?php echo number_format($booking['other_amount']);?></td>
                            <td><?php echo number_format($total-$booking['preliminary_amount']-$booking['permanent_amount']-$booking['other_amount']);?></td>
                            <td><?php echo $booking['permanent_remarks'];?></td>
                        </tr>
                    <?php
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="20" class="text-center alert-danger">
                            <?php echo $this->lang->line("NO_DATA_FOUND"); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {

    });
</script>