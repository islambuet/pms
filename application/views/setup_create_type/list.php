<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    if(isset($this->permissions['add'])&&($this->permissions['add']==1))
    {
        $action_data["action_new"]=base_url($CI->controller_url."/index/add");
    }
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
                { name: 'type_name', type: 'string' },
                { name: 'classification_name', type: 'string' },
                { name: 'crop_name', type: 'string' },
                { name: 'remarks', type: 'string' },
                { name: 'ordering', type: 'string' },
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
                    { text: '<?php echo $CI->lang->line('LABEL_TYPE_NAME'); ?>', dataField: 'type_name',width:'15%'},
                    { text: '<?php echo $CI->lang->line('LABEL_CROP_NAME'); ?>', dataField: 'crop_name',width:'15%'},
                    { text: '<?php echo $CI->lang->line('LABEL_CLASSIFICATION_NAME'); ?>', dataField: 'classification_name',width:'15%'},
                    { text: '<?php echo $CI->lang->line('LABEL_REMARKS'); ?>', dataField: 'remarks'},
                    { text: '<?php echo $CI->lang->line('LABEL_ORDER'); ?>', dataField: 'ordering',cellsalign: 'right',width:'100'},
                    { text: '<?php echo $CI->lang->line('STATUS'); ?>', dataField: 'status',filtertype: 'list',width:'150',cellsalign: 'right'}
                ]
            });
    });
</script>