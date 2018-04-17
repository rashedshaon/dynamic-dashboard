<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: MD RASHED IBNA ANOWAR
 * Date: 8/19/2017
 * Time: 1:20 PM
 */
class Dashboard_model extends Custom_model {

    public $table_name = "dashboard_manage";

    public function __construct()
    {
        parent::__construct();
    }

    public function settings($file_name)
    {
        $path = APPPATH.'views/dashboard/chart_settings/';
        $settings = read_file($path.$file_name.'.js');
        return $settings;
    }

    public function type_list()
    {
        $data      = [];
        $path      = APPPATH.'views/dashboard/chart_settings/';
        $filenames = get_filenames($path);
        foreach($filenames as $filename)
        {
            $key = strstr($filename, '.', true);
            $data[$key] = ucwords(str_replace("_"," ", $key));
        }
        return $data;
    }

    
    public function bar_chart($id)
    {  
        $data       = $this->findById(['chart_blocks_id' => $id]);
        $sql        = $data['sql'];
        $process    = [];
        $series     = [];
        $categories = [];

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);

            foreach($query->result() as $row)
            {
                foreach($row as $key => $value)
                {
                    $process[$key][] = (float) $value;
                }
            }
            
            foreach($process as $key => $value)
            {
                $series[] = [
                    'name' => ucwords(str_replace("_"," ", $key)),
                    'data' => $value
                ];
            }

            $data['series'] = json_encode($series);

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        

        return $data;
    }

    public function line_chart($id)
    {
        $data    = $this->findById(['chart_blocks_id' => $id]);
        $sql     = $data['sql'];
        $process = [];
        $series  = [];

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);

            foreach($query->result() as $row)
            {
                foreach($row as $key => $value)
                {
                    $process[$key][] = (float) $value;
                }
            }
            
            foreach($process as $key => $value)
            {
                $series[] = [
                    'name' => ucwords(str_replace("_"," ", $key)),
                    'data' => $value
                ];
            }

            $data['series'] = json_encode($series);
        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        

        return $data;
    }

    public function pie_chart($id)
    {
        $data        = $this->findById(['chart_blocks_id' => $id]);
        $sql         = $data['sql'];
        $process     = [];
        $series_data = [];

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);

            //Process single row by multi column
            if(count($query->result()) == 1)
            {
                foreach($query->result() as $row)
                {
                    foreach($row as $key => $value)
                    {
                        $series_data[] = [
                            'name' => ucwords(str_replace("_"," ", $key)),
                            'y' => (float) $value
                        ];
                    }
                }
            }
            
            //Process two column data (ex. name, value) by multi row
            if(count($query->result()) > 1)
            {
                foreach($query->result() as $key => $row)
                {
                    $flag = true;
                    foreach($row as  $value)
                    {
                        if($flag)
                        {
                            $flag = false;
                            $series_data[$key]['name'] = $value;
                        }else{
                            $series_data[$key]['y'] = (float) $value;
                        }
                    }
                }
            }

            $data['series_data'] = json_encode($series_data);

        }else{

            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Custom function template.
     * 
     */
    public function custom_data_function($data)
    {
        $process    = [];

        $query = $this->db->query('SELECT SUM(amount) as amount, SUM(cost) as cost FROM `bar_chart2`;');

        foreach($query->result() as $row)
        {
            foreach($row as $key => $value)
            {
                foreach($row as $key => $value)
                {
                    $process[] = $value;
                }
            }
        }
        
        $data['info_summary'] = $process[0];

        return $data;
    }
}