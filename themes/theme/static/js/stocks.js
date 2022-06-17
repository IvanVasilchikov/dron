window.addEventListener("load", function () {
    var splide = document.createElement("script");
    splide.src = "/js/swiper.min.js";
    splide.onload = function () {

        var interiors;

        if (window.innerWidth < 641) {
            interiors = new Swiper('.homeInterior-slider', {
                draggable: true,
                slidesPerView: 1,
                grid: {
                    rows: 2,
                    fill: 'row',
                },
                spaceBetween: 20,
            });
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth < 641 && interiors === undefined) {
                interiors = new Swiper('.homeInterior-slider', {
                    draggable: true,
                    slidesPerView: 1,
                    grid: {
                        rows: 2,
                        fill: 'row',
                    },
                    spaceBetween: 20,
                });
            } else if (window.innerWidth > 641 && interiors !== undefined) {
                interiors.destroy();
                interiors = undefined;
            }
        })

        new Swiper('.homeRatings-slider', {
            spaceBetween: 20,
            slidesPerView: 2,
            draggable: true,
            centerInsufficientSlides: true,
            breakpoints: {
                480: {
                    slidesPerView: 3,
                },
                640: {
                    slidesPerView: 3.5,
                },
                830: {
                    slidesPerView: 5,
                }
            }
        });

        new Swiper('.homeClients-slider', {
            draggable: true,
            loop: true,
            pagination: {
                el: ".swiper-pagination",
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            spaceBetween: 80,
        });

        new Swiper('.homeCertificates-slider', {
            loop: true,
            draggable: true,
            slidesPerView: 2,
            spaceBetween: 20,
            centerInsufficientSlides: true,
            watchSlidesProgress: true,
            breakpoints: {
                640: {
                    slidesPerView: 3,
                },
                830: {
                    slidesPerView: 4,
                },
                1290: {
                    slidesPerView: 4,
                    spaceBetween: 50,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                }
            }
        });


    };
    document.body.appendChild(splide);

    var target_date = new Date().getTime() + (1000 * 3600 * 48); // set the countdown date
    var days, hours, minutes, seconds; // variables for time units

    var timers = document.querySelectorAll('.fleets-banner__timer');
    timers.forEach(function (el){
        getCountdown(el);
        setInterval(function () {
            getCountdown(el);
        }, 1000);
    })

    function getCountdown(el) {

        // find the amount of "seconds" between now and target
        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;

        days = pad(parseInt(seconds_left / 86400));
        seconds_left = seconds_left % 86400;

        hours = pad(parseInt(seconds_left / 3600));
        seconds_left = seconds_left % 3600;

        minutes = pad(parseInt(seconds_left / 60));
        seconds = pad(parseInt(seconds_left % 60));

        el.querySelector(".days .value").innerHTML = "<span>" + days[0] + "</span><span>" + days[1] + "</span>";
        el.querySelector(".hours .value").innerHTML = "<span>" + hours[0] + "</span><span>" + hours[1] + "</span>";
        el.querySelector(".minutes .value").innerHTML = "<span>" + minutes[0] + "</span><span>" + minutes[1] + "</span>";
        el.querySelector(".seconds .value").innerHTML = "<span>" + seconds[0] + "</span><span>" + seconds[1] + "</span>";

    }

    function pad(n) {
        return (n < 10 ? "0" : "") + n;
    }

});