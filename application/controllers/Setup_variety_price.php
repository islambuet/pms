<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_variety_price extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_variety_price');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_variety_price';
        //$this->load->model("sys_user_role_model");
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
            $data['title']="Set Variety Price";
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['classifications']=array();
            $data['types']=array();
            $data['skin_types']=array();
            $data['varieties']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_variety_price/search",$data,true));
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
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $variety_id=$this->input->post('variety_id');
            $year=$this->input->post('year');
            $variety_price=Query_helper::get_info($this->config->item('table_variety_price'),'*',array('variety_id ='.$variety_id,'year ='.$year,'revision =1'),1);
            if($variety_price)
            {
                $data['variety_price']=$variety_price;
            }
            else
            {
                $data['variety_price']['variety_id']=$variety_id;
                $data['variety_price']['year']=$year;
                $data['variety_price']['unit_price']=0;
                $data['variety_price']['remarks']='';
            }


            //$data['variety_price']=

            $data['title']='Set Variety price';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("setup_variety_price/edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }


            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    public function system_save()
    {
        $user=User_helper::get_user();

        if(!(isset($this->permissions['add'])&&($this->permissions['add']==1)))
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
            $data = $this->input->post('variety_price');
            $data['year']=$this->input->post('year');
            $data['variety_id']=$this->input->post('variety_id');
            $time=time();
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;
            $data['revision'] = 1;

            $this->db->trans_start();  //DB Transaction Handle START
            $this->db->where('year',$data['year']);
            $this->db->where('variety_id',$data['variety_id']);
            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_variety_price'));
            Query_helper::add($this->config->item('table_variety_price'),$data);

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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('variety_price[unit_price]',$this->lang->line('LABEL_UNIT_PRICE'),'required|numeric');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
}
