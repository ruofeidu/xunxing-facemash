<?php
function join_cookie($cook)
{
    foreach( $cook as $k=>$v )
    {
		$d[] =$k."=".$v;
    }
	$data = implode(";",$d);
	return $data;
}

function join_post($cook)
{
 $fields_string='';
 foreach($cook as $key=>$value) { $fields_string .= urlencode($key).'='.$value.'&' ; }
 rtrim($fields_string ,'&') ;

 return $fields_string;
}
$output='';
function catcha($url,&$post_data,$cook,$putout=1)
{


		echo "\n<p/>";
		
		$ch = curl_init();
		// ���� url
		curl_setopt($ch, CURLOPT_URL, $url);
		

		
		//cookie data
		curl_setopt($ch, CURLOPT_COOKIE, join_cookie($cook));
        
        // ҳ���������ǲ�����Ҫ
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        // ����HTTP header
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // ���ؽ���������������
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		//post data
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		
		//�ض���
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		//ִ�У�
        $output = curl_exec($ch);
		curl_close($ch);
        // ���ض����HTTPͷ��Ϣ��?
        if (preg_match("!Location: (.*)!", $output, $matches)) {
            //echo "redirects to $matches[1]\n<br>";

        } else {
            //echo "no redirection\n<br>";
        }
		
		if (preg_match('|id="__VIEWSTATE" value="(.*?)" />|', $output, $matches)) {
            $post_data['__VIEWSTATE']=$matches[1];

        } else {
            $post_data['__VIEWSTATE']=null;
        }
		
		if (preg_match('|id="__EVENTVALIDATION" value="(.*?)" />|', $output, $matches)) {
            $post_data['__EVENTVALIDATION']=$matches[1];

        } else {
           $post_data['__EVENTVALIDATION']=null;
        }
		
		$result = mb_convert_encoding($output, "GBK", "UTF-8");
		
		//echo join_cookie($cook);
		if ($putout) echo "$result\n<br>";
		return $result;
}

function get_client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
}