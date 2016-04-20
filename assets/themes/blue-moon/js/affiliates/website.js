var $border_color = "#efefef";
var $grid_color = "#ddd";
var $default_black = "#666";
var $green = "#8ecf67";
var $yellow = "#fac567";
var $orange = "#F08C56";
var $blue = "#1e91cf";
var $red = "#f74e4d";
var $teal = "#28D8CA";
var $grey = "#999999";
var $dark_blue = "#0D4F8B";

$(function () {

    if(typeof $('a.login').attr('href') != 'undefined') {
        var url = $('a.login').attr('href');
        $('#allowAccess').attr('href', url);
        $('#analyitcsModal').show().addClass('in');
    }

    if($('#showModel').length) {
        $('#analyitcsModal').show().addClass('in');
    };

    var d1, chartOptions, d2;
    $.ajax({
        url: 'https://network4rentals.com/network/affiliates/my-website/ajaxMonthlyVisits',
        dataType: "json",
        success: function(data) {
            formatData(data);
        },
        error: function(error) {

        }
    });

    function formatData(d1) {

        var data, chartOptions;

        data = [{
            label: "Visitors",
            data: d1
        }];

        var d = new Date();
        var lastYear = d.getFullYear()-1+'-'+(d.getMonth()+1)+'-1';
        var thisYear = d.getFullYear()+'-'+(d.getMonth())+'-1';

        console.log(d.getMonth());
        console.log(lastYear);

        chartOptions = {
            xaxis: {
                min: new Date(lastYear).getTime(),
                max: new Date(thisYear).getTime(),
                mode: "time",
                tickSize: [1, "month"],
                monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                tickLength: 0
            },
            yaxis: {

            },
            series: {
                lines: {
                    show: true,
                    fill: true,
                    fill: 0.1,
                    lineWidth: 2
                },
                points: {
                    show: true,
                    radius: 5,
                    fill: true,
                    fillColor: "#ffffff",
                    lineWidth: 2,
                }
            },
            grid:{
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                tickColor: "#eee",
                borderColor: "#ccc",
            },
            legend: {
                show: true,
                position: 'nw'
            },
            tooltip: true,
            tooltipOpts: {
                content: '%s: %y'
            },
            shadowSize: 0,
            colors: ['#058DC7', '#666666', '#333333', '#CCCCCC'],
        };

        var holder = $('#area-chart3');

        if (holder.length) {
            $.plot(holder, data, chartOptions);
        }
    }








});