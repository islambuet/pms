<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_user_role extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Sys_user_role');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='sys_user_role';
        $this->load->model("sys_user_role_model");
    }

    public function index($action="list",$id=0)
    {
        if($action=="list")
        {
            $this->system_list($id);
        }
        elseif($action=="add" || $action=="edit")
        {
            $this->system_add_edit($id);
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_list($id);
        }
    }

    public function system_list()
    {
        $data['title']="User Role";
        $data['user_groups_info']=$this->sys_user_role_model->get_roles_count();

        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("sys_user_role/list",$data,true));
        if($this->message)
        {
            $ajax['system_message']=$this->message;
        }
        $ajax['system_page_url']=site_url($this->controller_url);

        $this->jsonReturn($ajax);
    }

    public function system_add_edit($id)
    {
        $data['access_tasks']=$this->sys_user_role_model->get_my_tasks($id);
        $data['role_status']=$this->sys_user_role_model->get_role_status($id);
        $data['title']="Edit User Role";
        $data['id']=$id;
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("sys_user_role/add_edit",$data,true));
        $ajax['system_page_url']=site_url($this->controller_url."/index/edit/".$id);
        $this->jsonReturn($ajax);
    }

    public function system_save()
    {
        $user=User_helper::get_user();
        $tasks=$this->input->post('tasks');
        $user_group_id=$this->input->post('id');

        $time=time();
        $this->db->trans_start();  //DB Transaction Handle START

        foreach($tasks as $task)
        {

            $data=array();
            if(isset($task['view'])&& ($task['view']==1))
            {
                $data['view']=1;
            }
            else
            {
                $data['view']=0;
            }
            if(isset($task['add'])&& ($task['add']==1))
            {
                $data['add']=1;
            }
            else
            {
                $data['add']=0;
            }
            if(isset($task['edit'])&& ($task['edit']==1))
            {
                $data['edit']=1;
            }
            else
            {
                $data['edit']=0;
            }
            if(isset($task['delete'])&& ($task['delete']==1))
            {
                $data['delete']=1;
            }
            else
            {
                $data['delete']=0;
            }
            if(isset($task['report'])&& ($task['report']==1))
            {
                $data['report']=1;
            }
            else
            {
                $data['report']=0;
            }
            if(isset($task['print'])&& ($task['print']==1))
            {
                $data['print']=1;
            }
            else
            {
                $data['print']=0;
            }
            if(($data['add'])||($data['edit'])||($data['delete'])||($data['report'])||($data['print']))
            {
                $data['view']=1;
            }
            if($task['ugr_id']>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                Query_helper::update($this->config->item('table_user_group_role'),$data,array("id = ".$task['ugr_id']));
            }
            else
            {
                $data['user_group_id']=$user_group_id;
                $data['task_id']=$task['task_id'];
                $data['created_by']=$user->user_id;
                $data['creation_date']=$time;
                Query_helper::add($this->config->item('table_user_group_role'),$data);
            }

        }
        $this->db->trans_complete();   //DB Transaction Handle END

        if ($this->db->trans_status() === TRUE)
        {
            $this->message=$this->lang->line("MSG_ROLE_ASSIGN_SUCCESS");
            $this->system_list();
        }
        else
        {
            $ajax['status']=false;
            $ajax['desk_message']=$this->lang->line("MSG_ROLE_ASSIGN_FAIL");
            $this->jsonReturn($ajax);
        }
    }

}
