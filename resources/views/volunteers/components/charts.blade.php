<script>
    (function(document, window, $) {
    	'use strict';
    	(function() {
			new Chartist.Line('#chartVolunteerHours .ct-chart', {
				labels: ['1', '2', '3', '4', '5'],
				series: [
					[3,8,24,5,7],
				]
			}, {
				low: 0,
				showArea: true,
				showPoint: false,
				showLine: true,
				fullWidth: true,
				lineSmooth: false,
				chartPadding: {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				},
				axisX: {
					showLabel: false,
					showGrid: false,
					offset: 0
				},
				axisY: {
					showLabel: false,
					showGrid: false,
					offset: 0
				}
			});

			new Chartist.Line('#chartVolunteerTec .ct-chart', {
				labels: ['1', '2', '3', '4', '5'],
				series: [
					[40,40,240,120,600],
				]
			}, {
				low: 0,
				showArea: true,
				showPoint: false,
				showLine: true,
				fullWidth: true,
				lineSmooth: false,
				chartPadding: {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				},
				axisX: {
					showLabel: false,
					showGrid: false,
					offset: 0
				},
				axisY: {
					showLabel: false,
					showGrid: false,
					offset: 0
				}
			});

			new Chartist.Line('#chartVolunteerDonations .ct-chart', {
				labels: ['1', '2', '3', '4', '5'],
				series: [
					[25,50,50,25,100],
				]
			}, {
				low: 0,
				showArea: true,
				showPoint: false,
				showLine: true,
				fullWidth: true,
				lineSmooth: false,
				chartPadding: {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				},
				axisX: {
					showLabel: false,
					showGrid: false,
					offset: 0
				},
				axisY: {
					showLabel: false,
					showGrid: false,
					offset: 0
				}
			});

			new Chartist.Line('#chartVolunteerConnections .ct-chart', {
				labels: ['1', '2', '3', '4', '5'],
				series: [
					[1,11,15,27,87],
				]
			}, {
				low: 0,
				showArea: true,
				showPoint: false,
				showLine: true,
				fullWidth: true,
				lineSmooth: false,
				chartPadding: {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				},
				axisX: {
					showLabel: false,
					showGrid: false,
					offset: 0
				},
				axisY: {
					showLabel: false,
					showGrid: false,
					offset: 0
				}
			});
		})();
    })(document, window, jQuery);
</script>