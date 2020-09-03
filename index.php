<?php



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
        //$text_S = $event['message']['text'];
        $text = $event['message']['text'];
        //$text=$text_S;
        
        $data = [
            'replyToken' => $reply_token ,
             //'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  
            'messages' => [['type' => 'text', 'text' => $text ]]
        ];
        $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

        echo "Result: ".$send_result."\r\n";
      
        
        
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
