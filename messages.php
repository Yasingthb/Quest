<?php
function islem($results, $code, $message)
{
    $response = array(
        "content" => $results,
        "code" => $code,
        "message" => $message
    );
    return $response;
}

function function_alert($message) {
      
    // Display the alert box 
    echo "<script>alert('$message');</script>";
}
  
?>