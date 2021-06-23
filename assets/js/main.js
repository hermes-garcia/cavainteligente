!(function($) {
    "use strict";
    let preloader = $('#preloader');
    let header = $('#header');
    let navSections = $('section');
    let navMenu = $('.nav-menu');

    $(window).on('load', function() {
        if (preloader.length) {
            preloader.fadeOut('slow', function() {
                $(this).remove();
            });
        }
    });

    $(document).on('click', '.nav-menu a, .scrollto, .inline-ref', function(e) {
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            e.preventDefault();
            let target = $(this.hash);
            if (target.length) {
                let scrollTo = target.offset().top;
                if (header.length) {
                    scrollTo -= header.outerHeight()
                }

                if ($(this).attr("href") === '#header') {
                    scrollTo = 0;
                }

                $('html, body').animate({
                    scrollTop: scrollTo
                }, 1500, 'easeInOutExpo');

                if ($(this).parents('.nav-menu').length) {
                    $('.nav-menu .active').removeClass('active');
                    $(this).closest('li').addClass('active');
                }
                return false;
            }
        }
    });

    $(window).scroll(function() {
        let currentPosition = $(this).scrollTop() + 200;
        navSections.each(function() {
            let top = $(this).offset().top,
                bottom = top + $(this).outerHeight();

            if (currentPosition >= top && currentPosition <= bottom) {
                if (currentPosition <= bottom) {
                    navMenu.find('li').removeClass('active');
                }
                navMenu.find('a[href="#' + $(this).attr('id') + '"]').parent('li').addClass('active');
            }
        });

        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
            header.addClass('header-scrolled');
        } else {
            $('.back-to-top').fadeOut('slow');
            header.removeClass('header-scrolled');
        }
    });

    $('.back-to-top, .logo').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 1500, 'easeInOutExpo');
        return false;
    });

    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 1000
    });

})(jQuery);