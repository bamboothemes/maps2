jQuery( document ).ready(function() {
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

	google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
	// http://stackoverflow.com/questions/832692/how-can-i-check-whether-google-maps-is-fully-loaded
    //this part runs when the mapobject is created and rendered
    function updateMapType(){
    	var selectedmaptype = jQuery('#jform_params_maptype').val();
    	if (selectedmaptype === 'SATELLITE') {
    		map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
    	} else if (selectedmaptype === 'HYBRID') {
    		map.setMapTypeId(google.maps.MapTypeId.HYBRID);
    	} else if (selectedmaptype === 'TERRAIN') {
    		map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
    	} else {
    		map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    	};
    } //end updatemapType

    //update type on pageload
    updateMapType();

    //update type on input change
    jQuery('#jform_params_maptype').change(updateMapType);

    //update input on map change
    google.maps.event.addListener( map, 'maptypeid_changed', function() { 
    	jQuery('#jform_params_maptype').val(map.getMapTypeId().toUpperCase());
    	jQuery('#jform_params_maptype_chzn span').text(map.getMapTypeId());
    } );

}); //end tilesloaded

	//set the map centre on pageload
	var latInput = jQuery('#jform_params_mapcentlat').val();
	var lonInput = jQuery('#jform_params_mapcentlon').val();
	map.setCenter(new google.maps.LatLng(latInput, lonInput));
	//update the values when the map is dragged
	google.maps.event.addListener(map, 'dragend', function(event){
		var latLng = map.getCenter();
		jQuery('#jform_params_mapcentlat').val(latLng.lat());
		jQuery('#jform_params_mapcentlon').val(latLng.lng());
	}); //end dragend
	//update the map when the values are changed
	jQuery('#jform_params_mapcentlat,#jform_params_mapcentlon').on('keyup keypress blur change', function() {
		var newlatlng = new google.maps.LatLng(jQuery('#jform_params_mapcentlat').val(), jQuery('#jform_params_mapcentlon').val());
		map.panTo(newlatlng);
	});

	//find the location of an address
	var geocoder = new google.maps.Geocoder();
	function geocodeAddress(location) {
		geocoder.geocode( { 'address': location}, function(results, status) {
  		//alert(status);
  		if (status == google.maps.GeocoderStatus.OK) {
  			map.setCenter(results[0].geometry.location);
  		}
  		else
  		{
  	//alert('some problem in geocode' + status);
  }
}); 
	}

	//set the centre when using the search
	jQuery('#jform_params_mapsearch').on('keyup', function() {
		geocodeAddress(jQuery('#jform_params_mapsearch').val());
		//console.log(jQuery('#jform_params_mapsearch').val());
	});
	
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
		//console.log(map.getZoom());
	});

	//toggle the tilt
	function updateMapTilt(tilt){
		map.setTilt(parseInt(tilt));
		console.log('tilt ' + parseInt(tilt));
	}
	jQuery('#jform_params_maptilt').change(function(){
		updateMapTilt(jQuery('#jform_params_maptilt').val());
	});
	//set on pageload
	updateMapTilt(jQuery('#jform_params_maptilt').val());

	//set the heading
	function updateMapHeading(heading){
		map.setHeading(parseInt(heading));
		//console.log('heading ' + parseInt(heading));
	}
	jQuery('#jform_params_mapheading').change(function(){
		updateMapHeading(jQuery('#jform_params_mapheading').val());
	});
	//set on pageload
	updateMapHeading(jQuery('#jform_params_mapheading').val());
	//update select on map heading change
	google.maps.event.addListener(map,'heading_changed',function () {
		jQuery('#jform_params_mapheading').val(map.getHeading());
		jQuery('#jform_params_mapheading_chzn span').text(map.getHeading());
		//console.log(map.getHeading());
	});	

	//set general options
	function updateOption(option,value){
		map.set(option,value);
		console.log(option + ' ' + value);
	}

	//map controls stuff
	jQuery('.checkboxes.jbmaps2-mapcontrols').on('change', ':checkbox', function(){
		updateOption(jQuery(this).val(), this.checked ? true : false);
	});
	//check option on pageload
	jQuery('.checkboxes.jbmaps2-mapcontrols :checkbox').each(function(){
		map.set(jQuery(this).val(), this.checked ? true : false);
	});

	//map draggable
	jQuery('#jform_params_mapdraggable').click(function(){
		updateOption('draggable',jQuery('#jform_params_mapdraggable input[type=radio]:checked').val());
	});

} //end initialize
google.maps.event.addDomListener(window, 'load', initialize);


jQuery( document ).ready(function() {

//update map size
function updateMapTypeSize(){
	var mapWidth = jQuery('#jform_params_mapwidth').val();
	var mapHeight = jQuery('#jform_params_mapheight').val();
	var animateMap = jQuery('#map').animate({width : mapWidth, height : mapHeight});
	jQuery.when.apply(this, animateMap).done(function() {
		google.maps.event.trigger(map,'resize'); 
	//console.log('map updated');
});
}
//update on pageload to get param values
updateMapTypeSize()
//update when inputs are changed and delay a little bit	so it's not jumping around
jQuery('#jform_params_mapwidth,#jform_params_mapheight').on('keyup keypress blur change', function() {
	setTimeout(updateMapTypeSize,500);
});

//resize when clicking on tabs incase we saved whilst not looking at the map
jQuery('.nav-tabs').click(function() {
	google.maps.event.trigger(map,'resize'); //need to get this working
});

//test stuff
//var selectedmaptype = jQuery('.jbmaps2-maptype').val();
//jQuery('#jbmaps2-maptype').append('<p>' + selectedmaptype + '</p>');
});
});