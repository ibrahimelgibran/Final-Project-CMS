<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/Chart.js')?>"></script>
<section class="content-header">
   <h1><i class="fa fa-bar-chart text-green"></i> <?=ucwords(strtolower($title));?></h1>
 </section>
 <section class="content">
 	<div class="box">
		<div class="panel-body">
			<canvas id="build_chart"></canvas>
		</div>
	</div>
 </section>
 <script type="text/javascript">
	var ctx = $('#build_chart');
	var buildChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: <?=$labels?>,
	        datasets: [{
	            label: '',
	            data: <?=$data?>,
	            borderWidth: 2,
	            backgroundColor: 'rgba(75, 192, 192, 0.2)',
	            borderColor: 'rgba(75, 192, 192, 1)'
	        }]
	    },
	    options: {
			title: {
			   display: true,
			   text: '<?=$title?>'
			},
			responsive: true,
			scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
	   }
	});
</script>
