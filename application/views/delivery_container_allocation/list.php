<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo "<pre>";
//print_r($containers);
//echo "</pre>";

?>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_VARIETY_TYPE');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="container_variety_type" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($varieties as $variety)
            {?>
                <option value="<?php echo $variety['id']?>"><?php echo $variety['variety_name'];?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NO');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="container_no" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
        </select>
    </div>
</div>
<div id="select_container">

</div>
<div id="edit_container">

</div>
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
<?php return; ?>
    <div class="row show-grid">
        <div class="col-xs-12" style="overflow-x: auto">
            <table class="table table-hover table-bordered" >
                <thead>
                <tr id="header_tr">
                    <th id="header_cs" colspan="3"></th>

                    <?php
                    foreach($containers as $container)
                    {
                        ?>
                        <th colspan="2"><?php echo $container['container_name']; ?></th>
                    <?php
                    }
                    ?>
                    <th colspan="2"></th>
                </tr>
                <tr>
                    <th>Customer</th>
                    <th>Variety</th>
                    <th>EQ</th>

                    <?php
                    foreach($containers as $container)
                    {
                        $text_variety='';
                        $text_quantity='';
                        foreach($container['varieties'] as $variety)
                        {
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['quantity'])."<br>";
                        }
                        ?>
                        <th id="header_<?php echo  $container['container_id']*2;?>"><?php echo $text_variety; ?></th>
                        <th id="header_<?php echo  $container['container_id']*2+1; ?>"><?php echo $text_quantity; ?></th>
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
                    <tr>
                        <td><?php echo $customer_info;?></td>
                        <td><?php echo $text_variety;?></td>
                        <td><?php echo $text_quantity;?></td>
                        <?php
                        foreach($containers as &$con)
                        {
                            $text_variety='';
                            $text_quantity='';

                            foreach($con['varieties'] as $variety)
                            {
                                if(isset($allocated_varieties[$booking['booking_id']][$con['container_id']][$variety['id']]['quantity']))
                                {
                                    $text_variety.=$variety['variety_name']."<br>";
                                    $text_quantity.=number_format($allocated_varieties[$booking['booking_id']][$con['container_id']][$variety['id']]['quantity'])."<br>";
                                    $booking['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['container_id']][$variety['id']]['quantity'];
                                    $con['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['container_id']][$variety['id']]['quantity'];
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
                <tr id="bottom_tr">
                    <td class="bottom" data-consignment-id="cs" colspan="3"></td>
                    <?php
                    foreach($containers as $container)
                    {
                        $text_variety='';
                        $text_quantity='';
                        //$text=$consignment;
                        foreach($container['varieties'] as $variety)
                        {
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['copy_quantity'])."<br>";
                        }
                        ?>
                        <td class="bottom" data-consignment-id="<?php echo $container['container_id']*2;?>"><?php echo($text_variety); ?></td>
                        <td class="bottom" data-consignment-id="<?php echo $container['container_id']*2+1; ?>"><?php echo($text_quantity); ?></td>
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

    });
</script>