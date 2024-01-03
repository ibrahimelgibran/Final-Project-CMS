<script src="<?=base_url('assets/plugins/Chart.js');?>"></script>
<script type="text/javascript">
$( document ).ready( function() {
	var element_id = document.getElementById('canvas');
	new Chart(element_id, {
		type: 'bar',
		data: {
			labels: <?=$labels;?>,
			datasets: [{
				label: '',
				data: <?=$data;?>,
				borderWidth: 2,
				backgroundColor: 'rgba(75, 192, 192, 0.2)',
				borderColor: 'rgba(75, 192, 192, 1)'
			}]
		},
		options: {
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
});
</script>
<div class="col-lg-8 col-md-8 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<canvas id="canvas"></canvas>
</div>
<?php $this->load->view('themes/green_land/sidebar')?>
