var base_url = "//network4rentals.com/network/";

function getLabels() {
	$.ajax(base_url+"ajax_contractors/grab_stats/", {
		dataType: 'json',
        success: function(n) {
			console.log(n);
            if(typeof n !== 'undefined') {
				var labels = [];
				var impressions = [];
				var clicks = [];
				for(var i=0;i<n.length;i++) {
					labels.push(n[i].label);
					impressions.push(n[i].impressions);
					clicks.push(n[i].clicks);
				}
				
				var barChartData = {
					labels : labels,
					datasets : [
						{
							label: "Ad Clicks",
							fillColor : "rgba(10, 230, 10, .8)",
							strokeColor : "rgba(220,220,220,0.8)",
							highlightFill: "rgba(20, 200, 20, .8)",
							highlightStroke: "rgba(220,220,220,1)",
							data :  clicks
						},
						{
							label: "Impressions/Views",
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,0.8)",
							highlightFill : "rgba(151,187,205,0.75)",
							highlightStroke : "rgba(151,187,205,1)",
							data : impressions
						}
					]
				};
				var ctx = document.getElementById("canvas").getContext("2d");
				window.myBar = new Chart(ctx).Bar(barChartData, {
					tooltipFillColor: "rgba(0,0,0,0.8)",
					multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>",
					responsive : true
				});
			}
        },
        error: function(e, t, n) {
			console.log(e+' | '+t+' | '+n);
			alert('Charts failed to load due to a time out, try reloading your page again');
		},
        complete: function() {
		
        },				
        timeout: 15000,
        beforeSend: function() {
			
		}
    });
}

$(function() {	
	var barChartData = getLabels();
});