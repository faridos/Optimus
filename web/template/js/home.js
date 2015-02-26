(function($) {
    "use strict";
var pos;
    var options = {
            zoom : 14,
            mapTypeId : 'Styled',
            disableDefaultUI: true,
            mapTypeControlOptions : {
                mapTypeIds : [ 'Styled' ]
            },
            scrollwheel: false
        };
    var styles = [{
        stylers : [ {
            hue : "#cccccc"
        }, {
            saturation : -100
        }]
    }, {
        featureType : "road",
        elementType : "geometry",
        stylers : [ {
            lightness : 100
        }, {
            visibility : "simplified"
        }]
    }, {
        featureType : "road",
        elementType : "labels",
        stylers : [ {
            visibility : "on"
        }]
    }, {
        featureType: "poi",
        stylers: [ {
            visibility: "off"
        }]
    }];

    var markers = [];
    var props = [{
     title : 'Evenement',
        image : '1-1-thmb.png',
        type : 'CrÃ©e par',
        price : 'Prix',
        address : 'Adresse',
        bedrooms : '3',
        bathrooms : '2',
        area : '3430 Sq Ft',
        position : {
            lat : 36.759682099999996,
            lng : 10.281168999999954
        },
        markerIcon : "marker-green.png"
    }, {
       title : 'Evenement',
        image : '1-1-thmb.png',
        type : 'CrÃ©e par',
        price : 'Prix',
        address : 'Adresse',
        bedrooms : '3',
        bathrooms : '2',
        area : '3430 Sq Ft',
        position : {
            lat : 36.803682099999996,
            lng : 10.189168999999954
        },
        markerIcon : "marker-green.png"
    }, {
        title : 'Evenement',
        image : '1-1-thmb.png',
        type : 'CrÃ©e par',
        price : 'Prix',
        address : 'Adresse',
        bedrooms : '3',
        bathrooms : '2',
        area : '3430 Sq Ft',
        position : {
            lat : 36.859682099999996,
            lng : 10.281168999999954
        },
        markerIcon : "marker-green.png"
    }, {
       title : 'Evenement',
        image : '1-1-thmb.png',
        type : 'CrÃ©e par',
        price : 'Prix',
        address : 'Adresse',
        bedrooms : '3',
        bathrooms : '2',
        area : '3430 Sq Ft',
        position : {
            lat : 36.829682099999996,
            lng : 10.21168999999954
        },
        markerIcon : "marker-green.png"
    }, {
       title : 'Evenement',
        image : '1-1-thmb.png',
        type : 'CrÃ©e par',
        price : 'Prix',
        address : 'Adresse',
        bedrooms : '3',
        bathrooms : '2',
        area : '3430 Sq Ft',
        position : {
            lat : 36.809683099999996,
            lng : 10.181568999999954
        },
        markerIcon : "marker-green.png"
    }];

    var infobox = new InfoBox({
        disableAutoPan: false,
        maxWidth: 202,
        pixelOffset: new google.maps.Size(-101, -285),
        zIndex: null,
        boxStyle: {
            background: "url('template/images/infobox-bg.png') no-repeat",
            opacity: 1,
            width: "202px",
            height: "245px"
        },
        closeBoxMargin: "28px 26px 0px 0px",
        closeBoxURL: "",
        infoBoxClearance: new google.maps.Size(1, 1),
        pane: "floatPane",
        enableEventPropagation: false
    });

    var addMarkers = function(props, map) {
        $.each(props, function(i,prop) {
            var latlng = new google.maps.LatLng(prop.position.lat,prop.position.lng);
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                icon: new google.maps.MarkerImage('template/images/marker-green.png'),
                draggable: false,
                animation: google.maps.Animation.DROP,
            });
            var infoboxContent = '<div class="infoW">' +
                                    '<div class="propImg">' +
                                        '<img src="template/images/prop/' + prop.image + '">' +
                                        '<div class="propBg">' +
                                            '<div class="propPrice">' + prop.price + '</div>' +
                                            '<div class="propType">' + prop.type + '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="paWrapper">' +
                                        '<div class="propTitle">' + prop.title + '</div>' +
                                        '<div class="propAddress">' + prop.address + '</div>' +
                                    '</div>' +
                                    '<div class="propRating">' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star"></span>' +
                                        '<span class="fa fa-star-o"></span>' +
                                    '</div>' +
                                  
                                    '<div class="clearfix"></div>' +
                                    '<div class="infoButtons">' +
                                        '<a class="btn btn-sm btn-round btn-gray btn-o closeInfo">fermer</a>' +
                                        '<a href="#" class="btn btn-sm btn-round btn-green viewInfo">Voir</a>' +
                                    '</div>' +
                                 '</div>';

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infobox.setContent(infoboxContent);
                    infobox.open(map, marker);
                }
            })(marker, i));

            $(document).on('click', '.closeInfo', function() {
                infobox.open(null,null);
            });

            markers.push(marker);
        });
    }

    var map;

    setTimeout(function() {
        $('body').removeClass('notransition');

        if ($('#home-map').length > 0) {
            map = new google.maps.Map(document.getElementById('home-map'), options);
            var styledMapType = new google.maps.StyledMapType(styles, {
                name : 'Styled'
            });

            map.mapTypes.set('Styled', styledMapType);
            
            map.setZoom(12);

            addMarkers(props, map);
            
            if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                         pos = new google.maps.LatLng(position.coords.latitude,
                                position.coords.longitude);



                        var newMarker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            icon: new google.maps.MarkerImage('template/images/marker-position.png'),
                            draggable: true,
                            animation: google.maps.Animation.DROP,
                        });

                        map.setCenter(pos);
                        
                    }, function () {
                        handleNoGeolocation(true);
                    });
                } else {
                    handleNoGeolocation(false);
                }
                
                var btn = (document.getElementById('btn-input'));
                google.maps.event.addDomListener(btn, 'click', function () {
                    map.setCenter(pos),
                    map.setZoom(12)

                });
                
                var input = (document.getElementById('pac-input2'));
                
                var searchBox = new google.maps.places.SearchBox((input));
                
                google.maps.event.addListener(searchBox, 'places_changed', function () {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }
//                    for (var i = 0, marker; marker = markers[i]; i++) {
//                        marker.setMap(null);
//                    }

                    // For each place, get the icon, place name, and location.
                    markers = [];
                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0, place; place = places[i]; i++) {
                        var image = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };


                        bounds.extend(place.geometry.location);
                    }

                    map.fitBounds(bounds);
                    map.setZoom(12);
                    
                    
                });
              
                google.maps.event.addListener(map, 'bounds_changed', function () {
                    var bounds = map.getBounds();
                    searchBox.setBounds(bounds);
                });
                
        }
    }, 300);

    if(!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch)) {
        $('body').addClass('no-touch');
    }

     $('.dropdown-select li a').click(function() {
        if (!($(this).parent().hasClass('disabled'))) {
            $(this).prev().prop("checked", true);
            $(this).parent().siblings().removeClass('active');
            $(this).parent().addClass('active');
            $(this).parent().parent().siblings('.dropdown-toggle').children('.dropdown-label').html($(this).text());
        }
    });

    var cityOptions = {
        types : [ '(cities)' ]
    };
    var city = document.getElementById('city');
    var cityAuto = new google.maps.places.Autocomplete(city, cityOptions);

    $('#advanced').click(function() {
        $('.adv').toggleClass('hidden-xs');
    });

    $('.home-navHandler').click(function() {
        $('.home-nav').toggleClass('active');
        $(this).toggleClass('active');
    });

    //Enable swiping
    $(".carousel-inner").swipe( {
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            $(this).parent().carousel('next'); 
        },
        swipeRight: function() {
            $(this).parent().carousel('prev');
        }
    });

    $('.modal-su').click(function() {
        $('#signin').modal('hide');
        $('#signup').modal('show');
    });

    $('.modal-si').click(function() {
        $('#signup').modal('hide');
        $('#signin').modal('show');
    });

    $('input, textarea').placeholder();

})(jQuery);