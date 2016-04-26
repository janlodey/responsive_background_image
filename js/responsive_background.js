(function ($) {
    Drupal.behaviors.responsiveBackgroundBehavior = {
        attach: function (context, settings) {

            // see - http://dinbror.dk/blazy/ for documentation on b-lazy plugin
            var bLazy = new Blazy({
                offset: 1500,
                breakpoints: [
                    {
                        width: 340, // max-width
                        src: 'data-src-mobile'
                    },
                    {
                        width: 769, // max-width
                        src: 'data-src-tablet'
                    }
                ],
                success: function(element){
                    setTimeout(function(){
                        // We want to remove the loader gif now.
                        // First we find the parent container
                        // then we remove the "loading" class which holds the loader image
                        var parent = element.parentNode;
                        parent.className = parent.className.replace(/\bloading\b/,'');
                    }, 200);
                }
            });

        }
    };
})(jQuery);
