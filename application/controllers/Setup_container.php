<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_container extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    private $selected_variety_ids=array();
    //this variable is set before save with selected variety ids
    //used to get price
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_container');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_container';
        //$this->load->model("booking_preliminary_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search($id);
        }
        elseif($action=="list")
        {
            $this->system_list();
        }
        elseif($action=="add")
        {
            $this->system_add($id);
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
            $this->system_search($id);
        }
    }
    public function system_search($consignment_id)
    {
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $ajax['status']=true;
            $data['title']="Container Management";
            $data['consignment_id']=$consignment_id;
            $data['year']=Date('Y');
            if($consignment_id>0)
            {
                $consignment_info=Query_helper::get_info($this->config->item('table_consignment'),'*',array('id ='.$consignment_id),1);
                if($consignment_info)
                {
                    $data['year']=$consignment_info['year'];
                }
            }
            $data['consignments']=Query_helper::get_info($this->config->item('table_consignment'),array('id','consignment_name'),array('year ='.$data['year'],'status ="'.$this->config->item('system_status_active').'"'));
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_container/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search/'.$consignment_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_list()
    {
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $consignment_id=$this->input->post('consignment_id');
            $data['containers']=Query_helper::get_info($this->config->item('table_container'),'*',array('consignment_id ='.$consignment_id));
            $ajax['status']=true;
            $data['title']="Containers";
            $data['consignment_id']=$consignment_id;
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("setup_container/list",$data,true));
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search/'.$consignment_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_add($consignment_id)
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {

            $ajax['status']=true;
            $data['title']="New Container";
            $data['container']['id']=0;
            $data['container']['consignment_id']=$consignment_id;
            $data['container']['container_name']='';
            $data['container']['remarks']='';
            $data['container']['status']=$this->config->item('system_status_active');

            $data['container_varieties']=array(array('variety_id'=>'','quantity'=>''));
            $data['varieties']=System_helper::get_all_varieties_for_dropdown();

            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_container/add_edit",$data,true));
            $ajax['system_page_url']=site_url($this->controller_url.'/index/add/'.$consignment_id);
            $this->jsonReturn($ajax);


        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_edit($container_id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $container=Query_helper::get_info($this->config->item('table_container'),'*',array('id ='.$container_id),1);
            if(sizeof($container)>0)
            {
                $ajax['status']=true;
                $data['title']="Edit Container";
                $data['container']=$container;

                $data['container_varieties']=Query_helper::get_info($this->config->item('table_container_varieties'),array('variety_id','quantity'),array('container_id ='.$container_id,'revision =1'));

                $data['varieties']=System_helper::get_all_varieties_for_dropdown();

                $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_container/add_edit",$data,true));
                $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$container_id);
                $this->jsonReturn($ajax);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']='Invalid Container';
                $this->jsonReturn($ajax);
            }

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

            $data = $this->input->post('container');

            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_container'),$data,array("id = ".$id));

                $this->db->where('container_id',$id);
                $this->db->set('revision', 'revision+1', FALSE);
                $this->db->update($this->config->item('table_container_varieties'));
                $this->insert_container_varieties($id);

                $this->db->trans_complete();   //DB Transaction Handle END

                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_search($data['consignment_id']);
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
                $container_id=Query_helper::add($this->config->item('table_container'),$data);
                $this->insert_container_varieties($container_id);
                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_search($data['consignment_id']);
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
    private function insert_container_varieties($container_id)
    {
        $booked_varieties=$this->input->post('container_varieties');
        //$variety_prices=$this->booking_preliminary_model->get_variety_prices($this->selected_variety_ids);
        $time=time();
        $user=User_helper::get_user();
        foreach($booked_varieties as $variety)
        {
            $data=array();
            $data['container_id']=$container_id;
            $data['variety_id']=$variety['id'];
            $data['quantity']=$variety['quantity'];
            //$data['unit_price']=$variety_prices[$variety['id']]['unit_price'];
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;
            Query_helper::add($this->config->item('table_container_varieties'),$data);
        }
    }
    private function check_validation()
    {
        $container=$this->input->post('container');
        if(!($container['container_name']))
        {
            $this->message=$this->lang->line("MSG_CONTAINER_NAME_INVALID");
            return false;
        }
        $booked_varieties=$this->input->post('container_varieties');

        if(sizeof($booked_varieties)>0)
        {
            $variety_ids=array();
            foreach($booked_varieties as $variety)
            {
                if(!is_numeric($variety['quantity']))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_MISSING");
                    return false;
                }
                if(!(($variety['quantity'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_INVALID");
                    return false;
                }
                if(!(($variety['id'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_VARIETY_MISSING");
                    return false;
                }
                $variety_ids[]=$variety['id'];

            }
            if(sizeof($variety_ids)!= sizeof(array_unique($variety_ids)))
            {
                $this->message=$this->lang->line("MSG_BOOKING_DUPLICATE_VARIETY");
                return false;
            }
            $this->selected_variety_ids=$variety_ids;
        }
        else
        {
            $this->message=$this->lang->line("MSG_REQUIRED_BOOKING");
            return false;
        }
        return true;
    }
}
