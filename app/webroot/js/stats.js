var form, formData;
$(document).ready(function() {
   setUpDatePickers();

	//get the form
	 form = $('#stats-control');

	//submit the form
	$(form).submit(function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();
		//serialize the form
    var endUrl = $(form).attr('action');
    console.log(form);
		formData = $(form).serialize();
		//make an ajax get REQUEST

		$.ajax({
			type: 'GET',
      dataType:'json',
			url: getBaseURL() + 'feed_finder_transactions/' + endUrl,
			data: formData,
      success:function(data){
        drawGraph(data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
      console.log(textStatus, errorThrown);
      }

		})
	});


});

function drawGraph(data){
  $('#graph-container').highcharts('StockChart', {

  chart: {
  },

  navigator: {
    handles: {
      backgroundColor: 'yellow',
      borderColor: 'red'
    }
  },

  rangeSelector: {
    selected: 1
  },

  series: [{
      name: 'USD to EUR',
      data: data
  }]
});

var scroller = Highcharts.charts[0].scroller;
var navMin = scroller.xAxis.translate(scroller.zoomedMin, true);
var navMax = scroller.xAxis.translate(scroller.zoomedMax, true);
console.log('min: '+Math.round(navMin)+"\nmax: "+Math.round(navMax));

}

function setUpDatePickers(){
  $('#datetimepicker1').datetimepicker({
    defaultDate: "1/1/2013",
    format: 'YYYY-MM-DD'
    }
  );

	$('#datetimepicker2').datetimepicker({
		useCurrent: false,
    format: 'YYYY-MM-DD',
    defaultDate: new Date()
	});

	$("#datetimepicker1").on("dp.change", function(e) {
		$('#datetimepicker2').data("DateTimePicker").minDate(e.date);
	});

	$("#datetimepicker2").on("dp.change", function(e) {
		$('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
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
