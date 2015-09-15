<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_controller extends Root_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";

    }

    public function get_dropdown_classifications_by_cropid()
    {
        $crop_id = $this->input->post('crop_id');
        $data['items']=Query_helper::get_info($this->config->item('table_classifications'),array('id value','classification_name text'),array('crop_id ='.$crop_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#classification_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_types_by_classificationid()
    {
        $classification_id = $this->input->post('classification_id');
        $data['items']=Query_helper::get_info($this->config->item('table_types'),array('id value','type_name text'),array('classification_id ='.$classification_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#type_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_skintypes_by_typeid()
    {
        $type_id = $this->input->post('type_id');
        $data['items']=Query_helper::get_info($this->config->item('table_skin_types'),array('id value','skin_type_name text'),array('type_id ='.$type_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#skin_type_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
}
