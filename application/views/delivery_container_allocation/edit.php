<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($other_allocated_varieties);
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



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">

    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>" />
    <input type="hidden" name="container_id" value="<?php echo $container_id; ?>" />
    <?php
    foreach($bookings as $booking)
    {
        ?>
        <div class="widget-header">
            <div class="title">
                <?php echo $booking['customer_name']; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Variety</th>
                <th>Customer Quantity</th>
                <th>Total Allocated Quantity</th>
                <th>Allocated Quantity</th>
                <th>Remaining</th>
                <th>Date</th>
            </tr>
            <tbody>
            <?php
            foreach($booking['varieties'] as $variety)
            {
                if(array_key_exists($variety['id'],$container_info))
                {
                ?>
                    <tr>
                        <td>
                            <?php echo $variety['variety_name']; ?>
                            <input type="hidden" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][variety_id]" value="<?php echo $variety['id']; ?>">
                        </td>
                        <td><input type="text" id="cs_quantity_<?php echo $booking['booking_id'].'_'.$variety['id']; ?>" disabled value="<?php echo number_format($variety['quantity']); ?>"></td>

                        <td>
                            <?php
                            $other_quantity=0;
                            if(isset($other_allocated_varieties[$booking['booking_id']][$variety['id']]))
                            {
                                $other_quantity=$other_allocated_varieties[$booking['booking_id']][$variety['id']]['quantity'];
                            }
                            ?>
                            <input type="text" id="other_quantity_<?php echo $booking['booking_id'].'_'.$variety['id']; ?>" disabled value="<?php echo number_format($other_quantity); ?>">
                        </td>
                        <td>
                            <?php
                                $quantity=0;
                                $time=time();
                                if(isset($allocated_varieties[$booking['booking_id']][$variety['id']]))
                                {
                                    $quantity=$allocated_varieties[$booking['booking_id']][$variety['id']]['quantity'];
                                    $time=$allocated_varieties[$booking['booking_id']][$variety['id']]['date'];
                                }
                                $container_info[$variety['id']]['copy_quantity']-=$quantity;
                            ?>
                            <input type="text" data-booking-id="<?php echo $booking['booking_id'];?>" data-variety-id="<?php echo $variety['id'];?>" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][quantity]" class="form-control quantity" value="<?php echo $quantity; ?>"/>
                        </td>
                        <td><input type="text" id="remain_quantity_<?php echo $booking['booking_id'].'_'.$variety['id']; ?>" disabled value="<?php echo number_format($variety['quantity']-$other_quantity-$quantity); ?>"></td>
                        <td><input type="text" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][date]" class="form-control datepicker" value="<?php {echo System_helper::display_date($time);} ?>"/></td>

                    </tr>
                <?php
                }
            }
            ?>
            </tbody>
            </thead>

        </table>
        <?php
    }

        ?>
        <div class="widget-header">
            <div class="title">
                Remaining
            </div>
            <div class="clearfix"></div>
        </div>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Variety</th>
            <th>Quantity</th>
            <th>Remaining Quantity</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($container_info as $info)
            {
                ?>
                <tr>
                    <td><?php echo $info['variety_name'] ?></td>
                    <td><input type="text" id="total_quantity_<?php echo $info['variety_id']; ?>" disabled value="<?php echo number_format($info['quantity']); ?>"></td>
                    <td><input type="text" id="total_remain_<?php echo $info['variety_id']; ?>" disabled value="<?php echo number_format($info['copy_quantity']); ?>"></td>
                </tr>
                <?php
            }
        ?>
        </tbody>
    </table>



</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".datepicker").datepicker({dateFormat : display_date_format});
    });
</script>
