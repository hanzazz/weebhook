<?php

$servername = "sql12.freemysqlhosting.net";
$username = "sql12363661";
$password = "2bkjcJHPBS";
$dbname = "sql12363661";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'XVkmOR4aT771B9CnIdxvdGmlOtXQSijnvLZ+T7GC5Hd8cVC8nKslvKPBTUs2M6vI5WhhF92i6S1NvR/ZY7IARrfIWCCZwo+ZYk6bzTnL9+ilJOWBlQyPXUvlZvgR5eE3a2KZ+C+hhDLn7bbiDVUJQgdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '5f02b5ca4a7aef50a0cb14673e155bd5';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array


$rowsql =0;
if ( sizeof($request_array['events']) > 0 ) {

    foreach ($request_array['events'] as $event) {

        $reply_message = '';
        $reply_token = $event['replyToken'];
        
        $userID = $event['source']['userId'];
        $groupID = $event['source']['groupId'];
        $timestamp = $event['timestamp'];
        $text_S = $event['message']['text'];
        $text=$text_S;

      
        if($text_S == 'EU-1' || $text_S == 'AU-1' || $text_S == 'GU-1' || $text_S == 'NU-1' || $text_S == 'UCAD-1' || $text_S == 'UCHF-1' || $text_S == 'UJPY-1' ||
           $text_S == 'eu-1' || $text_S == 'au-1' || $text_S == 'gu-1' || $text_S == 'nu-1' || $text_S == 'ucad-1' || $text_S == 'uchf-1' || $text_S == 'ujpy-1' ||
           $text_S == 'EU-2' || $text_S == 'AU-2' || $text_S == 'GU-2' || $text_S == 'NU-2' || $text_S == 'UCAD-2' || $text_S == 'UCHF-2' || $text_S == 'UJPY-2' ||
           $text_S == 'eu-2' || $text_S == 'au-2' || $text_S == 'gu-2' || $text_S == 'nu-2' || $text_S == 'ucad-2' || $text_S == 'uchf-2' || $text_S == 'ujpy-2' ){
        
            $x = 0;
            
            //if (err) throw err;
            //Select all customers and return the result object:
            
            $querysql = "SELECT * FROM log";
            
            //*************************************************************************** */
            // Perform query
            
            if ($result = $conn -> query($querysql)) {
              //echo "Returned rows are: " . $result -> num_rows;
              // Free result set
              $rowsql = $result -> num_rows;
              $result -> free_result();
            }
            
            /**************************************************************************** */
            for ($i = 0; $i < $rowsql; $i++) {
                
                //*****************************************************************************
                //console.log(`A JavaScript type is: ${result[_ID]["UserID"]}`)
                $UDI = $result[$i]["UserID"];
                $GROUPID = $result[$i]["GroupID"];
              
                $data = [
                    'replyToken' => $reply_token,
                    // 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]
                    'messages' => [['type' => 'text', 'text' => $text_S ]]
                ];
                $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

                $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

                echo "Result: ".$send_result."\r\n";
              
                if($userID == $UDI){
                    //*************************************************************************** */
                    $sql = "UPDATE log SET  Text='$text' WHERE UserID='$userID'";
                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully";
                        $data = [
                            'replyToken' => $reply_token,
                            // 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
                            'messages' => [['type' => 'text', 'text' => "wait pls!!" ]]
                        ];
                        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

                        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

                        echo "Result: ".$send_result."\r\n";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                        $data = [
                            'replyToken' => $reply_token,
                            // 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
                            'messages' => [['type' => 'text', 'text' => "Error: " . $sql . "<br>" . $conn->error ]]
                        ];
                        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

                        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

                        echo "Result: ".$send_result."\r\n";
                    }
                    /**************************************************************************** */
                    $x =0;
                    break;
                }
                else $x =1;
            }
            if($x==1){
                if($userID != $UDI){
                    $mysql = "INSERT INTO log (UserID, Text, Timestamp, GroupID) VALUES ('$userID','$text', '$timestamp','$groupID')" ;
                    if ($conn->query($sql) === TRUE) {
                        echo "New UserID: ".$userID."New record created successfully";
                        $data = [
                            'replyToken' => $reply_token,
                            // 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
                            'messages' => [['type' => 'text', 'text' => "wait pls!!" ]]
                        ];
                        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

                        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

                        echo "Result: ".$send_result."\r\n";
                    } else {
                        echo "New UserID: ".$userID."New record created Error". $conn->error;
                    }
                    
                }
            }
            
        }
        else{
          $data = [
              'replyToken' => $reply_token,
              // 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
              'messages' => [['type' => 'text', 'text' => "Keyword no Correct!! Check Pls" ]]
          ];
          $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

          $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

          echo "Result: ".$send_result."\r\n";
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
