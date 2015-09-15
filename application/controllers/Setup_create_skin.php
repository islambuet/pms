<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_create_skin extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_create_skin');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_create_skin';
        //$this->load->model("sys_user_role_model");
    }

    public function index($action="list",$id=0)
    {
        if($action=="list")
        {
            $this->system_list($id);
        }
        elseif($action=="add")
        {
            $this->system_add();
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
            $this->system_list($id);
        }
    }

    public function system_list()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Skin Types List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_skin/list",$data,true));
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
    }

    public function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Create New Skin Type";
            $data['skin_type']['id']=0;
            $data['skin_type']['crop_id']=0;
            $data['skin_type']['classification_id']=0;
            $data['skin_type']['type_id']=0;
            $data['skin_type']['skin_type_name']='';
            $data['skin_type']['remarks']='';
            $data['skin_type']['ordering']=99;
            $data['skin_type']['status']=$this->config->item('system_status_active');
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['classifications']=array();
            $data['types']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_skin/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/add');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_edit($id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            if(($this->input->post('id')))
            {
                $type_id=$this->input->post('id');
            }
            else
            {
                $type_id=$id;
            }

            $data['skin_type']=Query_helper::get_info($this->config->item('table_skin_types'),'*',array('id ='.$type_id),1);
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['classifications']=Query_helper::get_info($this->config->item('table_classifications'),array('id','classification_name'),array('crop_id ='.$data['skin_type']['crop_id']));
            $data['types']=Query_helper::get_info($this->config->item('table_types'),array('id','type_name'),array('classification_id ='.$data['skin_type']['classification_id']));
            $data['title']="Edit Skin Type (".$data['skin_type']['skin_type_name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_skin/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$type_id);
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
        $id = $this->input->post("id");
        if($id>0)
        {
            if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['add'])&&($this->permissions['add']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();

            }
        }
        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $data = $this->input->post('skin_type');
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_skin_types'),$data,array("id = ".$id));
                $this->db->trans_complete();   //DB Transaction Handle END

                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_list();
                }
                else
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                    $this->jsonReturn($ajax);

                }
            }
            else
            {
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::add($this->config->item('table_skin_types'),$data);
                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_list();
                }
                else
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                    $this->jsonReturn($ajax);
                }
            }
        }
    }
    private function check_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('skin_type[crop_id]',$this->lang->line('LABEL_CROP_NAME'),'required');
        $this->form_validation->set_rules('skin_type[classification_id]',$this->lang->line('LABEL_CLASSIFICATION_NAME'),'required');
        $this->form_validation->set_rules('skin_type[type_id]',$this->lang->line('LABEL_TYPE_NAME'),'required');
        $this->form_validation->set_rules('skin_type[skin_type_name]',$this->lang->line('LABEL_SKIN_TYPE_NAME'),'required');


        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
    public function get_crops()
    {
        //$this->db->from($this->config->item('table_types').' types');
        $this->db->from($this->config->item('table_skin_types').' stypes');
        $this->db->select('stypes.id id,stypes.skin_type_name skin_type_name');
        $this->db->select('stypes.remarks remarks,stypes.status status,stypes.ordering ordering');
        $this->db->select('crops.crop_name crop_name');
        $this->db->select('classifications.classification_name classification_name');
        $this->db->select('types.type_name type_name');
        $this->db->join($this->config->item('table_crops').' crops','crops.id = stypes.crop_id','INNER');
        $this->db->join($this->config->item('table_classifications').' classifications','classifications.id = stypes.classification_id','INNER');
        $this->db->join($this->config->item('table_types').' types','types.id = stypes.type_id','INNER');
        $this->db->where('stypes.status !=',$this->config->item('system_status_delete'));
        $classifications=$this->db->get()->result_array();
        $this->jsonReturn($classifications);

    }

}
