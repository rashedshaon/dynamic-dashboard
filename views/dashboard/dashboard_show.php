<?php header_js(['https://code.highcharts.com/highcharts.js']); ?>
<style>
    
.small-box .icon {
    top: 0px;
}
.info-box-icon > i{
    margin-top: 20px;
}

.db-element{
    margin-bottom: 10px;
}

</style>

<?php 
    echo $layout;
?>

<script>
    $(document).ready(function () {
        $('[element-id]').each(function(){
            console.log($(this));
            var element_id = $(this).attr('element-id');
            var element_type = $(this).attr('element-type');
            load_dashboard_element(element_type, element_id)
        });
    });

    function load_dashboard_element(element_type, element_id){
        var part = element_id.split('-');
        var type = part[0];
        var id = part[1];
        console.log('eeee');
        if(element_type == 'chart'){
            $.ajax({
                url:'<?php echo base_url('dashboard/chart/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    eval(data);
                    Highcharts.chart(element_id, chart_data);
                }
            });
        }else if(element_type == 'infobox'){
            $.ajax({
                url:'<?php echo base_url('dashboard/infobox/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    
                    $('#' + element_id).html(data);

                }
            });
        
        }else if(element_type == 'table'){
            $.ajax({
                url:'<?php echo base_url('dashboard/table/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    
                    $('#' + element_id).html(data);

                }
            });
        }
    }
</script>