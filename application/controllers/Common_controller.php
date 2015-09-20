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
    public function get_dropdown_varieties_by_skintypeid()
    {
        $skin_type_id = $this->input->post('skin_type_id');
        $data['items']=Query_helper::get_info($this->config->item('table_varieties'),array('id value','variety_name text'),array('skin_type_id ='.$skin_type_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#variety_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_territories_by_zoneid()
    {
        $zone_id = $this->input->post('zone_id');
        $data['items']=Query_helper::get_info($this->config->item('table_territories'),array('id value','territory_name text'),array('zone_id ='.$zone_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#territory_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_districts_by_territoryid()
    {
        $territory_id = $this->input->post('territory_id');
        $data['items']=Query_helper::get_info($this->config->item('table_districts'),array('id value','district_name text'),array('territory_id ='.$territory_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#district_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_upazilas_by_districtid()
    {
        $district_id = $this->input->post('district_id');
        $data['items']=Query_helper::get_info($this->config->item('table_upazilas'),array('id value','upazila_name text'),array('district_id ='.$district_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#upazila_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_unions_by_upazilaid()
    {
        $upazila_id = $this->input->post('upazila_id');
        $data['items']=Query_helper::get_info($this->config->item('table_unions'),array('id value','union_name text'),array('upazila_id ='.$upazila_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#union_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
    public function get_dropdown_customers_by_unionid()
    {
        $union_id = $this->input->post('union_id');
        $data['items']=Query_helper::get_info($this->config->item('table_customers'),array('id value','customer_name text'),array('union_id ='.$union_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#customer_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
}
