var current_country = 'fr';
var current_date = '0';
var carbon;
var airqual;
var biodiv;
var waterqual;

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
            airqual = result.data.data[0].aqi_101;
        },
        error: function () {
        }});

    // CARBON
    $.ajax({
        url: "/carbonEvolution",
        type: "POST",
        data: { country: country},
        success: function(result){
            calculatedResult = result.data.data.carbonIntensity;
            $('.sun').css('width',calculatedResult)
            .css('height',calculatedResult)
            .css('fill', 'rgb(255, 102, 51)')
            .css('stroke', 'rgb(255, 102, 51)')
            .css('fill-opacity', calculatedResult/1000)
            .css('stroke-opacity', calculatedResult/1000 + '!important')
            .css('animation-duration',(100%calculatedResult)*100 +'ms' );

          carbon  = calculatedResult;
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
                $('.pointu').css('fill-opacity','0');
            }
            biodiv= result.data * 100;
        },
        error: function () {
        }});

    $.ajax({
        url: "/water-quality",
        type: "POST",
        data: { country: country, date : date},
        success: function(result){
           $('.wave2').css('animation','wave-animation1 '+result.data/10 +'s infinite linear')
           waterqual  = result.data;
        },
        error: function () {
        }});


    var globalindex = (airqual + waterqual + biodiv );
   console.log(globalindex);
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

$(function(){
    $('.button-water').on('click', function(){
        $('.overlay').addClass('show');
        $('.modal').addClass('show');


        var country = $('.country-element').attr('id');
        var date = current_date;

        $.ajax({
            url: "/related-article",
            type: "POST",
            data: { country: country, date : date, type: "waterPollution"},
            success: function(result){
                console.log(result);
               $('.modal__content-val').html(result);
            },
            error: function () {
            }});
    });

    $('.button-trees').on('click', function(){
        $('.overlay').addClass('show');
        $('.modal').addClass('show');
        $('.content__article').css('display','flex');


        var country = $('.country-element').attr('id');
        var date = current_date;

        $.ajax({
            url: "/related-article",
            type: "POST",
            data: { country: country, date : date, type: "biodiversity"},
            success: function(result){
                console.log(result);
                $('.modal__content-val').html(result);
            },
            error: function () {
            }});
    });

    $('.button-clouds').on('click', function(){
        $('.overlay').addClass('show');
        $('.modal').addClass('show');
        $('.content__article').css('display','flex');


        var country = $('.country-element').attr('id');
        var date = current_date;

        $.ajax({
            url: "/related-article",
            type: "POST",
            data: { country: country, date : date, type: "airQualiy"},
            success: function(result){
                console.log(result);
                $('.modal__content-val').html(result);
            },
            error: function () {
            }});
    });

    $('.button-sun').on('click', function(){
        $('.overlay').addClass('show');
        $('.modal').addClass('show');
        $('.content__article').css('display','flex');

        var country = $('.country-element').attr('id');
        var date = current_date;

        $.ajax({
            url: "/related-article",
            type: "POST",
            data: { country: country, date : date, type: "climat"},
            success: function(result){
                console.log(result);
                $('.modal__content-val').html(result);
            },
            error: function () {
            }});
    });

    $('.overlay').on('click', function(){
        $(this).removeClass('show');
        $('.modal').removeClass('show');
        $('.content__article').css('display','none');
    })

    $('.arrow').on('click', function () {
        $('.overlay').removeClass('show');
        $('.modal').removeClass('show');
    })
});


