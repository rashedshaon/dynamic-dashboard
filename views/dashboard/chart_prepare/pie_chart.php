<?php
echo "var pie_settings = {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Browser market shares January, 2015 to May, 2015'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'IE',
            y: 56.33
        }, {
            name: 'Chrome',
            y: 24.03,
            sliced: true,
            selected: true
        }, {
            name: 'Firefox',
            y: 10.38
        }, {
            name: 'Safari',
            y: 4.77
        }, {
            name: 'Opera',
            y: 0.91
        }, {
            name: 'Other',
            y: 0.2
        }]
    }]
};";

?>

<?php foreach($settings as $key => $value){ ?>
    <?php 
        if($value == 'false' or $value == 'null') {
            $value_string = " = ".$value."";
        }else{
            $value_string = " = '".$value."'";
        }
    ?>
    <?php echo "pie_settings.".str_replace("_",".",$key).$value_string; ?>
    
<?php } ?>

<?php if(isset($error)){ ?>
    console.log("SQL Error!", "<?=$error?>");
<?php } ?>
    
<?php if(isset($series)){ ?>
        
        var previous_data = pie_settings.series[0].data;

        var new_data = JSON.parse('<?= $series; ?>'); //getting data
        var overwrite_data = JSON.parse('<?= $series; ?>'); //getting data

        //Set overwrite logic along with settings file.
        $.each(previous_data, function(k1, v1){
            $.each(new_data, function(k2, v2){
                if(v1.name == v2.name){
                    val = {name: v2.name, y: v2.y, sliced: true};
                    overwrite_data[k2] = val;
                }
            });
        });

        pie_settings.series[0].data = overwrite_data;
        
<?php } ?>



var chart_data = pie_settings;

