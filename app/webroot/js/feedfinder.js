$(document).ready(function() {
  //date picker stuff
	$(".date_form").datepicker({

		changeYear: true,
		changeMonth: true,
		dateFormat: "yy-mm-dd",
	}).datepicker('setDate', new Date());

	$('#from_datepicker').datepicker('setDate', new Date(2012, 0, 01));



  getBasicCount();
  plotGraph('actions','line','graph_div');

  $('#timespan,  #actions').on('change',function(){
      plotGraph('actions','line','graph_div');
  });

	$(function () {

	    // Prepare random data
	    var data = [
	        {
	            "code": "DE.SH",
	            "value": 728
	        },
	        {
	            "code": "DE.BE",
	            "value": 710
	        },
	        {
	            "code": "DE.MV",
	            "value": 963
	        },
	        {
	            "code": "DE.HB",
	            "value": 541
	        },
	        {
	            "code": "DE.HH",
	            "value": 622
	        },
	        {
	            "code": "DE.RP",
	            "value": 866
	        },
	        {
	            "code": "DE.SL",
	            "value": 398
	        },
	        {
	            "code": "DE.BY",
	            "value": 785
	        },
	        {
	            "code": "DE.SN",
	            "value": 223
	        },
	        {
	            "code": "DE.ST",
	            "value": 605
	        },
	        {
	            "code": "DE.",
	            "value": 361
	        },
	        {
	            "code": "DE.NW",
	            "value": 237
	        },
	        {
	            "code": "DE.BW",
	            "value": 157
	        },
	        {
	            "code": "DE.HE",
	            "value": 134
	        },
	        {
	            "code": "DE.NI",
	            "value": 136
	        },
	        {
	            "code": "DE.TH",
	            "value": 704
	        }
	    ];

	    $.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=germany.geo.json&callback=?', function (geojson) {

	        // Initiate the chart
	        $('#geo_div').highcharts('Map', {

	            title : {
	                text : 'GeoJSON in Highmaps'
	            },

	            mapNavigation: {
	                enabled: true,
	                buttonOptions: {
	                    verticalAlign: 'bottom'
	                }
	            },

	            colorAxis: {
	            },

	            series : [{
	                data : data,
	                mapData: geojson,
	                joinBy: ['code_hasc', 'code'],
	                name: 'Random data',
	                states: {
	                    hover: {
	                        color: '#BADA55'
	                    }
	                },
	                dataLabels: {
	                    enabled: true,
	                    format: '{point.properties.postal}'
	                }
	            }]
	        });
	    });
	});


});
function getBasicCount(){
  $.ajax({
    type:'GET',
    dataType:'json',
    url: getBaseURL() + '/feed_finder_transactions/' +'basic_data_counts',
    success: function(data){
      console.log(data);
      $('#basic_counts').append(JSON.stringify(data));
    },
    error: function(xhs,textStatus,error){
      console.log(textStatus);
    }

  });
}

function plotGraph(url, graph_type, render_div){
  form_data = $('#query_form').serialize();

  $.ajax({
    type: 'GET',
    dataType: "json",
    data:form_data,
    url: getBaseURL() + '/feed_finder_transactions/' + url,
    success: function(data) {
      var chart_title = $('#actions option:selected').text();
      chart = new Highcharts.Chart({
            chart: {
                renderTo: render_div,
                type: graph_type
            },
            title: {
                text: chart_title
            },
            xAxis: {
                categories: data.month
            },
            yAxis: {
                title: {
                    text: 'Count'
                },
                min:0
            },
            series: [{
                name: chart_title,
                data: data.counts
            }]
          });
                },
    error: function(jqXHR, textStatus, errorThrown) {
    console.log(textStatus, errorThrown);
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
