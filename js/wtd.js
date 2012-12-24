
$(document).ready(function(){

	
	var processWeatherData =
	  function(_city, _region)
	  {
	    var url = "includes/get_data.php?city="+encodeURIComponent(_city)+"&region="+encodeURIComponent(_region);
	    
	     var Result = $.getJSON(url, "",
      		function (jsonWeather)
      		{
        		$("#message").html('\"'+jsonWeather.message+'\"');	
        		$('#why').show("fast");
        		$("#current_time").html(jsonWeather.astroData.current_time.match(/\ (.*):/)[1])
        		$("#sunset_time").html(jsonWeather.astroData.sunset_time.match(/\ (.*):/)[1])
        		$("#sunrise_time").html(jsonWeather.astroData.sunrise_time.match(/\ (.*):/)[1])
        		DrawRainGraph(jsonWeather.weatherData.raw);
      		})
      		.error(WTDWeatherError);
	  }
	
  var WTDWeatherError = 
    function()
    {
      		$("#message").html("error getting weather data.");	
    };
                  
  
  var WTDLocationError = 
    function()
    {
         $("#user-location").html("Select");
    }

    var ShowLocationEditor = function()
    {
        $('#location-text').val($('#user-location').html().replace("<br>", ", "));
        UpdateLocationBox();
        $('#location-editor').show("fast");
        $('#location-text').attr('selected', 'selected').focus();
        $('#results').hide("fast");
    }

    var location_text_updated = 0;
    var location_text_working = 0;

    var UpdateLocationBox = function()
    {
      if (location_text_updated && !location_text_working)
      {
        location_text_updated = 0;
        location_text_working = 1;
        clearInterval(location_editor_timer);
        location_editor_timer = null;
        var location = $('#location-text').val();
        if (location.length > 3)
        {
          $.getJSON('includes/location_list.php?location=' + encodeURIComponent(location), "",
             function (jsonLocationList)
             {
               $('#location-list').empty();

               if (jsonLocationList.results.length)
                    $('#update-button').removeAttr("disabled");       
               else $('#update-button').attr("disabled", "disabled");

               $.each(jsonLocationList.results, function(index, value){
                 $('#location-list').append($('<option>', { index : value.name })
                             .text(value.name));
               })

                $('#location-list').css({'visibility':'visible'});
                location_text_working = 0;

           }).error( function() {location_text_working = 0;} );
        }
        else{
         $('#location-list').empty();
         $('#location-list').css({ 'visibility':'hidden'});
         $('#update-button').attr("disabled", "disabled");
         location_text_working = 0;
        }  
      }

    }

    var LocationTextUpdated = function(){
      if (location_editor_timer==null)
        location_editor_timer = window.setInterval(UpdateLocationBox, 500);

      location_text_updated = 1;
    }  
  
  var Run = function(_city, _region)
  {
    if(_city && _region)
    {
      // Save preference
      $.cookie('walkthedog_city', _city);
      $.cookie('walkthedog_region', _region);
      processWeatherData(_city, _region);
    }
    else
    {
      if ($.cookie('walkthedog_city')==null || $.cookie('walkthedog_region')==null)  
       {  
           $.getJSON('includes/ip_location.php', "",
             function (jsonLocation)
             {

               $("#user-location").html(jsonLocation.city+"<br>"+jsonLocation.region);

               if (jsonLocation.region && jsonLocation.city)
               {
                 $.cookie('walkthedog_city', jsonLocation.city);
                 $.cookie('walkthedog_region', jsonLocation.region);
                 processWeatherData(jsonLocation.city, jsonLocation.region);
               }
               else
               {
                 $("#user-location").html("Select your location");
               }

           }).error(WTDLocationError);    
       }else{
             $("#user-location").html($.cookie('walkthedog_city') + "<br>"+ $.cookie('walkthedog_region'));
             processWeatherData($.cookie('walkthedog_city'), $.cookie('walkthedog_region'));
       }
    }
  }
  
  var Update = function()
  {
    if ($('#location-list option').length && $('#update-button').attr("disabled") != "disabled")
    {
      if ($('#location-list option:selected').length == 0)
        $('#location-list option:first').attr('selected', 'selected').parent().focus();
      
      var string = $('#location-list option:selected').text();
      var array = string.split(', ');
      $('#user-location').html(array[0] + "<br>" + array[1]);
      $("#message").html('Loading weather data.')
      Run(array[0],array[1]);
      $('#location-editor').hide("fast");
      $('#results').show("fast");
    }
  }
  
  var rain_graph;
  
  var DrawRainGraph = function(_weatherdata)
  {
    var data = [];
    $.each(_weatherdata, function(index, value){
       data.push([value.prettytime, parseInt(value.pop)]);
     });
    
    
    rain_graph = $.jqplot('raingraph', [data], {
      title:'% Chance of rain',
      grid:
        {
          background: 'transparent',
          shadow: false,
          borderWidth: 0
        },
      axes:
        {
          xaxis:
          {
            renderer:$.jqplot.DateAxisRenderer, 
            tickOptions:{formatString:'%a %I%p'},
            numberTicks:4
          }
        },
      series:[{
        lineWidth:4,
        shadow: false, 
        color: "#97461F",
        markerOptions:{show: false},  
        rendererOptions: {
            smooth: true
        }}]
    });
  }
  
  $("#location-text")
    .live('input', LocationTextUpdated)
    .keydown(function (k){
        if(k.keyCode=='40'&& $('#location-list option').length )
        {
          $('#location-text').attr('prevval', $('#location-text').val());
          $('#location-text').val($('#location-list option:first').text());
          $('#location-list option:first').attr('selected', 'selected').parent().focus();
        }
        else if (k.keyCode=='13')
        {
          Update();
        }
    });
    
  $('#location-list')
    .keydown(function (k){
      if(k.keyCode=='38' && $('#location-list').prop('selectedIndex')==0)
      {
        $('#location-text').val($('#location-text').attr('prevval'));
        $('#location-text').attr('selected', 'selected').focus();
      }
        else if (k.keyCode=='13')
        {
          Update();
        }
    })
    .change(function (){
      if ($('#location-list option').length)
        if ($('#location-list option:selected').length)
          $('#location-text').val($('#location-list option:selected').text());
        else
        {
          $('#location-list option:first').attr('selected', 'selected').parent().focus();
          $('#location-text').val($('#location-list option:first').text());
        }
    });

  $('.show-editor').click(ShowLocationEditor);
  
  $('#update-button')
    .attr("disabled", "disabled")
    .click(Update);
  $('#hide-button')
    .click(function(){$('#location-editor').hide("fast"), $('#results').show("fast");});
    
  $('#clear-button').click( function () {$('#location-text').val('')});
  $('#why-link').click(
    function(){ 
      if($('#why-data').css('display')=="none")
      {
        $('#why-link').html('less info &uarr;');
        $("#why-data").show("fast");
        rain_graph.draw();
      }else 
      {
         $('#why-link').html('more info &darr;');
         $("#why-data").hide("fast"); 
      }
    });
  Run();
  
  var location_editor_timer = window.setInterval(UpdateLocationBox, 500);
});