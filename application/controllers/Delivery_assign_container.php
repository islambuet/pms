<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_assign_container extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Delivery_assign_container');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='delivery_assign_container';
        $this->load->model("delivery_assign_container_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="edit")
        {
            $this->system_edit();
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
            $data['title']="Assign Container to Customer";
            $data['varieties']=System_helper::get_all_varieties_for_dropdown();
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("delivery_assign_container/search",$data,true));
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
        $year=$this->input->post('year');
        $consignment_id=$this->input->post('consignment_id');
        $container_no=$this->input->post('container_no');
        $container_variety_type=$this->input->post('container_variety_type');
        $data['year']=$year;
        $data['consignment_id']=$consignment_id;
        $data['container_no']=$container_no;
        $data['container_variety_type']=$container_variety_type;

        $data['title']="Set Container";
        $data['delivery_time']=time();
        $data['container_id']='';
        $data['remarks']='';
        //$data['container_id']=2;
        $assigned_container=Query_helper::get_info($this->config->item('table_delivery_container_id_no'),array('container_id,date,remarks'),array('consignment_id ='.$consignment_id,'variety_id ='.$container_variety_type,'container_no ='.$container_no,'revision =1'),1);
        $data['containers']=$this->delivery_assign_container_model->get_containers($consignment_id,$container_variety_type);
        if($assigned_container)
        {
            $data['title']="Edit Container";
            $data['delivery_time']=$assigned_container['date'];
            $data['container_id']=$assigned_container['container_id'];
            $data['remarks']=$assigned_container['remarks'];

        }


        $data['bookings']=$this->delivery_assign_container_model->get_bookings($consignment_id,$container_variety_type,$container_no);

        $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("delivery_assign_container/edit",$data,true));

        $ajax['status']=false;
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
            $year=$this->input->post('year');
            $consignment_id=$this->input->post('consignment_id');
            $container_no=$this->input->post('container_no');
            $container_variety_type=$this->input->post('container_variety_type');
            $container_id=$this->input->post('container_id');
            $date=$this->input->post('delivery_time');
            $remarks=$this->input->post('remarks');
            $time=time();

            $data['consignment_id']=$consignment_id;
            $data['container_no']=$container_no;
            $data['variety_id']=$container_variety_type;
            $data['container_id']=$container_id;
            $data['date']=System_helper::get_time($date);
            $data['remarks']=$remarks;
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;

            $this->db->trans_start();  //DB Transaction Handle START

            $this->db->where('consignment_id',$consignment_id);
            $this->db->where('container_no',$container_no);
            $this->db->where('variety_id',$container_variety_type);
            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_delivery_container_id_no'));

            Query_helper::add($this->config->item('table_delivery_container_id_no'),$data);
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

        if($this->input->post('container_id')>0)
        {
            return true;
        }
        else
        {
            $this->message="Select a container";
            return false;
        }
    }

}
