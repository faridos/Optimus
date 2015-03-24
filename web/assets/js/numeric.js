(function ($) {
    $.fn.forceNumeric = function () {
        return this.each(function () {
 
            $(this).keyup(function() {
                if (!/^[0-9]+$/.test($(this).val())) {
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
                }
            });
        });
    };
})(jQuery);