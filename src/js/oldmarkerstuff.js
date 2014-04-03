var locations = [ //some to test with
    //[1, 'Bondi Beach', -33.890542, 151.274856],
    //[2, 'Coogee Beach', -33.923036, 151.259052],
    //[3, 'Cronulla Beach', -34.028249, 151.157507],
    //[4, 'Manly Beach', -33.80010128657071, 151.28747820854187],
    //[5, 'Maroubra Beach', -33.950198, 151.259302]
    ];

    function plotMarkers(locations) {
    	//need to add a markers clear here to remove markers when updating
    	var infowindow = new google.maps.InfoWindow();
    	var marker, i;
    	for (i = 0; i < locations.length; i++) {  
    		marker = new google.maps.Marker({
    			position: new google.maps.LatLng(locations[i][2], locations[i][3]),
                draggable: true,
                map: map
            });
            //open marker
    		google.maps.event.addListener(marker, 'click', (function(marker, i) {
    			return function() {
    				infowindow.setContent(locations[i][1]);
    				infowindow.open(map, marker);
    			}
    		})(marker, i));
            //remove marker
            google.maps.event.addListener(marker, 'dblclick', (function(marker, i) {
                return function() {
                    marker.setMap(null);
                }
            })(marker, i));
    	}
    }

    function placeMarker(location) {
    	var clickedLocation = new google.maps.LatLng(location);
    	var marker = new google.maps.Marker({
    		position: location,
            draggable: true,
            map: map
        });
    	newmarker = [ locations.length + 1, 'new marker', marker['position'].lat(), marker['position'].lng()];
    	locations.push(newmarker);
    	updateMarkerField(locations);
    	plotMarkers(locations);
    	console.log(locations.join('\n'));
    }


  /*  function removeMarker(id) {
    	//remove the marker from the array and replot
    	var locations = JSON.parse(jQuery('input#jform_params_markerdata').val());
    	var newlocations = [];
    	locations.forEach(function(location) {
    		if (parseInt(location[0]) !== id) {
    			console.debug(location);
    			newmarker = [newlocations.length + 1, location[1], location[2], location[3]];
    			newlocations.push(newmarker); 
    		} else {
    			console.log('remove:' + id);
                console.log(location[2],location[3]);
               /* delmarker = new google.maps.Marker({
                    position: google.maps.LatLng(location[2],location[3])
                })
                delmarker.setMap(null) */
    		//	locations[0].google.maps.marker.setMap(null); //need to remove the marker from the map
    	//	}
    //	});
    	
    	//replot markers and update fields
   // 	plotMarkers(newlocations);
   // 	updateMarkerField(newlocations);
    	//console.debug(JSON.stringify(newlocations));
 //   }

    function updateMarkerField(markerarray) {
    	jQuery('input#jform_params_markerdata').val(JSON.stringify(markerarray));
    }

    google.maps.event.addListener(map, 'click', function(event) {
    	placeMarker(event.latLng);
    	createMarkerFields();
    });

    //create the fields to edit marker data
    function createMarkerFields(locations){
    	locations = JSON.parse(jQuery('input#jform_params_markerdata').val());
    	var markerHtml = '';
    	locations.forEach(function(location) {
    		console.log(location);
    		markerHtml += '<fieldset class="form-inline">\
    		<legend>Marker' + location[0]  + '</legend>\
    		<label>Text</label>\
    		<textarea rows="3">' + location[1] + '</textarea>\
    		<label>Lat:</label>\
    		<input class="input-mini" type="text" value="' + location[2] +'">\
    		<label>Lon:</label>\
    		<input class="input-mini" type="text" value="' + location[3] +'">\
    		<button data-marker-id="' + location[0]  + '" class="btn btn-mini btn-danger removemarker" type="button"><i class="icon-remove"></i>Delete Marker</button>\
    		</fieldset>';
    	});
    	document.getElementById('markers').innerHTML = markerHtml;
    }

   	//check for markers on pageload
    if(jQuery('input#jform_params_markerdata').val() != ''){
    	savedmarkers = JSON.parse(jQuery('input#jform_params_markerdata').val());
    	locations = savedmarkers;
    	plotMarkers(savedmarkers);
    	createMarkerFields();
    }

    //remove a marker if clicked
    jQuery('.btn.removemarker').click(function(){
    	//console.log('marker id:' + jQuery(this).data('marker-id'));
    	id = jQuery(this).data('marker-id');
    	removeMarker(id);
    	createMarkerFields();
    	plotMarkers(JSON.parse(jQuery('input#jform_params_markerdata').val()));
    })