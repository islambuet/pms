<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_cc_variety_price extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_cc_variety_price');
        $this->controller_url='setup_cc_variety_price';
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
        elseif($action=="load_price")
        {
            $this->system_load_price();
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
            $data['title']="Variety Price List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety_price/list",$data,true));
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
        $this->db->from($this->config->item('table_cc_variety_price').' vp');
        $this->db->select('vp.id id,vp.year,vp.unit_price');
        $this->db->select('varieties.name variety_name');
        $this->db->select('crops.name crop_name');
        $this->db->select('classifications.name classification_name');
        $this->db->select('types.name type_name');
        $this->db->select('stypes.name skin_type_name');
        $this->db->join($this->config->item('table_cc_varieties').' varieties','varieties.id =vp.variety_id','INNER');
        $this->db->join($this->config->item('table_cc_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
        $this->db->join($this->config->item('table_cc_types').' types','types.id =stypes.type_id','INNER');
        $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
        $this->db->join($this->config->item('table_cc_crops').' crops','crops.id = classifications.crop_id','INNER');
        $this->db->order_by('vp.year DESC');
        $this->db->order_by('crops.ordering');
        $this->db->order_by('classifications.ordering');
        $this->db->order_by('types.ordering');
        $this->db->order_by('stypes.ordering');
        $this->db->order_by('varieties.ordering');
        $this->db->where('varieties.status !=',$this->config->item('system_status_delete'));
        $this->db->where('vp.revision',1);
        $varieties=$this->db->get()->result_array();
        $this->jsonReturn($varieties);

    }
    private function system_add()
    {
        if(isset($this->permissions['action1'])&&($this->permissions['action1']==1))
        {
            $data['title']="Set Variety Price";
            $data['price']['year']=date('Y');
            $data['price']['crop_id']='';
            $data['price']['classification_id']='';
            $data['price']['type_id']='';
            $data['price']['skin_type_id']='';
            $data['price']['variety_id']='';
            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=array();
            $data['types']=array();
            $data['skin_types']=array();
            $data['varieties']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety_price/search",$data,true));
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
                $price_id=$this->input->post('id');
            }
            else
            {
                $price_id=$id;
            }

            $data['title']="Set Variety Price";
            $this->db->from($this->config->item('table_cc_variety_price').' vp');
            //$this->db->from($this->config->item('table_cc_varieties').' varieties');
            $this->db->select('vp.*');
            $this->db->select('stypes.id skin_type_id');
            $this->db->select('types.id type_id');
            $this->db->select('classifications.id classification_id');
            $this->db->select('classifications.crop_id crop_id');

            $this->db->join($this->config->item('table_cc_varieties').' varieties','varieties.id =vp.variety_id','INNER');
            $this->db->join($this->config->item('table_cc_skin_types').' stypes','stypes.id =varieties.skin_type_id','INNER');
            $this->db->join($this->config->item('table_cc_types').' types','types.id =stypes.type_id','INNER');
            $this->db->join($this->config->item('table_cc_classifications').' classifications','classifications.id = types.classification_id','INNER');
            $this->db->where('vp.id',$price_id);
            $data['price']=$this->db->get()->row_array();
            $data['crops']=Query_helper::get_info($this->config->item('table_cc_crops'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['classifications']=Query_helper::get_info($this->config->item('table_cc_classifications'),array('id value','name text'),array('crop_id ='.$data['price']['crop_id']),0,0,array('ordering ASC'));
            $data['types']=Query_helper::get_info($this->config->item('table_cc_types'),array('id value','name text'),array('classification_id ='.$data['price']['classification_id']),0,0,array('ordering ASC'));
            $data['skin_types']=Query_helper::get_info($this->config->item('table_cc_skin_types'),array('id value','name text'),array('type_id ='.$data['price']['type_id']),0,0,array('ordering ASC'));
            $data['varieties']=Query_helper::get_info($this->config->item('table_cc_varieties'),array('id value','name text'),array('skin_type_id ='.$data['price']['skin_type_id']),0,0,array('ordering ASC'));
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cc_variety_price/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$price_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    private function system_load_price()
    {
        if(isset($this->permissions['action2'])&&($this->permissions['action2']==1))
        {
            $variety_id=$this->input->post('variety_id');
            $year=$this->input->post('year');
            $variety_price=Query_helper::get_info($this->config->item('table_cc_variety_price'),'*',array('variety_id ='.$variety_id,'year ='.$year,'revision =1'),1);
            if($variety_price)
            {
                $data['title']='Edit price';
                $data['variety_price']=$variety_price;
            }
            else
            {
                $data['title']='New price';
                $data['variety_price']['variety_id']=$variety_id;
                $data['variety_price']['year']=$year;
                $data['variety_price']['unit_price']=0;
                $data['variety_price']['remarks']='';
            }


            //$data['variety_price']=


            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("setup_cc_variety_price/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }


            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    private function system_save()
    {
        $user=User_helper::get_user();
        $time=time();
        if(!(isset($this->permissions['action1'])&&($this->permissions['action1']==1)))
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

            $data['user_created'] = $user->user_id;
            $data['date_created'] = $time;
            $data['revision'] = 1;

            $this->db->trans_start();  //DB Transaction Handle START
            $this->db->where('year',$data['year']);
            $this->db->where('variety_id',$data['variety_id']);
            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_cc_variety_price'));
            Query_helper::add($this->config->item('table_cc_variety_price'),$data);

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
        $this->form_validation->set_rules('variety_price[unit_price]',$this->lang->line('LABEL_UNIT_PRICE'),'required|numeric');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
}
