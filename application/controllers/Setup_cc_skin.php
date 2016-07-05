<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_cc_skin extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;

    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_cc_skin');
        $this->controller_url='setup_cc_skin';
    }

    public function index($action="list",$id=0)
    {
        if($action=="list")
        {
            $this->system_list($id);
        }
        elseif($action=="get_items")
        {
            $this->get_items();
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

    private function system_list()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Skin Types List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_skin/list",$data,true));
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
    private function get_items()
    {
        //$this->db->from($this->config->item('table_types').' types');
        $this->db->from($this->config->item('table_cc_skin_types').' stypes');
        $this->db->select('stypes.id id,stypes.name skin_type_name');
        $this->db->select('stypes.remarks remarks,stypes.status status,stypes.ordering ordering');
        $this->db->select('crops.name crop_name');
        $this->db->select('classifications.name classification_name');
        $this->db->select('types.name type_name');
        $this->db->join($this->config->item('table_cc_types').' types','types.id = stypes.type_id','INNER');
        $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
        $this->db->join($this->config->item('table_cc_crops').' crops','crops.id = classifications.crop_id','INNER');

        $this->db->order_by('crops.ordering');
        $this->db->order_by('classifications.ordering');
        $this->db->order_by('types.ordering');
        $this->db->order_by('stypes.ordering');
        $this->db->where('stypes.status !=',$this->config->item('system_status_delete'));
        $classifications=$this->db->get()->result_array();
        $this->jsonReturn($classifications);

    }

    private function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Create New Skin Type";
            $data['skin_type']['id']=0;
            $data['skin_type']['crop_id']=0;
            $data['skin_type']['classification_id']=0;
            $data['skin_type']['type_id']=0;
            $data['skin_type']['name']='';
            $data['skin_type']['remarks']='';
            $data['skin_type']['ordering']=99;
            $data['skin_type']['status']=$this->config->item('system_status_active');
            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=array();
            $data['types']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_skin/add_edit",$data,true));
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
    private function system_edit($id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            if(($this->input->post('id')))
            {
                $skin_id=$this->input->post('id');
            }
            else
            {
                $skin_id=$id;
            }

            //$data['skin_type']=Query_helper::get_info($this->config->item('table_skin_types'),'*',array('id ='.$type_id),1);

            $this->db->from($this->config->item('table_cc_skin_types').' stypes');
            $this->db->select('stypes.*');
            $this->db->select('types.classification_id classification_id');
            $this->db->select('classifications.crop_id crop_id');
            $this->db->join($this->config->item('table_cc_types').' types','types.id = stypes.type_id','INNER');
            $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
            $this->db->where('stypes.id',$skin_id);
            $data['skin_type']=$this->db->get()->row_array();


            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=Query_helper::get_info($this->config->item('table_cc_classifications'),array('id value','name text'),array('crop_id ='.$data['skin_type']['crop_id']),0,0,array('ordering ASC'));
            $data['types']=Query_helper::get_info($this->config->item('table_cc_types'),array('id value','name text'),array('classification_id ='.$data['skin_type']['classification_id']),0,0,array('ordering ASC'));
            $data['title']="Edit Skin Type (".$data['skin_type']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_skin/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$skin_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    private function system_save()
    {
        $user=User_helper::get_user();
        $time=time();
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

            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_cc_skin_types'),$data,array("id = ".$id));
            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_cc_skin_types'),$data);
            }
            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                if($save_and_new==1)
                {
                    $this->system_add();
                }
                else
                {
                    $this->system_list();
                }
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
        $this->form_validation->set_rules('skin_type[type_id]',$this->lang->line('LABEL_TYPE_NAME'),'required');
        $this->form_validation->set_rules('skin_type[name]',$this->lang->line('LABEL_SKIN_TYPE_NAME'),'required');


        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
