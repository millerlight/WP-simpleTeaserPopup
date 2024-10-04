jQuery(document).ready(function($) {
    // Function to resize both the animated headline and content text dynamically
    function adjustTextSizes() {
        var popupWidth = $('#simple-teaser-popup').width();

        // Adjust font size of animated headline (2% of popup width)
        var newHeadlineSize = popupWidth * 0.04; // 4% of the popup width for the headline
        $('.popup-content h2').css('font-size', newHeadlineSize + 'px');

        // Adjust font size of content text (1.5% of popup width)
        var newContentSize = popupWidth * 0.02; // 2% of the popup width for the content text
        $('.popup-text-block').css('font-size', newContentSize + 'px');
    }

    // Call the function when the popup is first shown
    setTimeout(function() {
        $('#simple-teaser-popup').fadeIn();
        adjustTextSizes(); // Adjust sizes when the popup appears
    }, 1000); // Adjust the delay as needed

    // Adjust the text size whenever the window is resized
    $(window).resize(function() {
        adjustTextSizes(); // Dynamically resize both headline and content text when resizing
    });

    // Close the popup when the close button is clicked
    $('.close-popup').click(function() {
        $('#simple-teaser-popup').fadeOut();
    });
});
