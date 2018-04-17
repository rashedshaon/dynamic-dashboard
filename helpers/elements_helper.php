<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('__chart')){
    function __chart($slug = '', $prepared_data = array())
    {
        $slug_data = explode('-', $slug);
        $type = $slug_data[0];
        $id   = $slug_data[1];

        $prepared_data = json_encode($prepared_data);

        echo '<div id="'.$slug.'"></div>';
        
        $scripts = '<script>
                        $(document).ready(function(){
                            $.ajax({
                                url: "'.base_url('dashboard/chart/load').'",
                                type: "post",
                                data:{type: "'.$type.'", id: "'.$id.'", prepared_data: \''.$prepared_data.'\' },
                                success: function(data){
                                    eval(data);
                                    Highcharts.chart("'.$slug.'", chart_data);
                                }
                            });
                        });
                    </script>';
        echo $scripts;
    }
}

if(!function_exists('__infobox')){
    function __infobox($slug = '', $prepared_data = array())
    {
        $slug_data = explode('-', $slug);
        $type = $slug_data[0];
        $id   = $slug_data[1];

        $prepared_data = json_encode($prepared_data);

        echo '<div id="'.$slug.'"></div>';
        
        $scripts = '<script>
                        $(document).ready(function(){
                            $.ajax({
                                url: "'.base_url('dashboard/infobox/load').'",
                                type: "post",
                                data:{type: "'.$type.'", id: "'.$id.'", prepared_data: \''.$prepared_data.'\' },
                                success: function(data){
                                    $("#'.$slug.'").html(data);
                                }
                            });
                        });
                    </script>';
        echo $scripts;
    }
}

if(!function_exists('__table')){
    function __table($slug = '', $prepared_data = array())
    {
        $slug_data = explode('-', $slug);
        $type = $slug_data[0];
        $id   = $slug_data[1];

        $prepared_data = json_encode($prepared_data);

        echo '<div id="'.$slug.'"></div>';
        
        $scripts = '<script>
                        $(document).ready(function(){
                            $.ajax({
                                url: "'.base_url('dashboard/table/load').'",
                                type: "post",
                                data:{type: "'.$type.'", id: "'.$id.'", prepared_data: \''.$prepared_data.'\' },
                                success: function(data){
                                    $("#'.$slug.'").html(data);
                                }
                            });
                        });
                    </script>';
        echo $scripts;
    }
}