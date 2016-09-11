<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_cc_variety extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_cc_variety');
        $this->controller_url='setup_cc_variety';
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

        if(isset($this->permissions['action0'])&&($this->permissions['action0']==1))
        {
            $data['title']="Skin Types List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety/list",$data,true));
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
        //$this->db->from($this->config->item('table_skin_types').' stypes');
        $this->db->from($this->config->item('table_cc_varieties').' varieties');
        $this->db->select('varieties.id id,varieties.name variety_name');
        $this->db->select('varieties.remarks remarks,varieties.status status,varieties.ordering ordering');
        $this->db->select('crops.name crop_name');
        $this->db->select('classifications.name classification_name');
        $this->db->select('types.name type_name');
        $this->db->select('stypes.name skin_type_name');
        $this->db->join($this->config->item('table_cc_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($this->config->item('table_cc_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
        $this->db->join($this->config->item('table_cc_crops').' crops','crops.id = classifications.crop_id','INNER');
        $this->db->order_by('crops.ordering');
        $this->db->order_by('classifications.ordering');
        $this->db->order_by('types.ordering');
        $this->db->order_by('stypes.ordering');
        $this->db->order_by('varieties.ordering');
        $this->db->where('varieties.status !=',$this->config->item('system_status_delete'));
        $varieties=$this->db->get()->result_array();
        $this->jsonReturn($varieties);

    }

    private function system_add()
    {
        if(isset($this->permissions['action1'])&&($this->permissions['action1']==1))
        {
            $data['title']="Create variety";
            $data['variety']['id']=0;
            $data['variety']['crop_id']=0;
            $data['variety']['classification_id']=0;
            $data['variety']['type_id']=0;
            $data['variety']['skin_type_id']='';
            $data['variety']['name']='';
            $data['variety']['remarks']='';
            $data['variety']['ordering']=99;
            $data['variety']['status']=$this->config->item('system_status_active');
            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=array();
            $data['types']=array();
            $data['skin_types']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety/add_edit",$data,true));
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
        if(isset($this->permissions['action2'])&&($this->permissions['action2']==1))
        {
            if(($this->input->post('id')))
            {
                $variety_id=$this->input->post('id');
            }
            else
            {
                $variety_id=$id;
            }
            $this->db->from($this->config->item('table_cc_varieties').' varieties');
            $this->db->select('varieties.*');
            $this->db->select('stypes.type_id type_id');
            $this->db->select('types.classification_id classification_id');
            $this->db->select('classifications.crop_id crop_id');


            $this->db->join($this->config->item('table_cc_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
            $this->db->join($this->config->item('table_cc_types').' types','types.id =stypes.type_id','INNER');
            $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
            $this->db->join($this->config->item('table_cc_crops').' crops','crops.id = classifications.crop_id','INNER');

            $this->db->where('varieties.id',$variety_id);
            $data['variety']=$this->db->get()->row_array();

            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=Query_helper::get_info($this->config->item('table_cc_classifications'),array('id value','name text'),array('crop_id ='.$data['variety']['crop_id']),0,0,array('ordering ASC'));
            $data['types']=Query_helper::get_info($this->config->item('table_cc_types'),array('id value','name text'),array('classification_id ='.$data['variety']['classification_id']),0,0,array('ordering ASC'));
            $data['skin_types']=Query_helper::get_info($this->config->item('table_cc_skin_types'),array('id value','name text'),array('type_id ='.$data['variety']['type_id']),0,0,array('ordering ASC'));
            $data['title']="Edit Variety (".$data['variety']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety/add_edit",$data,true));
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

    private function system_save()
    {
        $user=User_helper::get_user();
        $time=time();
        $id = $this->input->post("id");
        if($id>0)
        {
            if(!(isset($this->permissions['action2'])&&($this->permissions['action2']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['action1'])&&($this->permissions['action1']==1)))
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

            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_cc_varieties'),$data,array("id = ".$id));
            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_cc_varieties'),$data);
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
        $this->form_validation->set_rules('variety[skin_type_id]',$this->lang->line('LABEL_SKIN_TYPE_NAME'),'required');
        $this->form_validation->set_rules('variety[name]',$this->lang->line('LABEL_VARIETY_NAME'),'required');


        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
