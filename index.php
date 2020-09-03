<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LINEIN";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'XVkmOR4aT771B9CnIdxvdGmlOtXQSijnvLZ+T7GC5Hd8cVC8nKslvKPBTUs2M6vI5WhhF92i6S1NvR/ZY7IARrfIWCCZwo+ZYk6bzTnL9+ilJOWBlQyPXUvlZvgR5eE3a2KZ+C+hhDLn7bbiDVUJQgdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '5f02b5ca4a7aef50a0cb14673e155bd5';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array



if ( sizeof($request_array['events']) > 0 ) {

    foreach ($request_array['events'] as $event) {

        $reply_message = '';
        $reply_token = $event['replyToken'];
        
        $userID = $event['source']['userId'];
        $groupID = $event['source']['groupId'];
        $timestamp = $event['timestamp'];
        $text_S = $event['message']['text'];
        $text=$text_S;
        
        $data = [
            'replyToken' => $token,
             'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  
            //'messages' => [['type' => 'text', 'text' => $text_S ]]
        ];
        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

        echo "Result: ".$send_result."\r\n";
      
        if($text_S == "EU-1" || $text_S == "AU-1" || $text_S == "GU-1" || $text_S == "NU-1" || $text_S == "UCAD-1" || $text_S == "UCHF-1" || $text_S == "UJPY-1" ||
           $text_S == "eu-1" || $text_S == "au-1" || $text_S == "gu-1" || $text_S == "nu-1" || $text_S == "ucad-1" || $text_S == "uchf-1" || $text_S == "ujpy-1" ||
           $text_S == "EU-2" || $text_S == "AU-2" || $text_S == "GU-2" || $text_S == "NU-2" || $text_S == "UCAD-2" || $text_S == "UCHF-2" || $text_S == "UJPY-2" ||
           $text_S == "eu-2" || $text_S == "au-2" || $text_S == "gu-2" || $text_S == "nu-2" || $text_S == "ucad-2" || $text_S == "uchf-2" || $text_S == "ujpy-2" ){
        
            $x = 0;
            /**************************************************************************** */
            //if (err) throw err;
            //Select all customers and return the result object:
            $sql = "SELECT * FROM log";
            $result = $conn->query($sql);
            
            while($row = mysqli_fetch_assoc($result)) {
                
                $UDI = $row["UserID"];
                $GROUPID = $row["GroupID"];
                if($userID == $UDI){
                    $sql = "UPDATE log SET  Text='$text' WHERE UserID='$userID' AND GroupID='$groupID'";
                    if ($conn->query($sql) === TRUE) {
                        echo "UserID: ".$userID."  updated successfully";
                        handleEvent($reply_token);
                    } else {
                        echo "UserID: ".$userID."  updated Error" . $conn->error;
                    }
                    
                    $x =0;
                    break;
                }
                else $x =1;
            }
            if($x==1){
                if($userID != $UDI){
                    $sql = "INSERT INTO log (UserID, Text, Timestamp, GroupID) VALUES ('$userID','$text', '$timestamp','$groupID')" ;
                    if ($conn->query($sql) === TRUE) {
                        echo "New UserID: ".$userID."New record created successfully";
                        handleEvent($reply_token);
                    } else {
                        echo "New UserID: ".$userID."New record created Error". $conn->error;
                    }
                    
                }
            }
            
        }
        
    }
}

echo "OK";


function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

?>
