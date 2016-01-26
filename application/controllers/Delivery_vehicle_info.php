<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_vehicle_info extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Delivery_vehicle_info');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='delivery_vehicle_info';
        //$this->load->model("allocation_consignment_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="edit")
        {
            $this->system_edit($id);
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_search();
        }
    }

    public function system_search()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Vehicle Info & Loading Picture";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("delivery_vehicle_info/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_edit()
    {
        $consignment_id=$this->input->post('consignment_id');
        $vehicle_no=$this->input->post('vehicle_no');
        $year=$this->input->post('year');
        $data['consignment_id']=$consignment_id;
        $data['vehicle_no']=$vehicle_no;
        $data['year']=$year;

        $this->db->from($this->config->item('table_setup_vehicle_loading'));
        $this->db->order_by('ordering ASC');
        $data['pictures']=$this->db->get()->result_array();

        $data['vehicle_info']=Query_helper::get_info($this->config->item('table_data_vehicle_info_loading'),'*',array('consignment_id ='.$consignment_id,'vehicle_no ='.$vehicle_no),1);
        $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("delivery_vehicle_info/edit",$data,true));

        $ajax['status']=true;
        if($this->message)
        {
            $ajax['system_message']=$this->message;
        }
        $this->jsonReturn($ajax);
    }
    public function system_save()
    {
        $user=User_helper::get_user();


        if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
            die();
        }

        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $consignment_id=$this->input->post('consignment_id');
            $vehicle_no=$this->input->post('vehicle_no');
            $year=$this->input->post('year');

            $uploaded_images = System_helper::upload_file('images/delivery');

            $this->db->from($this->config->item('table_setup_vehicle_loading'));
            $this->db->order_by('ordering ASC');
            $pictures=$this->db->get()->result_array();
            $info=array();
            foreach($pictures as $picture)
            {
                if(array_key_exists('image_'.$picture['id'],$uploaded_images))
                {
                    $info[$picture['id']]=$uploaded_images['image_'.$picture['id']]['info']['file_name'];
                }
                else
                {
                    $info[$picture['id']]=$this->input->post('previous_image_'.$picture['id']);
                }
            }
            $vehicle_info=Query_helper::get_info($this->config->item('table_data_vehicle_info_loading'),'*',array('consignment_id ='.$consignment_id,'vehicle_no ='.$vehicle_no),1);
            $time=time();
            $data=$this->input->post('vehicle');
            $data['images']=json_encode($info);

            $this->db->trans_start();  //DB Transaction Handle START
            if($vehicle_info)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                Query_helper::update($this->config->item('table_data_vehicle_info_loading'),$data,array("id = ".$vehicle_info['id']));
            }
            else
            {
                $data['consignment_id']=$consignment_id;
                $data['vehicle_no']=$vehicle_no;
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                Query_helper::add($this->config->item('table_data_vehicle_info_loading'),$data);
            }
            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {

                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                $this->system_edit();

            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                $this->jsonReturn($ajax);
            }
        }
    }
    private function check_validation()
    {

        return true;
    }

}
