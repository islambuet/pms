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
                    <th>Preliminary B.Date</th>
                    <th>PAP Date</th>
                    <th>Total PAP Tk</th>
                    <th>Payment Method</th>
                    <th>C/P Number</th>
                    <th>Bank Name</th>
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
                        $max_variety_quantity=0;
                        foreach($booking['varieties'] as $variety)
                        {
                            //$customer_info.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['quantity'])."<br>";
                            if($variety['quantity']>$max_variety_quantity)
                            {
                                $max_variety_quantity=$variety['quantity'];
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo $booking['year'];?></td>
                            <td><?php echo $customer_info;?></td>
                            <td><?php echo $text_variety;?></td>
                            <td><?php echo $text_quantity;?></td>
                            <td><?php echo System_helper::display_date($booking['preliminary_booking_date']);?></td>

                            <td><?php echo System_helper::display_date($booking['preliminary_payment_info']['payment_date']);?></td>
                            <td><?php echo $booking['preliminary_payment_info']['amount'];?></td>
                            <td><?php echo $booking['preliminary_payment_info']['payment_method'];?></td>
                            <td><?php echo $booking['preliminary_payment_info']['payment_number'];?></td>
                            <td><?php echo $booking['preliminary_payment_info']['bank_name'];?></td>
                            <td><?php echo $booking['preliminary_remarks'];?></td>
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