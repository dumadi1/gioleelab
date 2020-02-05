<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['save'])){
    $user=trim($_POST['customer']);
    $sampleid=trim($_POST['id']);
    $stage=trim($_POST['stage']);
    $method=trim($_POST['method']);
    // $address=mysqli_real_escape_string($con,trim($_POST['address']));
   // echo $method;
    if(strcmp($method,"1")==0){
 $email=trim($_POST['email']);
 //echo $email;
 $message=$_POST['message'];

doemail($email,$message);



}else if(strcmp($method,"2")==0){
$number=$_POST['number'];
$message=$_POST['message'];
donumber($number,$message);


    }else if(strcmp($method,"3")==0){



$email=trim($_POST['email']);
$number=$_POST['number'];
$message=$_POST['message'];
doboth($email,$number,$message);
// echo $email;
// echo $number;
    }


}
function doboth($email,$number,$message){

    $json_url = "http://api.ebulksms.com:8080/sendsms.json";
    $xml_url = "http://api.ebulksms.com:8080/sendsms.xml";
    $http_get_url = "http://api.ebulksms.com:8080/sendsms";
    $username = "klintmgmt@gmail.com";
    $apikey = "482e3668f96d7705b583cc20a7596c840f79cfd2";
    
      
        $sendername = "GioleeLab";
        $recipients = $number;
        //echo gettype($recipients);
        $flash = 0;
    
    $result = useJSON($json_url, $username, $apikey, $flash, $sendername, $message, $recipients);

    doemail($email,$message);

}
function doemail($email,$message){



    $mail = new PHPMailer(true);

    try {
        //Server settings
       // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'gioleelab@gmail.com';                     // SMTP username
        $mail->Password   = 'ymzwmhmqioanjuxz';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom('gioleelab@gmail.com', 'Giolee Lab');
        $mail->addAddress($email, 'Esteemed Client');     // Add a recipient
        
    
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Sample Update';
        $mail->Body    = $message;
        $mail->AltBody = $message;
    
        $mail->send();
        echo 'Message has been sent';
        header("Location: /dashboard/sendupdate.php");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
}

function useJSON($url, $username, $apikey, $flash, $sendername, $messagetext, $recipients) {
    $gsm = array();
    $country_code = '234';
    $arr_recipient = explode(',', $recipients);
    foreach ($arr_recipient as $recipient) {
        $mobilenumber = trim($recipient);
        if (substr($mobilenumber, 0, 1) == '0'){
            $mobilenumber = $country_code . substr($mobilenumber, 1);
        }
        elseif (substr($mobilenumber, 0, 1) == '+'){
            $mobilenumber = substr($mobilenumber, 1);
        }
        $generated_id = uniqid('int_', false);
        $generated_id = substr($generated_id, 0, 30);
        $gsm['gsm'][] = array('msidn' => $mobilenumber, 'msgid' => $generated_id);
    }
    $message = array(
        'sender' => $sendername,
        'messagetext' => $messagetext,
        'flash' => "{$flash}",
    );
 
    $request = array('SMS' => array(
            'auth' => array(
                'username' => $username,
                'apikey' => $apikey
            ),
            'message' => $message,
            'recipients' => $gsm
    ));
    $json_data = json_encode($request);
    echo $json_data;
    if ($json_data) {
        $response = doPostRequest($url, $json_data, array('Content-Type: application/json'));
        $result = json_decode($response);
        return $result->response->status;
    } else {
        return false;
    }
}


function doPostRequest($url, $arr_params, $headers = array('Content-Type: application/x-www-form-urlencoded')) {
    $response = array();
    $final_url_data = $arr_params;
    if (is_array($arr_params)) {
        $final_url_data = http_build_query($arr_params, '', '&');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $final_url_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response['body'] = curl_exec($ch);
    $response['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo $response['body'];
    return $response['body'];
}
function useXML($url, $username, $apikey, $flash, $sendername, $messagetext, $recipients) {
    $country_code = '234';
    $arr_recipient = explode(',', $recipients);
    $count = count($arr_recipient);
    $msg_ids = array();
    $recipients = '';
 
    $xml = new SimpleXMLElement('<SMS></SMS>');
    $auth = $xml->addChild('auth');
    $auth->addChild('username', $username);
    $auth->addChild('apikey', $apikey);
 
    $msg = $xml->addChild('message');
    $msg->addChild('sender', $sendername);
    $msg->addChild('messagetext', $messagetext);
    $msg->addChild('flash', $flash);
 
    $rcpt = $xml->addChild('recipients');
    for ($i = 0; $i < $count; $i++) {
        $generated_id = uniqid('int_', false);
        $generated_id = substr($generated_id, 0, 30);
        $mobilenumber = trim($arr_recipient[$i]);
        if (substr($mobilenumber, 0, 1) == '0') {
            $mobilenumber = $country_code . substr($mobilenumber, 1);
        } elseif (substr($mobilenumber, 0, 1) == '+') {
            $mobilenumber = substr($mobilenumber, 1);
        }
        $gsm = $rcpt->addChild('gsm');
        $gsm->addchild('msidn', $mobilenumber);
        $gsm->addchild('msgid', $generated_id);
    }
    $xmlrequest = $xml->asXML();
 
    if ($xmlrequest) {
        $result = doPostRequest($url, $xmlrequest, array('Content-Type: application/xml'));
        $xmlresponse = new SimpleXMLElement($result);
        return $xmlresponse->status;
    }
    return false;
}
 
//Function to connect to SMS sending server using HTTP GET
function useHTTPGet($url, $username, $apikey, $flash, $sendername, $messagetext, $recipients) {
    $query_str = http_build_query(array('username' => $username, 'apikey' => $apikey, 'sender' => $sendername, 'messagetext' => $messagetext, 'flash' => $flash, 'recipients' => $recipients));
    return file_get_contents("{$url}?{$query_str}");
}

function donumber($number,$message){

    $json_url = "http://api.ebulksms.com:8080/sendsms.json";
    $xml_url = "http://api.ebulksms.com:8080/sendsms.xml";
    $http_get_url = "http://api.ebulksms.com:8080/sendsms";
    $username = "klintmgmt@gmail.com";
    $apikey = "482e3668f96d7705b583cc20a7596c840f79cfd2";
    
      
        $sendername = "GioleeLab";
        $recipients = $number;
        //echo gettype($recipients);
        $flash = 0;
    
    $result = useJSON($json_url, $username, $apikey, $flash, $sendername, $message, $recipients);
    sleep(1.5);
        header("Location: /dashboard/sendupdate.php");
        exit();

}
?>
