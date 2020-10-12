$(function() {
    
    // Get the form.
    var form = $('#contact_form');

    // Get the messages div.
    var formMessages = $('#form-messages');
    
	//$('#contact_form').submit(function(e)
	$(form).submit(function(e)
    {
        //stop the browser from submitting the form
        e.preventDefault();
       
        //var formMessages = $('#form-messages');

        $formSubmitted = $(this);
        
        //show some response on the button
        $('button[type="submit"]',  $formSubmitted).each(function()
        {
            $btn = $(this);
            $btn.prop('type','button' ); 
            $btn.prop('orig_label',$btn.text());
            $btn.text('Sending ...');
        });
      

        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: $(form).serialize()
        })
      
        .done(function(response) {
            // Make sure that the formMessages div has the 'success' class.
            $(formMessages).removeClass('alert-danger');
            $(formMessages).addClass('alert-success');

            // Set the message text.
            $(formMessages).text(response);

            // Clear the form.
            $('#name').val('');
            $('#email').val('');
            $('#message').val('');
            
            //hide the form
            $('#contact_form').hide();


        })
        
        .fail(function(data) {
            // Make sure that the formMessages div has the 'error' class.
            $(formMessages).removeClass('alert-success');
            $(formMessages).addClass('alert-danger');

            // Set the message text.
            if (data.responseText !== '') {
                $(formMessages).text(data.responseText);
            } else {
                $(formMessages).text('<strong>Oops!</strong> An error occured and your message could not be sent.');
            }
            
            //reverse the response on the button
            $('button[type="button"]', $formSubmitted).each(function()
            {
                $btn = $(this);
                label = $btn.prop('orig_label');
                if(label)
                {
                    $btn.prop('type','submit' );
                    $btn.text(label);
                    $btn.prop('orig_label','');
                }
            });

        });
        
    });	
});
