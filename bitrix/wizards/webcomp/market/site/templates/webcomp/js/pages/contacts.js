/*YANDEX*/
$(function () {
    ymaps.ready(init);
    var myMap,
        myPlacemark,
        myPin;

    function init() {

        var map = document.querySelector('#map');

        if (map) {
            var data = map.dataset,
                center = data.center;

            myMap = new ymaps.Map("map", {
                center: [data.centerX, data.centerY],
                zoom: data.zoom,
                // controls: ['zoomControl','searchControl',]
            });


            myMap.behaviors.disable(['scrollZoom']);
            // myMap.controls.remove('geolocationControl')
            //     .remove('searchControl')
            //     .remove('trafficControl')
            //     .remove('typeSelector')
            //     .remove('fullscreenControl')
            //     .remove('zoomControl')
            //     .remove('rulerControl');
            // myMap.controls.add('zoomControl');

            myPin = new ymaps.GeoObjectCollection();


            myPlacemark = new ymaps.Placemark([data.baloonX, data.baloonY], {
                    balloonContentHeader: data.baloonHeader,
                    balloonContentBody: data.baloonBody,
                    balloonContentFooter: data.baloonFooter,
                    hintContent: data.baloonContent
                },
                {
                    iconLayout: 'default#image',
                    iconImageHref: data.icon,
                    iconImageSize: [data.iconSizeX, data.iconSizeY],
                    iconImageOffset: [data.iconOffsetX, data.iconOffsetY]
                    // preset: 'islands#redGlyphIcon'
                });


            myPin.add(myPlacemark);
            myMap.geoObjects.add(myPin);
        }
    }
});
/*END YANDEX*/