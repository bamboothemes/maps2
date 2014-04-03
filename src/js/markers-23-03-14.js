var locations = [];
function placeMarker(location) {
  var clickedLocation = new google.maps.LatLng(location);
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    markerid: locations.length + 1,
    draggable:true
  });
  markerid = locations.length + 1;
  console.log('markerid: ' + markerid);
  newmarker = [ markerid, 'new marker ' + (locations.length + 1), marker.position.lat(), marker.position.lng()];
  locations.push(newmarker);
  updateMarkerField(locations);
  console.log(locations.join('\n'));
}
//add a marker on click
google.maps.event.addListener(map, 'click', function(event) {
  placeMarker(event.latLng);
  createMarkerFields();
});
//update the field holding the information
function updateMarkerField(markerarray) {
  jQuery('input#jform_params_markerdata').val(JSON.stringify(markerarray));
}
//plot the saved markers
function plotMarkers(locations,oldlocations) {
      // Sets the map on all markers in the array.
      function setAllMap(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }
      setAllMap(null);
      //marker.setMap(null);      
      var infowindow = new google.maps.InfoWindow();
      var marker, i;
      for (i = 0; i < locations.length; i++) {  
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][2], locations[i][3]),
          map: map,
          draggable:true
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(locations[i][1]);
            infowindow.open(map, marker);
          }
        })(marker, i));

        google.maps.event.addListener(marker, 'dblclick', function() { //only works after saving
          var x = confirm("are you sure to delete marker?");
          if(x){
            this.setMap(null);
          }
        });

        google.maps.event.addListener(marker, 'dragend', function(event) { //only works after saving
          newlat = event.latLng.lat();
          newlng = event.latLng.lng();
          console.log(newlat + '' +newlng + ' id:' + this.markerid); //can't seem to get markerid back and this.__gm_id returns different
        });
      }
      updateMarkerField(locations);
      createMarkerFields();
    }
//create the fields to edit marker data
function createMarkerFields(){
  locations = JSON.parse(jQuery('input#jform_params_markerdata').val());
  var markerHtml = '';
  locations.forEach(function(location) {
    //console.log(location);
    /*jshint multistr: true */
    markerHtml += '<fieldset class="form-inline" data-type="markerfieldset">\
    <legend>Marker' + location[0]  + '</legend>\
    <input data-type="markerid" type="hidden" value="' + location[0] +'">\
    <label>Text</label>\
    <textarea data-type="markerhtml" rows="3">' + location[1] + '</textarea>\
    <label>Lat:</label>\
    <input class="input-mini" data-type="markerlat" type="text" value="' + location[2] +'">\
    <label>Lng:</label>\
    <input class="input-mini" data-type="markerlng" type="text" value="' + location[3] +'">\
    <button data-marker-id="' + location[0]  + '" class="btn btn-mini btn-danger removemarker" type="button"><i class="icon-remove"></i>Delete Marker</button>\
    </fieldset>';
  });
  document.getElementById('markers').innerHTML = markerHtml;
}
function updateMarkerDatafromFields(){
  markerdata = [];
  jQuery('fieldset[data-type="markerfieldset"]').each(function(){
    id = parseInt(jQuery(this).find('input[data-type="markerid"]').val());
    html = htmlEntities(jQuery(this).find('textarea[data-type="markerhtml"]').val());
    lat = parseFloat(jQuery(this).find('input[data-type="markerlat"]').val());
    lng = parseFloat(jQuery(this).find('input[data-type="markerlng"]').val());
    //console.log(id,html,lat,lng);
    markerdata.push([id,html,lat,lng]);
    
  });
  updateMarkerField(markerdata);
}
//update marker array when editing
jQuery('#markers').on('keyup keypress change', 'input,textarea', function() {
  updateMarkerDatafromFields();
});
function removeMarker(id) {
      //remove the marker from the array and replot
      var locations = JSON.parse(jQuery('input#jform_params_markerdata').val());
      var newlocations = [];
      locations.forEach(function(location) {
        if (parseInt(location[0]) !== id) {
          //console.debug(location);
          newmarker = [newlocations.length + 1, location[1], location[2], location[3]];
          newlocations.push(newmarker); 
        } else {
          console.log('remove:' + id);
          //location[0].setMap(null); need to remove the marker from the map
        }
      });
      
      //replot markers and update fields
      plotMarkers(newlocations,locations);
      //console.debug(JSON.stringify(newlocations));
    }

//check for markers on pageload
if(jQuery('input#jform_params_markerdata').val() !== ''){
  locations = JSON.parse(jQuery('input#jform_params_markerdata').val());
  plotMarkers(locations);
  createMarkerFields();
}
//remove a marker if clicked
jQuery('#markers').on('click', '.btn.removemarker', function(){
  var x = confirm("are you sure to delete marker?");
  if(x){
    id = jQuery(this).data('marker-id');
    removeMarker(id);
  }
});