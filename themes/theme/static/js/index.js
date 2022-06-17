window.addEventListener("load", function () {
    var splide = document.createElement("script");
    splide.src = "/wp-content/themes/theme/static/js/swiper.min.js";
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

        new Swiper('.homeWorks-slider', {
            loop: true,
            draggable: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            spaceBetween: 80,
        });


    };
    document.body.appendChild(splide);
});