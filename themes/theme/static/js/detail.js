window.addEventListener("load", function () {
    var splide = document.createElement("script");
    splide.src = "/js/swiper.min.js";
    splide.onload = function () {

        if(document.querySelector('.detailBanner-text__features')){
            new Swiper(".detailBanner-text__features", {
                draggable: true,
                spaceBetween: 20,
                centeredSlides: true,
                slidesPerView: 1.5,
                breakpoints: {
                    480: {
                        slidesPerView: 2,
                        centeredSlides: false,
                    },
                    640: {
                        slidesPerView: 3,
                        centeredSlides: false,
                    },
                    1152: {
                        slidesPerView: "auto",
                        centeredSlides: false,
                        spaceBetween: 40,
                    },
                }
            });
        }
        if(document.querySelector('.detailResult-slider')){
            new Swiper(".detailResult-slider", {
                loop: true,
                draggable: true,
                pagination: {
                    el: ".swiper-pagination",
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                spaceBetween: 80,
            });
        }
        if(document.querySelector('.homeRatings-slider')){
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
        }
        if(document.querySelector('.homeClients-slider')){
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
        }
        if(document.querySelector('.homeCertificates-slider')){
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
                    1290:{
                        slidesPerView: 4,
                        spaceBetween: 50,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                    }
                }
            });
        }
        if(document.querySelector('.compare-links.swiper')){
            new Swiper(".compare-links.swiper", {
                draggable: true,
                spaceBetween: 0,
                slidesPerView: "auto",
            });
        }

        var interiors, recommend;

        if (window.innerWidth < 640) {

            interiors = new Swiper(".homeInterior-slider", {
                draggable: true,
                slidesPerView: 1,
                grid: {
                    rows: 2,
                    fill: "row",
                },
                spaceBetween: 20,
            });

            recommend = new Swiper(".recommend-feature__items", {
                draggable: true,
                spaceBetween: 20,
                centeredSlides: true,
                slidesPerView: 1.25,
                breakpoints: {
                    480: {
                        slidesPerView: 2,
                        centeredSlides: false,
                    },
                    640: {
                        slidesPerView: 3,
                        centeredSlides: false,
                    },
                    1152: {
                        slidesPerView: 3,
                        centeredSlides: false,
                        spaceBetween: 40,
                    },
                }
            });
        }

        window.addEventListener("resize", function () {
            if (window.innerWidth < 640 && interiors === undefined) {
                interiors = new Swiper(".homeInterior-slider", {
                    draggable: true,
                    slidesPerView: 1,
                    grid: {
                        rows: 2,
                        fill: "row",
                    },
                    spaceBetween: 20,
                });
            } else if (window.innerWidth > 640 && interiors !== undefined) {
                interiors.destroy();
                interiors = undefined;
            }

            if (window.innerWidth < 640 && recommend === undefined) {
                recommend = new Swiper(".recommend-feature__items", {
                    draggable: true,
                    spaceBetween: 20,
                    centeredSlides: true,
                    slidesPerView: 1.25,
                    breakpoints: {
                        480: {
                            slidesPerView: 2,
                            centeredSlides: false,
                        },
                        640: {
                            slidesPerView: 3,
                            centeredSlides: false,
                        },
                        1152: {
                            slidesPerView: 3,
                            centeredSlides: false,
                            spaceBetween: 40,
                        },
                    }
                });
            } else if (window.innerWidth > 640 && recommend !== undefined) {
                recommend.destroy();
                recommend = undefined;
            }

        });


    };
    document.body.appendChild(splide);

    var target_date = new Date().getTime() + (1000 * 3600 * 48);
    var days, hours, minutes, seconds;

    var countdown = document.getElementById("timer");

    if(countdown){
        getCountdown();

        setInterval(function () {
            getCountdown();
        }, 1000);
    }

    function getCountdown() {

        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;

        days = pad(parseInt(seconds_left / 86400));
        seconds_left = seconds_left % 86400;

        hours = pad(parseInt(seconds_left / 3600));
        seconds_left = seconds_left % 3600;

        minutes = pad(parseInt(seconds_left / 60));
        seconds = pad(parseInt(seconds_left % 60));

        countdown.querySelector(".days .value").innerHTML = "<span>" + days[0] + "</span><span>" + days[1] + "</span>";
        countdown.querySelector(".hours .value").innerHTML = "<span>" + hours[0] + "</span><span>" + hours[1] + "</span>";
        countdown.querySelector(".minutes .value").innerHTML = "<span>" + minutes[0] + "</span><span>" + minutes[1] + "</span>";
        countdown.querySelector(".seconds .value").innerHTML = "<span>" + seconds[0] + "</span><span>" + seconds[1] + "</span>";

    }

    function pad(n) {
        return (n < 10 ? "0" : "") + n;
    }
});

var imageMarker = document.querySelectorAll(".detailUsage-items__item .list-ul li[data-part]");
imageMarker.forEach(function (el) {
    el.addEventListener("mouseenter", function () {
        var id = this.dataset.part;
        var path = document.querySelector(".detailUsage-items__item .image-frame__dot[data-part=\"" + id + "\"");
        path.classList.add("scale");

    });
    el.addEventListener("mouseleave", function () {
        var id = this.dataset.part;
        var path = document.querySelector(".detailUsage-items__item .image-frame__dot[data-part=\"" + id + "\"");
        path.classList.remove("scale");
    });
});