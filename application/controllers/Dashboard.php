<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Root_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("dashboard_model");
    }

    public function get_sub_menu()
    {
        $module_id=$this->input->post("menu_id");
        $data['tasks']=$this->dashboard_model->get_tasks($module_id);

        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#sub-menu","html"=>$this->load->view("sub_menu",$data,true));

        $this->jsonReturn($ajax);

    }

}
