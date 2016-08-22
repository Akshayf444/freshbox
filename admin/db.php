<?php

////PHPMAILER
require_once(dirname(__FILE__) . '/phpMailer/class.phpmailer.php');
require_once(dirname(__FILE__) . '/phpMailer/class.smtp.php');

$url = explode("/", $_SERVER['PHP_SELF']);
array_pop($url);
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . join("/", $url) . '/');
//echo BASE_URL;
///Start Session
if (!isset($_SESSION) && empty($_SESSION)) {
    session_start();
}

global $con;
$con = mysqli_connect('localhost', 'root', '', 'freshbox');

function sanitized_attributes($field_array) {
    $clean_attributes = array();
    foreach ($field_array as $key => $value) {
        $clean_attributes[$key] = escape_value($value);
    }
    return $clean_attributes;
}

function escape_value($value) {
    global $con;
    $value = mysqli_real_escape_string($con, $value);
    return $value;
}

function create($field_array, $table_name) {

    $attributes = sanitized_attributes($field_array);
    $sql = "INSERT INTO " . $table_name . " (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";

    if (query($sql)) {
        return insert_id();
    } else {
        return false;
    }
}

function query($sql) {
    global $con;
    $result = mysqli_query($con, $sql);
    return $result;
}

function insert_id() {
    global $con;
    return mysqli_insert_id($con);
}

function update($field_array, $table_name) {
    global $con;
    $attributes = sanitized_attributes($field_array);
    $attribute_pairs = array();
    foreach ($attributes as $key => $value) {
        $attribute_pairs[] = "{$key}='{$value}'";
    }
    $sql = "UPDATE " . $table_name . " SET ";
    $sql .= join(", ", $attribute_pairs);
    $sql .= " WHERE " . key($field_array) . " = " . current($field_array);
    echo $sql;
    query($sql);
}

function delete($field_array, $table_name) {
    $sql = "DELETE FROM " . $table_name . " WHERE " . key($field_array) . " = " . current($field_array);
    if (query($sql)) {
        return true;
    } else {
        return false;
    }
}

function result($result) {
    $object = array();
    while ($row = mysqli_fetch_object($result)) {
        $object[] = $row;
    }
    return $object;
}

function getVideo($limit = "", $offset = "") {
    $sql = "SELECT * FROM video ";
    $sql .= $limit != '' ? " LIMIT " . $limit : "";
    $sql .= $offset != '' ? " OFFSET " . $offset : "";
    $result = query($sql);
    return $result;
}

function countVideo() {
    $sql = "SELECT count(*) as videocount FROM video ";
    $result = query($sql);
    $result = result($result);
    return !empty($result) ? array_shift($result) : 0;
}

function loadview($view = array()) {

    include_once './header.php';
    foreach ($view as $value) {
        include_once($value . '.php');
    }
    include_once './footer.php';
}

function deleteVideo($video_id = 0) {
    $sql = "DELETE FROM video WHERE id = {$video_id}";
    query($sql);
}

function authentication($username, $password) {
    $sql = "SELECT * FROM user WHERE username ='$username' AND password = '$password' ";
    $result = query($sql);
    $result = result($result);
    return !empty($result) ? array_shift($result) : FALSE;
}

function logout() {
    session_destroy();
    header('Location:index.php');
}

function sendsms($mobile, $messages) {
    //echo $mobile . ':' . $messages . '<br/>';
    $authKey = "78106A1u8VLmCC054cb666b";
    $mobileNumber = $mobile;
    $senderId = "FRSBOX";
    $message = $messages;
    $finalmessage = rawurlencode($message);
    $smsUser = 'manish';
    $smsPassword = '123456';

    //Define route
    $route = "4";
    //Prepare you post parameters
    $postData = array(
        'authkey' => $authKey,
        'mobiles' => $mobileNumber,
        'message' => $finalmessage,
        'sender' => $senderId,
        'route' => $route
    );

    //API URL
    $url = "https://control.msg91.com/sendhttp.php";

    // init the resource
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
    ));


    //Ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


    //get response
    $output = curl_exec($ch);

    //Print error if any
    if (curl_errno($ch)) {
        echo 'error:' . curl_error($ch);
    }

    curl_close($ch);

    //echo $output;

    return $output;
}

function sendmail($email, $name, $message) {

    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP

    try {
        $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the server
        $mail->Host = "smtpout.asia.secureserver.net";      // sets the SMTP server
        $mail->Port = 465;                   // set the SMTP port for the MAIL server
        $mail->Username = "bisupport@instacom.in";  //  username
        $mail->Password = "bi@123$%^";            // password

        $mail->FromName = "BI-Tracking";
        $mail->From = "bisupport@instacom.in";
        $mail->AddAddress($email, $name);
        $mail->Subject = "BI-Tracking Login Details";

        $mail->IsHTML(true);

        $mail->Body = <<<EMAILBODY
{$message}
EMAILBODY;

        $mail->Send();
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
}

function paging($perpage = 1, $totalcount = 0) {
    $totalpages = floor($totalcount / $perpage);
    return $totalpages;
}

function renderPaging($pages, $url) {
    if ($pages > 0) {
        echo '<div class="right">';
        for ($i = 1; $i <= $pages; $i++) {
            echo '<a href="' . $url . $i . '">' . $i . '</a>';
        }
        echo '</div>';
    }
}

function offset($currentpage, $perpage) {
    return ($currentpage - 1) * $perpage;
}
