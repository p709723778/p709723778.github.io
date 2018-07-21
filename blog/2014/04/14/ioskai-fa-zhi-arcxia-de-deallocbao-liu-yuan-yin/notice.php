define('MESSAGE_TEXT_TYPE', 0);
define('REQUEST_TALK_MESSAGE_TYPE', 1);

// api访问地址
$API_URL = 'https://botapi.chaoxin.com/';

// 从机器人管理页面获得的token
$API_TOKEN = '1566012:2b939bf27a47d75d5911ee13556c74f1';

// 获取客户端的请求
$msg = json_decode(file_get_contents('php://input'), true);

// 当请求类型为1时表示机器人接受聊天消息
if ($msg['request_type'] == REQUEST_TALK_MESSAGE_TYPE)
{
// 当聊天消息为文本消息时
if ($msg['message_type'] == MESSAGE_TEXT_TYPE)
{
$text = $msg['text'];

// 构建发送api请求的参数
$parameters = array(
'chat_id' => $msg['chat_id'],
'chat_type' => $msg['chat_type'],
'text' => $text
);

// 请求发送文字消息协议
apiRequest('sendTextMessage', $parameters);
}
}

function apiRequest($method, $parameters)
{
global $API_URL, $API_TOKEN;
$url = $API_URL.$method.'/'.$API_TOKEN;

$ci = curl_init();
curl_setopt($ci, CURLOPT_URL, $url);

curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ci, CURLOPT_POST, true);
curl_setopt($ci, CURLOPT_POSTFIELDS, $parameters);

curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ci);
$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

curl_close($ci);

return array($http_code, $response);
}