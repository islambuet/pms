<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_module_task extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Sys_module_task');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='sys_module_task';
        $this->load->model("sys_module_task_model");
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
        $data['title']="Module and Task";
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['modules_tasks']=$this->sys_module_task_model->get_modules_tasks_table_tree();

            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("sys_module_task/list",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
        /**/

    }

    public function system_add_edit($id)
    {
        if ($id != 0)
        {
            $data['module_task'] = $this->sys_module_task_model->get_module_task_info($id);
            $data['title']='Edit '.$data['module_task']['name'];
            $ajax['system_page_url']=site_url($this->controller_url."/index/edit/".$id);
        }
        else
        {
            $data['title']="Create New Module/Task";
            $data["module_task"] = Array(
                'id' => 0,
                'name' => '',
                'type' => '',
                'parent' => 0,
                'controller' => '',
                'ordering' => 99,
                'icon' => 'menu.png',
                'status' => $this->config->item('pms_status_active')
            );
            $ajax['system_page_url']=site_url($this->controller_url."/index/add");
        }

        //$data['crops'] = System_helper::get_ordered_crops();
        $data["modules"] = $this->sys_module_task_model->get_modules();
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("sys_module_task/add_edit",$data,true));

        $this->jsonReturn($ajax);
    }

    public function system_save()
    {
        $id = $this->input->post("id");
        $data=$this->input->post('task');
        $user = User_helper::get_user();
        if($id>0)
        {
            $this->db->trans_start();  //DB Transaction Handle START

            $data['modified_by'] = $user->user_id;
            $data['modification_date'] = time();

            Query_helper::update($this->config->item('table_task'),$data,array("id = ".$id));

            $this->db->trans_complete();   //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_UPDATE_SUCCESS");
                $this->system_list();
            }
            else
            {
                $this->message=$this->lang->line("MSG_NOT_UPDATED_SUCCESS");
            }
        }
        else
        {
            $this->db->trans_start();  //DB Transaction Handle START

            $data['created_by'] = $user->user_id;
            $data['creation_date'] = time();

            Query_helper::add($this->config->item('table_task'),$data);

            $this->db->trans_complete();   //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_CREATE_SUCCESS");
                $this->system_list();
            }
            else
            {
                $this->message=$this->lang->line("MSG_NOT_SAVED_SUCCESS");
            }
        }
    }

}
