<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_invoice extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_invoice');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_invoice';
        //$this->load->model("sys_user_role_model");
    }

    public function index($action="list",$id=0)
    {
        if($action=="edit")
        {
            $this->system_edit();
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_edit();
        }
    }


    public function system_edit()
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $data['title']='Invoice Setup';
            $data['setup_invoice']=Query_helper::get_info($this->config->item('table_setup_invoice'),'*',array('revision =1'),1);
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_invoice/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/');
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
            $time=time();

            $uploaded_images = System_helper::upload_file('images/invoice_logo');
            if(array_key_exists('image_logo',$uploaded_images))
            {
                $logo_file=$uploaded_images['image_logo']['info']['file_name'];
            }
            else
            {
                $logo_file=$this->input->post('previous_image_logo');
            }
            $this->db->trans_start();  //DB Transaction Handle START

            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_setup_invoice'));

            $data = $this->input->post('setup_invoice');
            $data['logo'] = $logo_file;
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;

            Query_helper::add($this->config->item('table_setup_invoice'),$data);

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
