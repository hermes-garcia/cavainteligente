!(function($) {
    "use strict";
    let preloader = $('#preloader');
    let header = $('#header');
    let body = $('body');
    let navMenu = $('.nav-menu');

    $(window).on('load', function() {
        if (preloader.length) {
            preloader.fadeOut('slow', function() {
                $(this).remove();
            });
        }
    });

    $(document).on('click', '.nav-menu a, .mobile-nav a, .scrollto, .inline-ref', function(e) {
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            e.preventDefault();
            let target = $(this.hash);
            if (target.length) {

                let scrollto = target.offset().top;

                if (header.length) {
                    scrollto -= header.outerHeight()
                }

                if ($(this).attr("href") === '#header') {
                    scrollto = 0;
                }

                $('html, body').animate({
                    scrollTop: scrollto
                }, 1500, 'easeInOutExpo');

                if ($(this).parents('.nav-menu, .mobile-nav').length) {
                    $('.nav-menu .active, .mobile-nav .active').removeClass('active');
                    $(this).closest('li').addClass('active');
                }

                if (body.hasClass('mobile-nav-active')) {
                    body.removeClass('mobile-nav-active');
                    $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
                    $('.mobile-nav-overly').fadeOut();
                }
                return false;
            }
        }
    });

    if (navMenu.length) {
        let $mobile_nav = navMenu.clone().prop({
            class: 'mobile-nav d-lg-none'
        });
        body.append($mobile_nav);
        body.prepend('<button type="button" class="mobile-nav-toggle d-lg-none"><i class="icofont-navigation-menu"></i></button>');
        body.append('<div class="mobile-nav-overly"></div>');

        $(document).on('click', '.mobile-nav-toggle', function(e) {
            body.toggleClass('mobile-nav-active');
            $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
            $('.mobile-nav-overly').toggle();
        });

        $(document).on('click', '.mobile-nav .drop-down > a', function(e) {
            e.preventDefault();
            $(this).next().slideToggle(300);
            $(this).parent().toggleClass('active');
        });

        $(document).click(function(e) {
            let container = $(".mobile-nav, .mobile-nav-toggle");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                if (body.hasClass('mobile-nav-active')) {
                    body.removeClass('mobile-nav-active');
                    $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
                    $('.mobile-nav-overly').fadeOut();
                }
            }
        });
    } else if ($(".mobile-nav, .mobile-nav-toggle").length) {
        $(".mobile-nav, .mobile-nav-toggle").hide();
    }

    let nav_sections = $('section');
    let main_nav = $('.nav-menu, #mobile-nav');

    $(window).on('scroll', function() {
        let cur_pos = $(this).scrollTop() + 200;

        nav_sections.each(function() {
            let top = $(this).offset().top,
                bottom = top + $(this).outerHeight();

            if (cur_pos >= top && cur_pos <= bottom) {
                if (cur_pos <= bottom) {
                    main_nav.find('li').removeClass('active');
                }
                main_nav.find('a[href="#' + $(this).attr('id') + '"]').parent('li').addClass('active');
            }
            if (cur_pos < 300) {
                $(".nav-menu ul:first li:first").addClass('active');
            }
        });
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            header.addClass('header-scrolled');
        } else {
            header.removeClass('header-scrolled');
        }
    });

    if ($(window).scrollTop() > 100) {
        header.addClass('header-scrolled');
    }

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
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