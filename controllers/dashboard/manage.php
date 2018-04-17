<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends Custom_Controller {

    public $data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard_model','','TRUE');
        $this->load->model('chart_model','','TRUE');
        $this->load->model('infobox_model','','TRUE');
        $this->load->model('table_model','','TRUE');
    }

    public function view($id)
    {
        $data = $this->dashboard_model->findById(['dashboard_manage_id' => $id]);
        $this->render_page('dashboard/dashboard_show', $data);
    }

    public function show($data = [])
    {
        $data['dashboard_data'] = $this->dashboard_model->findAll();
        $data['chart_data'] = $this->chart_model->findAll();
        $data['infobox_data'] = $this->infobox_model->findAll();
        $data['table_data'] = $this->table_model->findAll();

    
        $this->render_page('dashboard/dashboard_details', $data);
    }

    public function save()
    {

        $insert = $this->dashboard_model->insert();

        if($insert)
        {
            redirect(base_url().'dashboard/manage/show');
        }
    }

    public function edit($id)
    {
        $data = $this->dashboard_model->findById(['dashboard_manage_id' => $id]);
        $this->show($data);
    }

    public function update($id)
    {
        $update = $this->dashboard_model->update(['dashboard_manage_id' => $id]);
        
        if($update)
        {
            redirect(base_url().'dashboard/manage/show');
        }
    }

    public function delete($id)
    {
        $delete = $this->dashboard_model->deleteItem(['dashboard_manage_id' => $id]);
        
        if($delete)
        {
            redirect(base_url().'dashboard/manage/show');
        }
    }

    public function get_settings()
    {
        $post = $this->input->post();
        $data = $this->dashboard_model->settings($post['file_name']);
        echo $data;
    }


    public function load()
    {
        $type = $this->input->post('type');
        $id = $this->input->post('id');

        $data = $this->dashboard_model->$type($id);
        $this->load->view('dashboard/chart_prepare/'.$type, $data);
    }

    public function sql()
    {
        $sql = 'SELECT dashboard_manage_id, dashboard_manage_name FROM dashboard_dashboard_manage';
        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            foreach($query->result() as $data)
            {
                foreach($data as $key => $value)
                {
                    $process[$key][] = $value;
                }
            }
        }else{
            $error = $this->db->error();
        }
    }
    
}
