var chart;
$(document).ready(function() {

$(".date_form").datepicker({

  	changeYear: true,
  	changeMonth: true,
  	dateFormat: "yy-mm-dd",
  }).datepicker('setDate', new Date());


  $('#from_datepicker').datepicker('setDate',new Date(2012,0,01));


  // $('#date_span').on('change',function(){
  //   form_data = $('#query_form').serialize();
  //
  //       $.ajax({
  //         type: 'GET',
  //         dataType: "json",
  //         data:form_data,
  //         url: getBaseURL() + '/feed_finder_transactions/' + 'date_range',
  //         success: function(data) {
  //           console.log(data);
  //         },
  //         error: function(error){
  //           alert('failed! :P');
  //         }
  //         });
  // });


  $('#actions, #date_span').on('change',function(){
    form_data = $('#query_form').serialize();
    plotGraphs('action_graph_data','graph_div');
  });


  // $('#query_form').submit(function() {
  //   form_data = $('#query_form').serialize();
  //   plotGraphs('date_range','graph_div');
  //   return false;
  // });




});


function plotGraphs(url, chartDiv){
  form_data = $('#query_form').serialize();
  // alert(url);
  $.ajax({
    type: 'GET',
    dataType: "json",
    data:form_data,
    url: getBaseURL() + '/feed_finder_transactions/' + url,
    success: function(data) {
      console.log(data);
    chart = new Highcharts.Chart({
      chart: {
          renderTo: chartDiv,
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
error: function(xhr, error, textStatus) {
  alert(xhr.status);
}
});
}






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
