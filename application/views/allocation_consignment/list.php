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
        <div class="col-xs-12" style="overflow-x: auto">
            <table class="table table-hover table-bordered" >
                <thead>
                    <tr id="header_tr">
                        <th id="header_cs" colspan="3"></th>

                        <?php
                        foreach($consignments as $consignment)
                        {
                        ?>
                            <th colspan="2"><?php echo $consignment['consignment_name'].'<br>'.System_helper::display_date($consignment['expected_receive_date']); ?></th>
                        <?php
                        }
                        ?>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <th>Variety</th>
                        <th>EQ</th>

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
                            <th id="header_<?php echo  $consignment['consignment_id']*2;?>"><?php echo $text_variety; ?></th>
                            <th id="header_<?php echo  $consignment['consignment_id']*2+1; ?>"><?php echo $text_quantity; ?></th>
                        <?php
                        }
                        ?>
                        <th id="header_rv">RV</th>
                        <th id="header_rq">RQ</th>
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
                <tr id="bottom_tr" style="position: fixed;bottom: 0px;">
                    <td class="bottom" data-consignment-id="cs" colspan="3"></td>
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
                        <td class="bottom" data-consignment-id="<?php echo $consignment['consignment_id']*2;?>"><?php echo($text_variety); ?></td>
                        <td class="bottom" data-consignment-id="<?php echo $consignment['consignment_id']*2+1; ?>"><?php echo($text_quantity); ?></td>
                    <?php
                    }
                    ?>
                    <td class="bottom" data-consignment-id="rv"></td>
                    <td class="bottom" data-consignment-id="rq"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $("#bottom_tr").outerWidth($('#header_tr').width());

        $( ".bottom" ).each(function( index )
        {
            var consignment_id=$(this).attr('data-consignment-id');
            var header=$('#header_'+consignment_id);
            var width=header.width();
            $(this).outerWidth(width);

        });

    });
</script>