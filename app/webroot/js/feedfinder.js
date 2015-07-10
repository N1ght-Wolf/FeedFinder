$(document).ready(function() {
  //date picker stuff
	$(".date_form").datepicker({

		changeYear: true,
		changeMonth: true,
		dateFormat: "yy-mm-dd",
	}).datepicker('setDate', new Date());

	$('#from_datepicker').datepicker('setDate', new Date(2012, 0, 01));

  $('#timespan,  #actions').on('change',function(){
      form_data = $('#query_form').serialize();
      alert(form_data);
      $.ajax({
        type: 'GET',
        dataType: "json",
        data:form_data,
        url: getBaseURL() + '/feed_finder_transactions/' + 'action',
        success: function(data) {
          chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'graph_div',
                    type: 'line'
                },
                title: {
                    text: 'reviews'
                },
                xAxis: {
                    categories: data.month
                },
                yAxis: {
                    title: {
                        text: 'Reviews'
                    },
                    min:0
                },
                series: [{
                    name: 'reviews',
                    data: data.counts
                }]
              });
                    },
        error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
        }
      });
  });




});



function getBaseURL() {
	var url = location.href;
	var baseURL = url.substring(0, url.indexOf('/', 14));

	if (baseURL.indexOf('http://localhost') != -1) {
		var url = location.href;
		var pathname = location.pathname;
		var index1 = url.indexOf(pathname);
		var index2 = url.indexOf("/", index1 + 1);
		var baseLocalUrl = url.substr(0, index2);

		return baseLocalUrl + "/";
	} else {
		// Root Url for domain name
		return baseURL + "/";
	}
}
