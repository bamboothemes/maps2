function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(54.525961, 15.255119), //change to match a default set in the params
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    zoom: 5,
    panControl: 0,
    zoomControl: 0,
    mapTypeControl: 0,
    scaleControl: 0,
    streetViewControl: 0,
    overviewMapControl: 0
  };
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);

//******************map size***********************//
function updateMapTypeSize(){
  var mapWidth = jQuery('#jform_params_mapwidth').val();
  var mapHeight = jQuery('#jform_params_mapheight').val();
  var animateMap = jQuery('#map').animate({width : mapWidth, height : mapHeight});
  jQuery.when.apply(this, animateMap).done(function() {
    google.maps.event.trigger(map,'resize'); 
  });
  //TODO add a warning if the map is set to be larger than the current div
}
//update on pageload to get param values
updateMapTypeSize();
//update when inputs are changed and delay a little bit so it's not jumping around
jQuery('#jform_params_mapwidth,#jform_params_mapheight').on('keyup keypress blur change', function() {
  setTimeout(updateMapTypeSize,500);
});
//******************end map size*******************//

//******************map centre***********************//
function updateMapCentre(lat,lng){
  map.setCenter(new google.maps.LatLng(lat, lng));
}
//update centre on pageload
var latInput = jQuery('#jform_params_mapcentlat').val();
var lngInput = jQuery('#jform_params_mapcentlon').val();
updateMapCentre(latInput, lngInput);

function updateCentreFields(lat,lng){
  var latLng = map.getCenter();
  lat = typeof lat !== 'undefined' ? lat : latLng.lat();
  lng = typeof lng !== 'undefined' ? lng : latLng.lng();
  jQuery('#jform_params_mapcentlat').val(lat);
  jQuery('#jform_params_mapcentlon').val(lng);
}
//update values when map is dragged
google.maps.event.addListener(map, 'dragend', function(event){
  updateCentreFields();
});
//update the map when the values are changed
jQuery('#jform_params_mapcentlat,#jform_params_mapcentlon').on('keyup keypress blur change', function() {
  var newlatlng = new google.maps.LatLng(jQuery('#jform_params_mapcentlat').val(), jQuery('#jform_params_mapcentlon').val());
  map.panTo(newlatlng);
});
//find the location of an address
var geocoder = new google.maps.Geocoder();
function geocodeAddress(location) {
  geocoder.geocode( { 'address': location}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      updateCentreFields();
    }
    else
    {
    //console.log('some problem in geocode' + status);
  }
});
}
//set the centre when using the search
jQuery('#jform_params_mapsearch').on('keyup', function() {
  geocodeAddress(jQuery('#jform_params_mapsearch').val());
});
//******************end map centre*******************//

//******************maptype***********************//
function updateMapType(){
  var selectedmaptype = jQuery('#jform_params_maptype').val();
  var mapStyle = JSON.parse(jQuery('#jform_params_mapcustomstyle').val());
  if (mapStyle !== '') {
    map.setOptions({styles: mapStyle});
  } 
  if (selectedmaptype === 'SATELLITE') {
    map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
  } else if (selectedmaptype === 'HYBRID') {
    map.setMapTypeId(google.maps.MapTypeId.HYBRID);
  } else if (selectedmaptype === 'TERRAIN') {
    map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
  } else {
    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
  }
} 
//update type on pageload
updateMapType();
//update type on input change
jQuery('#jform_params_maptype').change(updateMapType);
//update input on map change
google.maps.event.addListener( map, 'maptypeid_changed', function() { 
  jQuery('#jform_params_maptype').val(map.getMapTypeId().toUpperCase());
  jQuery('#jform_params_maptype_chzn span').text(map.getMapTypeId());
} );
//******************end maptype*******************//

//******************controls***********************//
jQuery('.checkboxes.jbmaps2-mapcontrols').on('change', ':checkbox', function(){
  map.set(jQuery(this).val(), this.checked ? true : false);
});
//check option on pageload
jQuery('.checkboxes.jbmaps2-mapcontrols :checkbox').each(function(){
  map.set(jQuery(this).val(), this.checked ? true : false);
});
//******************end controls*******************//

//******************zoom***********************//
function updateMapZoom(){
  var selectedmapzoom = parseInt(jQuery('#jform_params_mapzoom').val());
  map.setZoom(selectedmapzoom);
}
//set zoom on pageload
updateMapZoom();
//update on zoom select change
jQuery('#jform_params_mapzoom').change(updateMapZoom);
//update zoom select on map zoom
google.maps.event.addListener(map,'zoom_changed',function () {
  jQuery('#jform_params_mapzoom').val(map.getZoom());
  jQuery('#jform_params_mapzoom_chzn span').text(map.getZoom());
}); 
//******************end zoom*******************//

//******************tilt***********************//
function updateMapTilt(tilt){
  map.setTilt(parseInt(tilt));
}
//set on pageload
updateMapTilt(jQuery('#jform_params_maptilt').val());
//update on field change
jQuery('#jform_params_maptilt').change(function(){
  updateMapTilt(jQuery('#jform_params_maptilt').val());
});
//need to add map listener here
//******************end tilt*******************//

//******************heading***********************//
function updateMapHeading(heading){
  map.setHeading(parseInt(heading));
}
//set on pageload
updateMapHeading(jQuery('#jform_params_mapheading').val()); 
//update on field change   
jQuery('#jform_params_mapheading').change(function(){
  updateMapHeading(jQuery('#jform_params_mapheading').val());
});
//update hield on map change
google.maps.event.addListener(map,'heading_changed',function () {
  jQuery('#jform_params_mapheading').val(parseInt(map.getHeading()));
  jQuery('#jform_params_mapheading_chzn span').text(map.getHeading());
});
//******************end heading*******************//

//******************drag***********************//
jQuery('#jform_params_mapdraggable').click(function(){
  map.set('draggable',jQuery('#jform_params_mapdraggable input[type=radio]:checked').val());
});
//******************end drag*******************//



//******************o***********************//
//******************end o*******************//
//******************o***********************//
//******************end o*******************//
//******************o***********************//
//******************end o*******************//
//******************o***********************//
//******************end o*******************//

//******************re-initalize on tab click***********************//
[].forEach.call(document.querySelectorAll('ul.nav-tabs li'), function(el) {
  el.addEventListener('click', function() {
    setTimeout(function(){ initialize(); }, 100);
  });
});
google.maps.event.trigger(map,'resize');
//******************end re-initalize on tab click*******************//

} //end initalize
google.maps.event.addDomListener(window, 'load', initialize);