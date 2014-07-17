<?php 
$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, JPATH_COMPONENT_ADMINISTRATOR, $language_tag, $reload);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR); 
$obj = new bll_locations(-1);
$locations = $obj->findAll();

?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="../<?php echo  LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="../<?php echo  LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="../<?php echo  LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyA8oRtWdB_iU1tGQPrDPxcFgCEo2gBwO7o" ></script>
<style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 400px; }
      .ui-autocomplete {
        height:20%;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
      }
    </style>
<script type="text/javascript">
    var map;
    var obj_markers = <?php echo json_encode($locations) ?>;
    var markers=[];
    function initialize() {
      var mapOptions = {
        zoom: 2,
        center: new google.maps.LatLng(30.333, -60.644)
      };
      var infowindow = new google.maps.InfoWindow();
      map = new google.maps.Map(document.getElementById('map_canvas'),
          mapOptions);
      for(var index in obj_markers)
      {
          var obj = obj_markers[index];
          var myLatlng = new google.maps.LatLng(obj.lat,obj.lng);
          markers[index] = new google.maps.Marker({
            position: myLatlng,
            map: map,
            animation: google.maps.Animation.DROP,
          });
          google.maps.event.addListener(markers[index], 'click', 
          (function(marker, l_obj) {
            return function() {
              var html="<p>"+l_obj.address+"</p>";
              html+="<a href=\"../administrator/index.php?option=com_fittizen&view=locations&mode=delete&id="+l_obj.id+"\"><span class=\"icon-cancel\"></span><?php echo JText::_("COM_FITTIZEN_REMOVE"); ?></a>";
              infowindow.setContent(html);
              
              infowindow.open(map, marker);
            }
          })(markers[index], obj));
      }
    }
  
  google.maps.event.addDomListener(window, 'load', initialize);
  
  $(function(){
      
      function clr_add_form()
      {
          $("#neighborhood").val("");
          $("#administrative_area_level_1").val("");
          $("#administrative_area_level_2").val("");
          $("#sublocality").val("");
          $("#locality").val("");
          $("#lat").val("");
          $("#lng").val("");
          $("#address").val("");
          $("#country").val("");
          $("#country_short_name").val("");
      }
      
      $( "#dialog" ).dialog({
      autoOpen: false,
      modal:true,
      width:"auto",
      height:"auto",
      show: {
        effect: "blind",
        duration: 500
      },
      hide: {
        effect: "blind",
        duration: 500
      }
    });
    $( "#address" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "http://maps.googleapis.com/maps/api/geocode/json?address="+$( "#address" ).val(),
          dataType: "json",
          success: function( data ) {
            response( $.map( data.results, function( item ) {
              return {
                label: item.formatted_address,
                value: item.formatted_address
              }
            }));
          }
        });
      },
      minLength: 2,
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },
      select: function( event, ui ) {
        this.value = ui.item.value;
        var val = this.value;
        $.ajax('http://maps.googleapis.com/maps/api/geocode/json?address='+val).done(
            function(data)
            {
                if(data.results[0] !== null)
                {
                    $("#submit")[0].disabled=false;
                    var result = data.results[0];
                    if(result.address_components.length > 0)
                    {
                        for(var index in result.address_components)
                        {
                            var ac = result.address_components[index];
                            switch(ac.types[0])
                            {
                                case "neighborhood":
                                    $("#neighborhood").val(ac.long_name);
                                break;
                                case "administrative_area_level_1":
                                    $("#administrative_area_level_1").val(ac.long_name);
                                break;
                                case "administrative_area_level_2":
                                    $("#administrative_area_level_2").val(ac.long_name);
                                break;
                                case "sublocality":
                                    $("#sublocality").val(ac.long_name);
                                break;
                                case "locality":
                                    $("#locality").val(ac.long_name);
                                break;
                                case "country":
                                    $("#country").val(ac.long_name);
                                    $("#country_short_name").val(ac.short_name);
                                break;
                            }
                        }
                    }
                    $("#lat").val(result.geometry.location.lat);
                    $("#lng").val(result.geometry.location.lng);
                    $("#address").val(result.formatted_address);
                }
            }
        );
      }
    });
    
    $("#address").keydown(function(){
        $("#submit")[0].disabled=true;
    })
 
    $( "#opener" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
    $( "#closer" ).click(function() {
      $( "#dialog" ).dialog( "close" );
      clr_add_form();
    });
  });
  
</script>
<div class="span9">
    <h3 class="span3">
        <?php
        echo JText::_('COM_FITTIZEN_LOCATIONS');
        ?>
    </h3> 
    <button id="opener" class="span2 right">
        <?php echo JText::_('COM_FITTIZEN_ADD'); ?>
    </button>
</div>
<div id="map_canvas" class="span9" >

</div>
<div id="dialog" class="span7" title="<?php echo JText::_('COM_FITTIZEN_SEARCH_LOCATION'); ?>">
    <form action="../administrator/index.php?option=com_fittizen&view=locations" method="POST">
        <div>
            <label>
                <?php echo JText::_('COM_FITTIZEN_ADD'); ?>
            </label>
            <input type="text" name="address" value="" id="address" />
        </div>
        <input type="hidden" name="mode" value="save" />
        <input type="hidden" name="country" id="country" value="" />
        <input type="hidden" name="country_short_name" id="country_short_name" value="" />
        <input type="hidden" name="neighborhood" id="neighborhood" value="" />
        <input type="hidden" name="locality" id="locality" value="" />
        <input type="hidden" name="sublocality" id="sublocality" value="" />
        <input type="hidden" name="lat" id="lat" value="" />
        <input type="hidden" name="lng" id="lng" value="" />
        <input type="hidden" name="administrative_area_level_1" id="administrative_area_level_1" value="" />
        <input type="hidden" name="administrative_area_level_2" id="administrative_area_level_2" value="" />
        <button type="submit" id="submit" class="span2 left" disabled="disabled">
            <span class="icon-save"></span>
            <?php echo JText::_('COM_FITTIZEN_ADD'); ?>
        </button>
        <button id="closer" type="button" class="span2 right">
            <span class="icon-cancel"></span>
            <?php echo JText::_('COM_FITTIZEN_CANCEL'); ?>
        </button>
    </form>
</div>
