const app = require('./app');

global.$ = $;


$(document).ready(function(){
    $(window).on('load', function() {
        setTimeout(() => {
            $(".loading-overlay").hide(0, function(){
                $(this).remove();
                removeBodyClass();
            })
        }, 2900);
    });
    function removeBodyClass() {
        $('body').removeClass("bodyActive");
    }

    setTimeout(() => {
        $(".login").addClass('loginActive');
    }, 3200)
    setTimeout(() => {
        $(".login__image").animate({
            opacity: 1,
        }, 500, function() {
            // Animation complete.
        });
    }, 4700)
    setTimeout(() => {
        $(".login__content").show()
    }, 5200)
});



// const hamburger = document.querySelector(".hamburger");
// const navLinks = document.querySelector(".nav__items");
// const body = document.querySelector("body");
// hamburger.addEventListener("click", () => {
//     navLinks.classList.toggle("active");
//     hamburger.classList.toggle("actived");
//         setTimeout(() => {
//             document.querySelector(".socialmedia").classList.toggle("socialActive");
//             //body.classList.toggle("bodyActive")
            
//         }, 400);
// })

// const logo = document.querySelectorAll('#logo path');
// for(let i = 0; i < logo.length; i++) {
//     console.log(`Letter ${i} is ${logo[i].getTotalLength()}`)
// }



/** Typed js */

// var options = {
//     strings: ['Frontend', 'Backend'],
//     typeSpeed: 100,
//     backSpeed: 60,
//     loop: true
//   };
  
// var typed = new Typed('.typedone', options);


/** scroll menu sticky */

window.addEventListener("scroll", function() {
    var header = document.querySelector("header");
    header.classList.toggle("sticky", window.scrollY > 0);
})



/** model remove */

$(document).ready(function(){
    $(".fa-times").click(function() {
        console.log("hi");
        $(".overlay-back").remove();
        $(".model").remove();
    });

})




// var options = {
//     strings: ['Welcome to my portfolio, Thank you for your trust!',
//             'Subscribe if you wanna see more!'],
//     typeSpeed: 60,
//     loop: false
//   };

// setTimeout(() => {
//     var typed = new Typed('#modelh3', options);
// }, 4200);
