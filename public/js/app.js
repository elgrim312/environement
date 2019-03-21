var current_country = 'fr';
var current_date = '0';

$( document ).ready(function() {
    fetchdata(current_country,current_date);


    $(".js-range-slider").ionRangeSlider({
        skin: "square",
        min: 0,
        max: 5,
        values: ['2 ans','1 an','6 mois', '2 mois', 'Aujourd\'hui'],
        from:5,
        onFinish: function (data) {

            switch (data.from_value) {
                case '2 ans':
                    current_date = '-24';
                    break;
                case '1 an':
                    current_date = '-12';
                    break;
                case '6 mois':
                    current_date = '-6';
                    break;
                case '2 mois':
                    current_date = '-2';
                    break;
                case 'Aujourd\'hui':
                    current_date = '0';
                    break;
                default:
                    current_date = '0';
            }
            fetchdata(current_country,current_date);
        },
    });
});


$('.country-element').click(function(){
        current_country = $(this).attr('id');
        $('.country-flag').css('background','url(/img/'+current_country+'.svg)  center').css('background-size','cover');
        fetchdata(current_country,'0');
        $(".js-range-slider").data("ionRangeSlider").reset();
});

function fetchdata(country, date){
    // AIRQUALITY
    $.ajax({
        url: "/airquality",
        type: "POST",
        data: { country: country, date : date},
        success: function(result){
            if (result.data.data[0].aqi_101>=50){
                $('.cloud').css('background-color','grey');
            }
        },
        error: function () {
        }});

    // AIRQUALITY
    $.ajax({
        url: "/carbonEvolution",
        type: "POST",
        data: { country: country},
        success: function(result){
            calculatedResult = result.data.data.carbonIntensity;
            $('.sun').css('width',calculatedResult*2)
            .css('height',calculatedResult*2)
            .css('fill', 'rgb(255, 102, 51)')
            .css('stroke', 'rgb(255, 102, 51)')
            .css('fill-opacity', calculatedResult/100)
            .css('stroke-opacity', calculatedResult/100)
            .css('animation-duration',(100%calculatedResult)*100 +'ms' );

        },
        error: function () {
        }});

    $.ajax({
        url: "/biodiversityScore",
        type: "POST",
        data: { country: country, date : date},
        success: function(result){
            if(result.data <= 0){
                $('.rond').css('fill-opacity','0');
            }
            if(result.data <= -0.05){
                $('.pointu').css('fill-opacity','0');
            }

        },
        error: function () {
        }});

}

particlesJS('particles-js',

    {
        "particles": {
            "number": {
                "value": 1000,
                "density": {
                    "enable": true,
                    "value_area": 1000
                }
            },
            "color": {
                "value": "#000000"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 0.4,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 2,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": false,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": false,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": false,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true,
        "config_demo": {
            "hide_card": false,
            "background_color": "#b61924",
            "background_image": "",
            "background_position": "50% 50%",
            "background_repeat": "no-repeat",
            "background_size": "cover"
        }
    }
);

//get slider value
$('.by-range').on('click', function() {
    var valslider = $(this).data('month');
    console.log(valslider);
});
