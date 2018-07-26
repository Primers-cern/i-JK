<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Config;

function UploadFile($request,$name,$disk){
    $file=$request->file($name);
    if($file->isValid()){
    	$originalName=$file->getClientOriginalName();
    	$ext=$file->getClientOriginalExtension();
    	$type=$file->getClientMimeType();
    	$realPath=$file->getRealPath();
    	$filename=date("Y-m-d-H-i-s")."-".uniqid().".".$ext;
    	$bool=Storage::disk($disk)->put($filename,file_get_contents($realPath));
    	if(!$bool) exit(json_encode(['errmsg'=>"文件上传失败。"],JSON_UNESCAPED_UNICODE));
    	else return "/uploads/".$disk."/".$filename;
    }else{
    	exit(json_encode(['errmsg'=>"请上传文件。"],JSON_UNESCAPED_UNICODE));
    }
} 

    function send_yzm($phone,$code){
      //商家注册申请验证码
       $uid=Config::get("constants.MESSAGE_UID");
       $pwd=md5(Config::get("constants.MESSAGE_PWD").Config::get("constants.MESSAGE_UID"));
       $url='http://api.sms.cn/sms/';
       $data=array('uid'=>$uid,'pwd'=>$pwd,'format'=>'json','ac'=>'send','mobile'=>$phone,'content'=>'{"regcode":'.$code.'}','template'=>438128);
       $postFields = http_build_query($data);
      // $result = json_decode(iconv('GBK','UTF-8',postCurl($postFields,$url)),true);
       $result = json_decode(postCurl($postFields,$url),true);
       if($result['stat']==100){
        return true;
       }else{
        return false;
       }
    }

     function postCurl($data,$url){
       
        $ch = curl_init (); 
        // print_r($ch); 
        curl_setopt( $ch, CURLOPT_URL, $url ); 
        curl_setopt ( $ch, CURLOPT_POST, 1 ); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //跳过SSL证书检查
        curl_setopt ( $ch, CURLOPT_HEADER, 0 ); 
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); 
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data ); 
        $return = curl_exec ( $ch ); 
        curl_close ( $ch ); 
        return $return;
    }