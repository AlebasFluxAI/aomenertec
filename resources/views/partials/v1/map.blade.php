<label>{{$input_label??""}}</label>


<script>
    let autocomplete;
    let map;
    let marker;

    function myMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // Success function
                getLocation,
                // Error function
                null,
                // Options. See MDN for details.
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function getLocation(position) {

        if (@this.latitude != null && @this.longitude != null) {
            var center = {
                lat: Number(@this.latitude),
                lng: Number(@this.longitude)
            };
        } else {
            var center = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
        }


        var mapProp = {
            center: center,
            zoom: 14,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        marker = new google.maps.Marker({
            position: center,
            map: map,
            mapTypeControl: false,
            draggable: true
        });


        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("autocomplete"),
            {
                types: ["address"],
                componentRestrictions: {'country': ['CO']},
                fields: ['place_id', 'geometry', 'name']
            }
        );

        google.maps.event.addListener(marker, 'dragend', function (evt) {
            $("#autocomplete").val('');
            map.panTo(evt.latLng);
            updateLocation(evt.latLng.lat().toFixed(6), evt.latLng.lng().toFixed(6));
        });


        autocomplete.addListener('place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert('No details available for input: \'' + place.name + '\'');
                return;
            }
            updateLocation(place.geometry.location.lat().toFixed(6), place.geometry.location.lng().toFixed(6));
            renderAddress(place);
            fillInAddress(place);
        });

        function fillInAddress(place) {  // optional parameter
            const addressNameFormat = {
                'street_number': 'short_name',
                'route': 'long_name',
                'locality': 'long_name',
                'administrative_area_level_1': 'short_name',
                'country': 'long_name',
                'postal_code': 'short_name',
            };
            const getAddressComp = function (type) {
                for (const component of place.address_components) {
                    if (component.types[0] === type) {
                        return component[addressNameFormat[type]];
                    }
                }
                return '';
            };


        }

        function updateLocation(latitude, longitude) {
        @this.latitude
            = latitude;
        @this.longitude
            = longitude;
        }

        document.addEventListener('livewire:load', function () {
        @this.latitude
            = position.coords.latitude;
        @this.longitude
            = position.coords.longitude;
        })


    }

    function renderAddress(place) {
        map.setCenter(place.geometry.location);
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    }

    function latitudeShow() {
        let latitude = document.getElementById('latitude').value;
        let longitude = document.getElementById('longitude').value;
        map.setCenter(new google.maps.LatLng(latitude, longitude));
        marker.setPosition(new google.maps.LatLng(latitude, longitude));
        marker.setVisible(true);
    }

    function longitudeShow() {
        let latitude = document.getElementById('latitude').value;
        let longitude = document.getElementById('longitude').value;
        map.setCenter(new google.maps.LatLng(latitude, longitude));
        marker.setPosition(new google.maps.LatLng(latitude, longitude));
        marker.setVisible(true);
    }

</script>


<div class="col-md-8 mb-3">
    <input class="form-control" id="autocomplete" type="text" placeholder="Ingrese una direccion (Opcional)"/>
</div>


<div wire:ignore id="googleMap" style="width:100%;height:400px;border-color: teal;border-width: 2px"></div>


<div class="col-md-8 mb-3">
    <p><b>Direccion:</b></p>
    <ul>
        <li>  {{ $decodedAddress }}</li>

    </ul>
</div>
<div class="col-md-8 mb-3">
    <p><b>Coordenadas :</b></p>
    <ul>
        <li>
            @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Latitude",
                        "input_model"=>"latitude",
                        "updated_input"=>"lazy",
                        "icon_class"=>"fas fa-map",
                        "placeholder"=>"latitude",
                        "input_id"=>"latitude",
                        "input_on_change"=>"latitudeShow",
                        "col_with"=>8,
                        "input_type"=>"text",
                        "required"=>false
               ])</li>
        <li>
            @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Longitude",
                        "input_model"=>"longitude",
                       "updated_input"=>"lazy",
                         "input_id"=>"longitude",
                        "input_on_change"=>"longitudeShow",
                        "icon_class"=>"fas fa-map",
                        "placeholder"=>"longitude",
                        "col_with"=>8,
                        "input_type"=>"text",
                        "required"=>false
               ])</li>
    </ul>
</div>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{config("google.apiKey")}}&callback=myMap&libraries=places"></script>


