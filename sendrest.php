<?php

function bluemail_get_vcap_variable( $variable ){
    $vcap = getenv("VCAP_SERVICES");
    $data = json_decode($vcap, true);

    return $data[$variable]['0'];   //Get back the vcap variable you asked for.
}

function bluemail_get_URI() {

    $bluemail = bluemail_get_vcap_variable('bluemailservice');
    $creds = $bluemail['credentials'];
    $emailUrl = $creds['emailUrl'];
    $username = $creds['username'];
    $password = $creds['password'];
    $URI = 'https://' . $username . ':' . $password . '@' . substr($emailUrl,8);

    return $URI;
}

function sendrestmail($to, $subject, $message, $headers) {

    // Get the full email URI with username and password from the VCAP_SERICES environment variable
    $request = bluemail_get_URI();
    $sender='tsnyder@ca.ibm.com';
    $recipients=array(
        'recipient' => $to,
    );
    $params = json_encode(array(
        'contact'    => $sender,
        'recipients' => [$recipients],
        'subject'    => $subject,
        'message'    => $message,
    ));
    $curlheaders = array(
          'Content-Type: application/json',
    );

    // $file2 = 'RESTparams.json';
    // file_put_contents($file2, $params);


    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl that this is the json header type
    curl_setopt ($session, CURLOPT_HTTPHEADER, $curlheaders);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    // obtain response
    $response = curl_exec($session);
    curl_close($session);

    // Capture the REST response of only the most recent send. This writes to the ~/app/htdocs/wp-admin folder.
    $file3 = 'RESTresponse.log';
    file_put_contents($file3, $response);
}

?>
