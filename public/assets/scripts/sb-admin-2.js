$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }



        /* Custom code by Samad
         The page-wrapper minimum height is defined by the height of the window's inner height,
         so that when the menu is big, the footer appears in the middle of the page.
         We'll set the minimum height to the menu's height, so that the footer can be seen immediately after the menu
         */
        checkDivExist = document.getElementById('MainNav');
        if(checkDivExist  == null){
            height = 0;
        }else{
            height = document.getElementById('MainNav').clientHeight;
        }
        if(height < 500){
            height = 600;
        }
        // end custom code

        // this is old code
        //height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});
