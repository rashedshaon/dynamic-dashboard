<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Table extends Custom_Controller {

    public $data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->model('table_model','','TRUE');
    }
    

    public function show($data = [])
    {
        $data['table_data'] = $this->table_model->findAll();
        $data['type_list']  = $this->table_model->type_list();
        
        $this->render_page('dashboard/table_details', $data);
    }

    public function save()
    {
        
        $settings_array = $this->table_model->posted_data['settings'];

        $this->table_model->posted_data['settings'] = $this->table_model->ini_encode($settings_array);

        $insert = $this->table_model->insert();

        if($insert)
        {
            redirect(base_url().'dashboard/table/show');
        }
    }

    public function edit($id)
    {
        $data = $this->table_model->findById(['table_blocks_id' => $id]);
        
        //json_encode edit settings
        $settings = $this->table_model->process_settings_data(parse_ini_string($data['settings']));
        $data['settings'] = json_encode($settings);

        $this->show($data);
    }

    public function update($id)
    {
        $settings_array = $this->table_model->posted_data['settings'];
        
        $this->table_model->posted_data['settings'] = $this->table_model->ini_encode($settings_array);

        $update = $this->table_model->update(['table_blocks_id' => $id]);
        
        if($update)
        {
            redirect(base_url().'dashboard/table/show');
        }
    }

    public function delete($id)
    {
        $delete = $this->table_model->deleteItem(['table_blocks_id' => $id]);
        
        if($delete)
        {
            redirect(base_url().'dashboard/table/show');
        }
    }

    public function get_settings()
    {
        $post = $this->input->post();
        $data = $this->table_model->settings($post['file_name']);
        echo $data;
    }

    public function preview()
    {
        $type = $this->input->post('file_name');
        $this->load->view('dashboard/table_prepare/'.$type);
    }

    public function load()
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('id');


        if($this->input->post('prepared_data') != '[]')
        {
            $perpared_data = json_decode($this->input->post('prepared_data'), true);
            $data = $this->table_model->$type($id, $perpared_data);
        }
        else
        {
            $data = $this->table_model->$type($id);
        }

        $data['settings'] = parse_ini_string($data['settings']);

        $this->load->view('dashboard/table_prepare/'.$type, $data);
    }  
}
