$('#messageForm').submit(function(e){

    e.preventDefault();
    $('.form-error').css({ display: "none" })
    $('.form-success').css({ display: "none" })

    var errors = false;

    if ($('#recipEmail').val() == '') {
        $('.form-error').text('Please submit a valid email address')
        $('.form-error').css({ display: "block" })
        errors = true;
    }

    if ($('#message').val() == '') {
        $('.form-error').text('Please submit a message')
        $('.form-error').css({ display: "block" })
        errors = true;
    }

    if (!errors) {
        $.ajax({
            url: 'assets/php/processor.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function() {
                $('.form-success').text('Your message has been sent')
                $('.form-success').css({ display: "block" })
            },
            error: function() {
                $('.form-error').text('Something went wrong, please try again')
                $('.form-error').css({ display: "block" })
            }

        })
    }

    
});