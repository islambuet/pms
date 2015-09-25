<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
    {
        $action_data["action_edit"]=base_url($CI->controller_url."/index/edit");
    }
    $CI->load->view("action_buttons",$action_data);
?>

<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="col-xs-12" id="system_jqx_container">

    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        turn_off_triggers();
        var url = "<?php echo base_url($CI->controller_url.'/get_crops');?>";

        // prepare the data
        var source =
        {
            dataType: "json",
            dataFields: [
                { name: 'id', type: 'int' },
                { name: 'customer_name', type: 'string' },
                { name: 'year', type: 'int' },
                { name: 'booking_status', type: 'string' },
                { name: 'total_quantity', type: 'number' },
                { name: 'total_price', type: 'number' },
                { name: 'preliminary_payment', type: 'number' },
                { name: 'permanent_payment', type: 'number' },
                { name: 'total_payment', type: 'number' },
                { name: 'preliminary_booking_date', type: 'string' },
                { name: 'permanent_booking_date', type: 'string' },
                { name: 'status', type: 'string' }
            ],
            id: 'id',
            url: url
        };

        var dataAdapter = new $.jqx.dataAdapter(source);
        // create jqxgrid.
        $("#system_jqx_container").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                pageable: true,
                filterable: true,
                sortable: true,
                showfilterrow: true,
                columnsresize: true,
                pagesize:50,
                pagesizeoptions: ['20', '50', '100', '200','300','500'],
                selectionmode: 'checkbox',
                altrows: true,
                autoheight: true,
                columns: [
                    { text: '<?php echo $CI->lang->line('LABEL_BOOKING_ID'); ?>', dataField: 'id',width:'50',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_CUSTOMER_NAME'); ?>', dataField: 'customer_name'},
                    { text: '<?php echo $CI->lang->line('LABEL_YEAR'); ?>', dataField: 'year',filtertype: 'list',width:'50',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_BOOKING_STATUS'); ?>',filtertype: 'list', dataField: 'booking_status',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_TOTAL_QUANTITY'); ?>',dataField: 'total_quantity',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_TOTAL_PRICE'); ?>',dataField: 'total_price',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_TOTAL_PRELIMINARY_PAYMENT'); ?>',dataField: 'preliminary_payment',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_PERMANENT_PAYMENT'); ?>',dataField: 'permanent_payment',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_TOTAL_ADVANCED_PAYMENT'); ?>',dataField: 'total_payment',width:'100',cellsalign: 'right'},
                    { text: '<?php echo $CI->lang->line('LABEL_PRELIMINARY_BOOKING_DATE'); ?>', dataField: 'preliminary_booking_date'},
                    { text: '<?php echo $CI->lang->line('LABEL_PERMANENT_BOOKING_DATE'); ?>', dataField: 'permanent_booking_date'},
                    { text: '<?php echo $CI->lang->line('STATUS'); ?>', dataField: 'status',filtertype: 'list',width:'150',cellsalign: 'right'}
                ]
            });
    });
</script>