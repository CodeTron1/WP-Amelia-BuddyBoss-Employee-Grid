/* AB Employee Grid and Carousel - Admin JavaScript v3.0.0 */
jQuery(document).ready(function($) {
    // Shortcode copy functionality
    $(document).on('click', 'input[readonly]', function() {
        $(this).select();
        try {
            document.execCommand('copy');
            // Show temporary success message
            var $this = $(this);
            var originalBg = $this.css('background-color');
            $this.css('background-color', '#d4edda');
            setTimeout(function() {
                $this.css('background-color', originalBg);
            }, 1000);
        } catch (err) {
            console.log('Copy to clipboard failed');
        }
    });

    // Tab functionality for settings page
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $('.abegc-tab-content').hide();
        $(this).addClass('nav-tab-active');
        $($(this).attr('href')).show();
    });

    // Range value display
    $('input[type="range"]').on('input', function() {
        $(this).next('.abegc-range-value').text($(this).val() + '%');
    });

    // Color picker enhancements
    if (typeof wpColorPicker !== 'undefined') {
        $('.color-field').wpColorPicker();
    }
});
