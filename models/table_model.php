<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: MD RASHED IBNA ANOWAR
 * Date: 8/19/2017
 * Time: 1:20 PM
 */
class Table_model extends Custom_model {

    public $table_name = "dashboard_table_blocks";

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
     * Retrive table settings as type.
     * 
     */
    public function settings($file_name)
    {
        $path = APPPATH.'views/dashboard/table_settings/';
        $settings = parse_ini_file($path.$file_name.'.ini', true);

        $settings = $this->process_settings_data($settings);

        return json_encode($settings);
    }

    /**
     * Retrive table type list.
     * 
     */
    public function type_list()
    {
        $data = [];
        $path = APPPATH.'views/dashboard/table_settings/';
        $filenames = get_filenames($path);
        foreach($filenames as $filename)
        {
            $key = strstr($filename, '.', true);
            $data[$key] = ucwords(str_replace("_"," ", $key));
        }
        return $data;
    }

    /**
     * Consider a first colunm as bar table summary value.
     * 
     */
    public function table_one($id, $perpared_data = '')
    {  
        $data = $this->findById(['table_blocks_id' => $id]);

        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            $data['table_data'] = $perpared_data;
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
            
            $data['table_data'] = $query_data;
            return $data;

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Consider a first colunm as bar table summary value.
     * 
     */
    public function table_two($id, $perpared_data = '')
    {  
        $data = $this->findById(['table_blocks_id' => $id]);

        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            $data['table_data'] = $perpared_data;
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
            
            $data['table_data'] = $query_data;
            return $data;

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }
}