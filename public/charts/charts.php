<?php include("./chartsdata.php"); ?>
    $(function(){
        var plot6 = $.jqplot("chart<?php echo $id; ?>", 
            <?php echo $str; ?>  
            ,{
                title:"<?php echo $title; ?>",
                animate: true,
                animateReplot: true,
                seriesDefaults: {
                lineWidth: 1.5, 
                    showMarker:false,
                    pointLabels: {
                        show: false,
                        edgeTolerance: 5
                    }
                },
                series: [
                    <?php foreach ($options as $key => $value) {
                        echo "{";
                        echo "label: '".$value."'";
                        echo "},";
                    } ?>
           ],
                legend:{
                    show:true,
                    placement: 'outsideGrid', 
                    renderer: $.jqplot.EnhancedLegendRenderer,
                    rendererOptions: {
                        numberRows: 1
                    },
                    location: 's'
                },
                axesDefaults: {
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                },
                axes:{
                    xaxis:{
                        renderer:$.jqplot.DateAxisRenderer,
                        tickOptions:{
                            angle:-30,
                            formatString:"<?php echo $type; ?>"
                        }, 
                        min:"<?php echo reset($time); ?>", max:"<?php echo end($time); ?>"
                    },
                    yaxis:{
                        pad: 1, min:0,
                        tickOptions:{
                                formatString:"%.2f"
                        },
                        label: "Unit: <?php echo $unit; ?>"}
                },
                highlighter: {
                    //tooltipOffset: -50, //提示框向下位置偏移量
                    //useAxesFormatters: false, //数据原格式保留，
                    show: true, 
                    sizeAdjust: 1,
                    showTooltip: true ,
                    formatString:'<table class="jqplot-highlighter"> \
                                <tr><td>Time:</td><td>%s</td></tr> \
                                <tr><td>Num:</td><td>%.2f</td></tr></table>'
                },
                grid:{shadow:false,borderWidth:0,gridLineColor:"#ccc",background:"#FFFFFF"}
            });
        });
