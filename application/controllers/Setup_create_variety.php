<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_create_variety extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_create_variety');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_create_variety';
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
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_variety/list",$data,true));
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
            $data['title']="Create variety";
            $data['variety']['id']=0;
            $data['variety']['crop_id']=0;
            $data['variety']['classification_id']=0;
            $data['variety']['type_id']=0;
            $data['variety']['skin_type_id']='';
            $data['variety']['variety_name']='';
            $data['variety']['remarks']='';
            $data['variety']['ordering']=99;
            $data['variety']['status']=$this->config->item('system_status_active');
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['classifications']=array();
            $data['types']=array();
            $data['skin_types']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_variety/add_edit",$data,true));
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
                $variety_id=$this->input->post('id');
            }
            else
            {
                $variety_id=$id;
            }

            $data['variety']=Query_helper::get_info($this->config->item('table_varieties'),'*',array('id ='.$variety_id),1);
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['classifications']=Query_helper::get_info($this->config->item('table_classifications'),array('id','classification_name'),array('crop_id ='.$data['variety']['crop_id']));
            $data['types']=Query_helper::get_info($this->config->item('table_types'),array('id','type_name'),array('classification_id ='.$data['variety']['classification_id']));
            $data['skin_types']=Query_helper::get_info($this->config->item('table_skin_types'),array('id','skin_type_name'),array('type_id ='.$data['variety']['type_id']));
            $data['title']="Edit Variety (".$data['variety']['variety_name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_create_variety/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$variety_id);
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
            $data = $this->input->post('variety');
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_varieties'),$data,array("id = ".$id));
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
                Query_helper::add($this->config->item('table_varieties'),$data);
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
        $this->form_validation->set_rules('variety[crop_id]',$this->lang->line('LABEL_CROP_NAME'),'required');
        $this->form_validation->set_rules('variety[classification_id]',$this->lang->line('LABEL_CLASSIFICATION_NAME'),'required');
        $this->form_validation->set_rules('variety[type_id]',$this->lang->line('LABEL_TYPE_NAME'),'required');
        $this->form_validation->set_rules('variety[skin_type_id]',$this->lang->line('LABEL_SKIN_TYPE_NAME'),'required');
        $this->form_validation->set_rules('variety[variety_name]',$this->lang->line('LABEL_VARIETY_NAME'),'required');


        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
    public function get_crops()
    {
        //$this->db->from($this->config->item('table_skin_types').' stypes');
        $this->db->from($this->config->item('table_varieties').' varieties');
        $this->db->select('varieties.id id,varieties.variety_name variety_name,varieties.unit_price');
        $this->db->select('varieties.remarks remarks,varieties.status status,varieties.ordering ordering');
        $this->db->select('crops.crop_name crop_name');
        $this->db->select('classifications.classification_name classification_name');
        $this->db->select('types.type_name type_name');
        $this->db->select('stypes.skin_type_name skin_type_name');
        $this->db->join($this->config->item('table_crops').' crops','crops.id = varieties.crop_id','INNER');
        $this->db->join($this->config->item('table_classifications').' classifications','classifications.id = varieties.classification_id','INNER');
        $this->db->join($this->config->item('table_types').' types','types.id =varieties.type_id','INNER');
        $this->db->join($this->config->item('table_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->where('varieties.status !=',$this->config->item('system_status_delete'));
        $varieties=$this->db->get()->result_array();
        $this->jsonReturn($varieties);

    }

}
