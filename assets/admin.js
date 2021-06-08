
$(document).mouseup(function(e) 
{
    var container = $(".header__profile--menu");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.slideUp('fast');
    }
});

$(".li__a").on('click', function() {

    if($(".li__a").find("i").hasClass('activeRotate') && !$(this).find("i").hasClass('activeRotate')) {
        $(".li__a").next('ul').hide('fast'); 
        $(".li__a").find("i").removeClass('activeRotate', {duration:500})
    }
    $(this).next('ul').toggle('fast');
    $(this).find("i").toggleClass('activeRotate', {duration:500})


    console.log($(this).find("i").hasClass('activeRotate'));
})

$(".header__logo--burger").on('click', function() {
   if($(this).hasClass('activeBurger')) {
       $(this).removeClass('activeBurger');
       $(".content").removeClass('activeContent')
       $(".sidebar").removeClass('activeSidebare')
   } else {
        $(this).addClass('activeBurger');
        $(".content").addClass('activeContent')
        $(".sidebar").addClass('activeSidebare')
   }
})

$(".header__profile--image").on('click', function() {
   $('.header__profile--menu').slideDown('fast');
})