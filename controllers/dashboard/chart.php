<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends Custom_Controller {

    public $data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->model('chart_model','','TRUE');
    }

    public function show($data = [])
    {
        $data['table_data'] = $this->chart_model->findAll();
        $data['type_list']  = $this->chart_model->type_list();
        
        $this->render_page('dashboard/chart_details', $data);
    }

    public function save()
    {
        
        $settings_array = $this->chart_model->posted_data['settings'];

        $this->chart_model->posted_data['settings'] = $this->chart_model->ini_encode($settings_array);

        $insert = $this->chart_model->insert();

        if($insert)
        {
            redirect(base_url().'dashboard/chart/show');
        }
    }

    public function edit($id)
    {
        $data = $this->chart_model->findById(['chart_blocks_id' => $id]);
        
        //json_encode edit settings
        $settings = $this->chart_model->process_settings_data(parse_ini_string($data['settings']));
        $data['settings'] = json_encode($settings);

        //dd($data['settings'], true);
        $this->show($data);
    }

    public function update($id)
    {
        $settings_array = $this->chart_model->posted_data['settings'];
        
        $this->chart_model->posted_data['settings'] = $this->chart_model->ini_encode($settings_array);

        $update = $this->chart_model->update(['chart_blocks_id' => $id]);
        
        if($update)
        {
            redirect(base_url().'dashboard/chart/show');
        }
    }

    public function delete($id)
    {
        $delete = $this->chart_model->deleteItem(['chart_blocks_id' => $id]);
        
        if($delete)
        {
            redirect(base_url().'dashboard/chart/show');
        }
    }

    public function get_settings()
    {
        $post = $this->input->post();
        $data = $this->chart_model->settings($post['file_name']);
        echo $data;
    }


    public function load()
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('id');


        if($this->input->post('prepared_data') != '[]')
        {
            $perpared_data = json_decode($this->input->post('prepared_data'), true);
            $data = $this->chart_model->$type($id, $perpared_data);
        }
        else
        {
            $data = $this->chart_model->$type($id);
        }

        $data['settings'] = parse_ini_string($data['settings']);

        $this->load->view('dashboard/chart_prepare/'.$type, $data);
    }    
}
