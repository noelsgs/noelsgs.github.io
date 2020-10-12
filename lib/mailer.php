<?php
  require_once "recaptchalib.php";

  //only process POST 
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      
    $secret = "6LeJZWYUAAAAAHPMgp1xOZ-JVVGC6i1_Kzk-O498";

    // The response from reCAPTCHA
    $resp = null;
    // The error code from reCAPTCHA, if any
    $error = null;
    $reCaptcha = new ReCaptcha($secret);
    // Was there a reCAPTCHA response?
    if ($_POST["g-recaptcha-response"]) {
        $resp = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }

    if ($resp != null && $resp->success) {
          
      $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
      $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
      $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
      
      if(!preg_match("/^[A-Za-z .'-]+$/", $name)){
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission (Invalid name). Please complete the form and try again.";
        exit;
      }
      
      if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $email)){
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission (Invalid email). Please complete the form and try again.";
        exit;
      }
      
      if(strlen($message) === 0){
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission (Empty message). Please complete the form and try again.";
        exit;
      }
      
      // Set the recipient email address.
      $recipient = "noelsgardeningservices@gmail.com";
   
      // Set the email subject.
      $subject = "New contact from $name";
   
      // Build the email content.
      $email_content = "Hi Noel,\nYou have a new message from your website.\n";
      $email_content .= "Name: $name\n";
      $email_content .= "Email: $email\n\n";
      $email_content .= "Message:\n$message\n";
   
      // Build the email headers.
      $email_headers = "Reply-To: $name <$email>";
   
      // Send the email.
      if (mail($recipient, $subject, $email_content, $email_headers)) {
          // Set a 200 (okay) response code.
          http_response_code(200);
          echo "Thank You! Your message has been sent.";
      } else {
          // Set a 500 (internal server error) response code.
          http_response_code(500);
          echo "Oops! Something went wrong and we couldn't send your message.";
      }
    }
    else{
      // Failed reCapture, set a 403 (forbidden) response code.
      http_response_code(403);
      echo "reCapture failed, please try again.";
    }


  } else {
  
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";

    // This is in the PHP file and sends a Javascript alert to the client
    //$message = "not POST";
    //echo "<script type='text/javascript'>alert('$message');</script>";
  }

?>