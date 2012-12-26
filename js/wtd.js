$(document)
    .ready(function ()
{


    var processWeatherData = function (_city, _region)
    {
        var url = "includes/get_data.php?city=" + encodeURIComponent(_city) + "&region=" + encodeURIComponent(_region);

        var Result = $.getJSON(url, "",

        function (jsonWeather)
        {
            $("#message")
                .html('\"' + jsonWeather.message + '\"');
            $('#why')
                .show("fast");
            $("#current_time")
                .html(jsonWeather.astroData.current_time.match(/\ (.*):/)[1])
            $("#sunset_time")
                .html(jsonWeather.astroData.sunset_time.match(/\ (.*):/)[1])
            $("#sunrise_time")
                .html(jsonWeather.astroData.sunrise_time.match(/\ (.*):/)[1])
            DrawRainGraph(jsonWeather.weatherData.raw);
        })
            .error(WTDWeatherError);
    }

    var WTDWeatherError = function ()
    {
        $("#message")
            .html("error getting weather data.");
    };


    var WTDLocationError = function ()
    {
        $("#user-location")
            .html("Select");
    }

    var ShowLocationEditor = function ()
    {
        $('#location-text')
            .val($('#user-location')
            .html()
            .replace("<br>", ", "));
        UpdateLocationBox();
        $('#location-editor')
            .show("fast");
        $('#location-text')
            .attr('selected', 'selected')
            .focus();
        $('#results')
            .hide("fast");
    }

    var location_text_updated = 0;
    var location_text_working = 0;

    var UpdateLocationBox = function ()
    {
        if (location_text_updated && !location_text_working)
        {
            location_text_updated = 0;
            location_text_working = 1;
            clearInterval(location_editor_timer);
            location_editor_timer = null;
            var location = $('#location-text')
                .val();
            if (location.length > 3)
            {
                $.getJSON('includes/location_list.php?location=' + encodeURIComponent(location), "",

                function (jsonLocationList)
                {
                    $('#location-list')
                        .empty();

                    $.each(jsonLocationList.results, function (index, value)
                    {

                        var array = value.name.split(', ');
                        if ( array.length == 2 )
                        {
                          $('#location-list')
                              .append($('<a>',
                              {
                                  class: "location-result", 
                                  href : "#",
                                  index: value.name,
                                  city: array[0],
                                  region: array[1]
                              })
                              .click( function (e) { Update(e.target.attributes.city.nodeValue, e.target.attributes.region.nodeValue) })
                              .html(value.name));
                        }
                    })

                    $('#location-list')
                        .css(
                    {
                        'visibility': 'visible'
                    });
                    location_text_working = 0;

                })
                    .error(function ()
                {
                    location_text_working = 0;
                });
            }
            else
            {
                $('#location-list')
                    .empty();
                $('#location-list')
                    .css(
                {
                    'visibility': 'hidden'
                });
                location_text_working = 0;
            }
        }

    }

    var LocationTextUpdated = function ()
    {
        if (location_editor_timer == null) location_editor_timer = window.setInterval(UpdateLocationBox, 500);

        location_text_updated = 1;
    }

    var Run = function (_city, _region)
    {
        if (_city && _region)
        {
            // Save preference
            $.cookie('walkthedug_city', _city);
            $.cookie('walkthedug_region', _region);
            processWeatherData(_city, _region);
        }
        else
        {
            if ($.cookie('walkthedug_city') == null || $.cookie('walkthedug_region') == null)
            {
                $.getJSON('includes/ip_location.php', "",

                function (jsonLocation)
                {

                    $("#user-location")
                        .html(jsonLocation.city + "<br>" + jsonLocation.region);

                    if (jsonLocation.region && jsonLocation.city)
                    {
                        $.cookie('walkthedug_city', jsonLocation.city);
                        $.cookie('walkthedug_region', jsonLocation.region);
                        processWeatherData(jsonLocation.city, jsonLocation.region);
                    }
                    else
                    {
                        $("#user-location")
                            .html("Select your location");
                    }

                })
                    .error(WTDLocationError);
            }
            else
            {
                $("#user-location")
                    .html($.cookie('walkthedug_city') + "<br>" + $.cookie('walkthedug_region'));
                processWeatherData($.cookie('walkthedug_city'), $.cookie('walkthedug_region'));
            }
        }
    }

    var Update = function (_city, _region)
    {

            $('#user-location')
                .html(_city + "<br>" + _region);
            $("#message")
                .html('Loading weather data.')
            Run(_city, _region);
            $('#location-editor')
                .hide("fast");
            $('#results')
                .show("fast");
    }

    var rain_graph;

    var DrawRainGraph = function (_weatherdata)
    {
        var data = [];
        $.each(_weatherdata, function (index, value)
        {
            data.push([value.prettytime, parseInt(value.pop)]);
        });

        
        $('#raingraph').empty();
        rain_graph = $.jqplot('raingraph', [data],
        {
            title: '% Chance of rain',
            grid: {
                background: 'transparent',
                shadow: false,
                borderWidth: 0
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickOptions: {
                        formatString: '%a %I%p'
                    },
                    numberTicks: 4
                }
            },
            series: [
            {
                lineWidth: 4,
                shadow: false,
                color: "#97461F",
                markerOptions: {
                    show: false
                },
                rendererOptions: {
                    smooth: true
                }}]
        });
    }

    $("#location-text")
        .live('input', LocationTextUpdated);


    $('.show-editor')
        .click(ShowLocationEditor);

    $('.location-result').click( function () { alert("yo")});

    $('#hide-button')
        .click(function ()
    {
        $('#location-editor')
            .hide("fast"), $('#results')
            .show("fast");
    });

    $('#clear-button')
        .click(function ()
    {
        $('#location-text')
            .val('');
        $('#location-text').focus();
    });
    $('#why-link')
        .click(

    function ()
    {
        if ($('#why-data')
            .css('display') == "none")
        {
            $('#why-link')
                .html('less info &uarr;');
            $("#why-data")
                .show("fast");
            rain_graph.draw();
        }
        else
        {
            $('#why-link')
                .html('more info &darr;');
            $("#why-data")
                .hide("fast");
        }
    });
    Run();

    var location_editor_timer = window.setInterval(UpdateLocationBox, 500);
});