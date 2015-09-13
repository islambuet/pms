<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    if(isset($this->permissions['add'])&&($this->permissions['add']==1))
    {
        $action_data["action_new"]=base_url($CI->controller_url."/index/add");
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
                { name: 'edit_link', type: 'string' },
                { name: 'component_name', type: 'string' },
                { name: 'description', type: 'string' },
                { name: 'component_icon', type: 'string' },
                { name: 'ordering', type: 'string' },
                { name: 'status_text', type: 'string' }
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
                pagesize:10,
                pagesizeoptions: ['10', '20', '30', '50','100','150'],
                selectionmode: 'checkbox',
                altrows: true,
                autoheight: true,
                columns: [
                    { text: '<?php echo $CI->lang->line('NAME'); ?>', dataField: 'component_name',width:'15%'},
                    { text: '<?php echo $CI->lang->line('DESCRIPTION'); ?>', dataField: 'description'},
                    { text: '<?php echo $CI->lang->line('ICON'); ?>', dataField: 'component_icon',width:'25%'},
                    { text: '<?php echo $CI->lang->line('ORDERING'); ?>', dataField: 'ordering',cellsalign: 'right',width:'70'},
                    { text: '<?php echo $CI->lang->line('STATUS'); ?>', dataField: 'status_text',cellsalign: 'right',width:'70'}
                ]
            });
    });
</script>