//Remove animations on load
window.onload = function () {
    document.querySelector('body').classList.remove('perf-no-animation');
}

//Mobile menu init
function mobileMenu() {
    var toggle = document.querySelector('.header-burger .burger');
    var menu = document.querySelector('.mobileMenu');
    var body = document.querySelector('body');

    this.onToggle = function () {
        toggle.classList.toggle('open');
        menu.classList.toggle('opened');
        body.classList.toggle('mobile');

    }
}

var mobileMenu = new mobileMenu();

document.querySelector('.header-burger .burger').addEventListener('click', function (e) {
    e.preventDefault();
    mobileMenu.onToggle();
});

var navLinks = document.querySelectorAll('.mobileMenu-nav__ul li a');
for (var i = 0; i < navLinks.length; i++) {
    navLinks[i].addEventListener('click', function () {
        mobileMenu.onClose();
    });
}

var navToggle = document.querySelectorAll('.mobileMenu-nav__ul .has-submenu__toggle');
for (var i = 0; i < navToggle.length; i++) {
    navToggle[i].addEventListener('click', function () {
        this.parentElement.classList.toggle('is-active');
    });
}

//Browser-level image lazy-loading
if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[loading="lazy"]');
    for (var i = 0; i < images.length; i++) {
        images[i].src = images[i].dataset.src;
    }
}
else {
    const script = document.createElement('script');
    script.src = '/wp-content/themes/theme/static/js/lazysizes.min.js';
    document.body.appendChild(script);
}

function scrollTop() {
    if (window.pageYOffset > 0) {
        this.document.querySelector('.scroll-top').classList.add('is-active');
    }
    else {
        this.document.querySelector('.scroll-top').classList.remove('is-active');
    }
}

window.addEventListener("scroll", () => {
    scrollTop();
});

//Scroll to top btn
scrollTop();

//Load scripts after page load
window.addEventListener("load", function () {
    var select = document.createElement('script');
    select.src = "/wp-content/themes/theme/static/js/select.min.js";
    select.onload = function () {
        const selectCustom = new customSelect({
            selector: 'select'
        })
        selectCustom.init()
    }
    document.body.appendChild(select);
});

function getElementIndex(el) {
    return [...el.parentElement.children].indexOf(el);
}

var tabs= document.querySelectorAll('.tabs__block');
tabs.forEach(function (elem){
    var toggles = elem.querySelectorAll('.tabs__toggle');
    var contents = elem.querySelectorAll('.tabs__content');
    for (var i = 0; i < toggles.length; i++){
        toggles[i].addEventListener('click', function (){
            toggles.forEach(function (el){
                el.classList.remove('is-active');
            })
            this.classList.add('is-active');

            contents.forEach(function (el){
                el.classList.remove('is-active');
            })

            var index = getElementIndex(this);

            console.log(getElementIndex(this));

            contents[index].classList.add('is-active');

        })
    }
})

function initMap(){
    var maps = document.createElement('script');
    maps.src = "//api-maps.yandex.ru/2.1/?apikey=b0321ffe-52c4-4701-ad20-d7885ae820a6&lang=ru_RU";
    maps.onload = function (){
        ymaps.ready(init);
        function init(){
            var map = document.querySelector("#map");
            var myMap = new ymaps.Map("map", {
                center: [map.getAttribute('data-lat'), map.getAttribute('data-lon')],
                zoom: 15,
                controls: [],
            });
            var placemark = new ymaps.Placemark([map.getAttribute('data-lat'), map.getAttribute('data-lon')],{});
            myMap.geoObjects.add(placemark);
        }
    }
    document.body.appendChild(maps);
    window.removeEventListener('scroll', initMap);
}

function initMaska(){
    var maska = document.createElement('script');
    maska.src = "/wp-content/themes/theme/static/js/maska.js";
    maska.onload = function (){
        Maska.create('input[type="tel"]', {
            mask: '+7 (###) ###-##-##'
        });
        window.removeEventListener("click", initMaska)
    }
    document.body.appendChild(maska);
}

window.addEventListener('scroll', initMap);
window.addEventListener('click', initMaska);

//open popup
var popupLink = document.querySelectorAll('a[data-popup]');
popupLink.forEach(function (element){
    element.addEventListener('click', function (e){
    })
})
//close popups
var popupClose = document.querySelectorAll('.popup__wrp');
popupClose.forEach(function (element){
    element.addEventListener('click', function (e){
        if(e.target !== e.currentTarget)
        {
            console.log('clicked on popup');
        }
        else{
            console.log('clicked on popup wrapper');
            window.location.href="#close";
        }
    });
});
