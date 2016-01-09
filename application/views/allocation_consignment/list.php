<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function get_color($colors,$quantity)
{
    $selected_color="#FFFFFF";
    foreach($colors as $color)
    {
        if(($color['min_quantity']<=$quantity)&&($color['max_quantity']>=$quantity))
        {
            $selected_color=$color['color_code'];
            break;
        }
    }
    return $selected_color;
}
?>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="booking_id" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($bookings as $booking)
            {?>
                <option value="<?php echo $booking['booking_id']?>"><?php echo $booking['customer_name'];?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>


<div id="edit_container">

</div>
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div id="data_div" class="col-xs-12" style="overflow: hidden;margin-bottom: 100px;">
            <table class="table table-bordered" >
                <thead>
                    <tr>
                        <th colspan="3"></th>

                        <?php
                        foreach($consignments as $consignment)
                        {
                        ?>
                            <th colspan="2"><?php echo $consignment['consignment_name'].'<br>'.System_helper::display_date($consignment['expected_receive_date']); ?></th>
                        <?php
                        }
                        ?>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th><div class="header_div">Customer</div></th>
                        <th><div class="header_div">Variety</div></th>
                        <th><div class="header_div">EQ</div></th>

                        <?php
                        foreach($consignments as $consignment)
                        {
                            $text_variety='';
                            $text_quantity='';
                            foreach($consignment['varieties'] as $variety)
                            {
                                $text_variety.=$variety['variety_name']."<br>";
                                $text_quantity.=number_format($variety['quantity'])."<br>";
                            }
                            ?>
                            <th><div class="header_div"><?php echo $text_variety; ?></div></th>
                            <th><div class="header_div"><?php echo $text_quantity; ?></div></th>
                        <?php
                        }
                        ?>
                        <th><div class="header_div">RV</div></th>
                        <th><div class="header_div">RQ</div></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($bookings as &$booking)
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
                    <tr style="background-color: <?php echo get_color($colors,$max_variety_quantity); ?>">
                        <td><?php echo $customer_info;?></td>
                        <td><?php echo $text_variety;?></td>
                        <td><?php echo $text_quantity;?></td>
                        <?php
                            foreach($consignments as &$con)
                            {
                                $text_variety='';
                                $text_quantity='';

                                foreach($con['varieties'] as $variety)
                                {
                                    if(isset($allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity']))
                                    {
                                        $text_variety.=$variety['variety_name']."<br>";
                                        $text_quantity.=number_format($allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'])."<br>";
                                        $booking['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'];
                                        $con['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'];
                                    }
                                    else
                                    {
                                        $text_variety.=$variety['variety_name']."<br>";
                                        $text_quantity.='0'."<br>";
                                    }
                                    //$con['varieties'][$variety['id']]['copy_quantity']=0;
                                }
                                ?>
                                <td><?php echo $text_variety; ?></td>
                                <td><?php echo $text_quantity; ?></td>
                                <?php
                            }
                        $text_variety='';
                        $text_quantity='';
                        foreach($booking['varieties'] as $variety)
                        {
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['copy_quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo $text_variety; ?></td>
                        <td><?php echo $text_quantity; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<div id="scroll_div" class="col-xs-12" style="overflow-x: auto;position: fixed;bottom: 10px;background-color: #0daed3" >
    <table class="table table-bordered" style="margin-bottom: 0;" >
        <tbody>

        <tr>
            <td><div class="footer_div"> &nbsp;</div></td>
            <td><div class="footer_div"> &nbsp;</div></td>
            <td><div class="footer_div"> &nbsp;</div></td>
            <?php
            foreach($consignments as $consignment)
            {
                $text_variety='';
                $text_quantity='';
                //$text=$consignment;
                foreach($consignment['varieties'] as $variety)
                {
                    $text_variety.=$variety['variety_name']."<br>";
                    $text_quantity.=number_format($variety['copy_quantity'])."<br>";
                }
                ?>
                <td><div class="footer_div"><?php echo($text_variety); ?></div></td>
                <td><div class="footer_div"><?php echo($text_quantity); ?></div></td>
            <?php
            }
            ?>
            <td><div class="footer_div"> &nbsp;</div></td>
            <td><div class="footer_div"> &nbsp;</div></td>
        </tr>
        <tr style="background-color: #0daed3;">
            <td colspan="3"></td>

            <?php
            foreach($consignments as $consignment)
            {
                ?>
                <td colspan="2"><?php echo $consignment['consignment_name'].'<br>'.System_helper::display_date($consignment['expected_receive_date']); ?></td>
            <?php
            }
            ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        /*$("#bottom_tr").outerWidth($('#header_tr').width());

        $( ".bottom" ).each(function( index )
        {
            var consignment_id=$(this).attr('data-consignment-id');
            var header=$('#header_'+consignment_id);
            var width=header.width();
            $(this).outerWidth(width);

        });*/
        $("#scroll_div").width($("#data_div").width());
        var footer_divs=$(".footer_div");
        $(".header_div").each( function( index)
        {
            var width=$(this).width();
            $(footer_divs[index]).width(width)
        });
        $("#scroll_div").scroll(function()
        {
            $("#data_div").scrollLeft($("#scroll_div").scrollLeft());
        });

    });
</script>