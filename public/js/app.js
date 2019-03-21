$( document ).ready(function() {
    fetchdata('fr','0');
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
            console.log("error");
        }});

    // AIRQUALITY
    $.ajax({
        url: "/carbonEvolution",
        type: "POST",
        data: { country: country},
        success: function(result){
            calculatedResult = result.data.data.carbonIntensity;
            $('.sun').css('width',calculatedResult*2);
            $('.sun').css('height',calculatedResult*2);
            $('.sun').css('fill', 'rgb(255, 102, 51)');
            $('.sun').css('stroke', 'rgb(255, 102, 51)');
            $('.sun').css('fill-opacity', calculatedResult/100);
            $('.sun').css('stroke-opacity', calculatedResult/100);
        },
        error: function () {
            console.log("error");
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
            console.log("error");
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

var sheet = document.createElement('style'),
    $rangeInput = $('.range input'),
    prefs = ['webkit-slider-runnable-track', 'moz-range-track', 'ms-track'];

document.body.appendChild(sheet);

var getTrackStyle = function (el) {
    var curVal = el.value,
        val = (curVal - 1) * 25,
        style = '';

    // Set active label
    $('.range-labels li').removeClass('active selected');

    var curLabel = $('.range-labels').find('li:nth-child(' + curVal + ')');

    curLabel.addClass('active selected');
    curLabel.prevAll().addClass('selected');

    // Change background gradient
    for (var i = 0; i < prefs.length; i++) {
        style += '.range {background: linear-gradient(to right, #37adbf 0%, #37adbf ' + val + '%, #A8C9AC ' + val + '%, #A8C9AC 100%)}';
        style += '.range input::-' + prefs[i] + '{background: linear-gradient(to right, #37adbf 0%, #37adbf ' + val + '%, #000 ' + val + '%, #000 100%)}';
    }

    return style;
}

$rangeInput.on('input', function () {
    sheet.textContent = getTrackStyle(this);
});

// Change input value on label click
$('.range-labels li').on('click', function () {
    var index = $(this).index();

    $rangeInput.val(index + 1).trigger('input');
});

//get slider value
$('.by-range').on('click', function() {
    var valslider = $(this).data('month');
    console.log(valslider);
});
