<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Infobox extends Custom_Controller {

    public $data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->model('infobox_model','','TRUE');
    }
    
    public function show($data = [])
    {
        $data['table_data'] = $this->infobox_model->findAll();
        $data['type_list']  = $this->infobox_model->type_list();
        
        $this->render_page('dashboard/infobox_details', $data);
    }

    public function save()
    {
        
        $settings_array = $this->infobox_model->posted_data['settings'];

        $this->infobox_model->posted_data['settings'] = $this->infobox_model->ini_encode($settings_array);

        $insert = $this->infobox_model->insert();

        if($insert)
        {
            redirect(base_url().'dashboard/infobox/show');
        }
    }

    public function edit($id)
    {
        $data = $this->infobox_model->findById(['info_blocks_id' => $id]);
        
        //json_encode edit settings
        $settings = $this->infobox_model->process_settings_data(parse_ini_string($data['settings']));
        $data['settings'] = json_encode($settings);

        $this->show($data);
    }

    public function update($id)
    {
        $settings_array = $this->infobox_model->posted_data['settings'];
        
        $this->infobox_model->posted_data['settings'] = $this->infobox_model->ini_encode($settings_array);

        $update = $this->infobox_model->update(['info_blocks_id' => $id]);
        
        if($update)
        {
            redirect(base_url().'dashboard/infobox/show');
        }
    }

    public function delete($id)
    {
        $delete = $this->infobox_model->deleteItem(['info_blocks_id' => $id]);
        
        if($delete)
        {
            redirect(base_url().'dashboard/infobox/show');
        }
    }

    public function get_settings()
    {
        $post = $this->input->post();
        $data = $this->infobox_model->settings($post['file_name']);
        echo $data;
    }

    public function preview()
    {
        $type = $this->input->post('file_name');
        $this->load->view('dashboard/infobox_prepare/'.$type);
    }

    public function load()
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('id');


        if($this->input->post('prepared_data') != '[]')
        {
            $perpared_data = json_decode($this->input->post('prepared_data'), true);
            $data = $this->infobox_model->$type($id, $perpared_data);
        }
        else
        {
            $data = $this->infobox_model->$type($id);
        }

        $data['settings'] = parse_ini_string($data['settings']);

        $this->load->view('dashboard/infobox_prepare/'.$type, $data);
    }
    
}
