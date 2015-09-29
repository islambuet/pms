<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $CI->load->view("action_buttons",$action_data);
?>

<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_BOOKING_ID');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-2">
            <input type="text" id="booking_id" class="form-control" value="<?php if($booking_id){echo $booking_id;} ?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
        </div>
        <div class="col-xs-2">
            <input type="button" id="button_search" class="form-control btn-primary" value="<?php echo $this->lang->line('LABEL_SEARCH');?>" />
        </div>
    </div>
</div>
    <div class="row widget" id="detail_container">
       <?php
       if($booking_id)
       {
           $payments=Query_helper::get_info($CI->config->item('table_booking_payments'),'*',array('booking_id ='.$booking_id));
           if((sizeof($payments))>0)
           {
               $data['title']="Payment History";
               $data['payments']=$payments;
               $data['booking_id']=$booking_id;
               $CI->load->view('payment/list',$data);
           }
       }
       ?>
    </div>
    <div class="clearfix"></div>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();

        $(document).on("click","#button_search",function()
        {

            $("#detail_container").html("");

            var booking_id=$("#booking_id").val();
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/list/",
                type: 'POST',
                datatype: "JSON",
                data:{booking_id:booking_id},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });

        });
    });
</script>
