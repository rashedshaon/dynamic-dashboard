<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: MD RASHED IBNA ANOWAR
 * Date: 8/19/2017
 * Time: 1:20 PM
 */
class Infobox_model extends Custom_model {

    public $table_name = "dashboard_info_blocks";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process settings string data line by line.
     * 
     */
    public function process_settings_data($settings_array)
    {
        $settings = [];
        foreach($settings_array as $key => $value)
        {

            $settings[] = [
                "label" => ucwords(str_replace("_"," ", $key)),
                "key" => $key,
                "value" => htmlspecialchars ($value, ENT_QUOTES, 'UTF-8'),
            ];
        }

        return $settings;
    }

    public function ini_encode($array)
    {
        $ini = '';

        foreach($array as $key => $value)
        {
            $ini .= $key." = '".$value."'". PHP_EOL;
        }

        return $ini;
    }


    /**
     * Retrive infobox settings as type.
     * 
     */
    public function settings($file_name)
    {
        $path = APPPATH.'views/dashboard/infobox_settings/';
        $settings = parse_ini_file($path.$file_name.'.ini', true);

        $settings = $this->process_settings_data($settings);

        return json_encode($settings);
    }

    /**
     * Retrive infobox type list.
     * 
     */
    public function type_list()
    {
        $data = [];
        $path = APPPATH.'views/dashboard/infobox_settings/';
        $filenames = get_filenames($path);
        foreach($filenames as $filename)
        {
            $key = strstr($filename, '.', true);
            $data[$key] = ucwords(str_replace("_"," ", $key));
        }
        return $data;
    }

    /**
     * Consider a first colunm as bar infobox summary value.
     * 
     */
    public function infobox_one($id, $perpared_data = '')
    {  
        $data = $this->findById(['info_blocks_id' => $id]);

        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            return $this->process_by_value($data, $perpared_data);
        }

        $sql = $data['sql'];
     
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            $query_data = $query->result();
            
            return $this->process_by_value($data, $query_data);

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Consider a first colunm as line infobox summary value.
     * 
     */
    public function infobox_two($id, $perpared_data = '')
    {  
        $data = $this->findById(['info_blocks_id' => $id]);
        
        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            return $this->process_by_value($data, $query_data);
        }

        $sql = $data['sql'];
        
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            $query_data = $query->result();
            
            return $this->process_by_value($data, $query_data);

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Column data will be Line data source and column name will be Line name.
     */
    public function infobox_three($id, $perpared_data = '')
    {
        $data = $this->findById(['info_blocks_id' => $id]);

        /**
         * Return Data From perpared_data.
         */
        if(is_array($perpared_data))
        {
            $data['series'] = json_encode($perpared_data['series']);
            return $data;
        }

        $sql = $data['sql'];
        
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            $query_data = $query->result();
            
            return $this->process_by_column($data, $query_data);
        
        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }


    /**
     * Consider a first colunm as infobox summary value.
     * Next Column value will be consider to calculate percent.
     */
    public function process_by_column($infobox_data, $query_data)
    {
        $process    = [];

        foreach($query_data as $row)
        {
            foreach($row as $key => $value)
            {
                $process[] = $value;
            }
        }
        
    
        $infobox_data['info_summary'] = $process[0];
        
        //Define Calculation part here.
        $infobox_data['info_percent'] = round(($process[0] * 100) / $process[1]);

        return $infobox_data;
    }

    /**
     * Consider a first colunm as infobox summary value.
     * 
     */
    public function process_by_value($infobox_data, $query_data)
    {
        $process    = [];

        foreach($query_data as $row)
        {
            foreach($row as $key => $value)
            {
                $process[] = $value;
            }
        }
    
        $infobox_data['info_summary'] = $process[0];
        
        return $infobox_data;
    }
    

    
}