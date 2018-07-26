<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Config;

class DbComActionController extends Controller{
	public function __construct(Request $request) {
		if(!$request->isMethod("POST")){
            $return['errmsg']="请用POST方式发送！";
             
		}else if(!in_array($request->input("secret"), Config::get("constants.DB_SECRET"))){
			$return['errmsg']="secret错误。";
            
		}else if(!$request->input("tbname")){
			$return['errmsg']="表名错误";
            
		}
		if(isset($return['errmsg'])) exit(json_encode($return,JSON_UNESCAPED_UNICODE));
    }
    public function add(Request $request){
    	//$input=$request->all(); 
    	$input=$_POST; 
    	$tbname=$input['tbname'];
    	unset($input['tbname'],$input['secret']);
    	foreach ($input as $key => $value) {
    		if($value==''){
    			exit(json_encode(['errmsg'=>$key."不能为空"],JSON_UNESCAPED_UNICODE));
    		}
    	}
    	$BOOL=DB::table($tbname)->insert($input);
    	$BOOL?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"fail"],JSON_UNESCAPED_UNICODE));
    }

     public function update(Request $request){
    	$input=$_POST; 
    	$tbname=$input['tbname'];
    	unset($input['tbname'],$input['secret']);
    	foreach ($input as $key => $value) {
    		if($value==''){
    			exit(json_encode(['errmsg'=>$key."不能为空"],JSON_UNESCAPED_UNICODE));
    		}
    	}
    	$where=explode("=",$input['where']);
    	unset($input['where']);
    	$num=DB::table($tbname)->where($where[0],$where[1])->update($input);
    	$num?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"0"],JSON_UNESCAPED_UNICODE));
    }

    public function updateMerchant(Request $request){
        $license_path=UploadFile($request,"license","license");
        $merchant_logo_path=UploadFile($request,"merchant_logo","merchant_logo");
        $input=$_POST; 
    	$tbname=$input['tbname'];
    	unset($input['tbname'],$input['secret']);
    	foreach ($input as $key => $value) {
    		if($value==''){
    			exit(json_encode(['errmsg'=>$key."不能为空"],JSON_UNESCAPED_UNICODE));
    		}
    	}
    	$where=explode("=",$input['where']);
    	unset($input['where']);
    	$input['license']=$license_path;
    	$input['merchant_logo']=$merchant_logo_path;
    	$input['update_time']=time();
    	$num=DB::table($tbname)->where($where[0],$where[1])->update($input);
    	$num?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"0"],JSON_UNESCAPED_UNICODE));

    }

    public function addMerchant(Request $request){
        $input=$request->all(); 
    	unset($input['tbname'],$input['secret']);
    	foreach ($input as $key => $value) {
    		if($value==''){
    			exit(json_encode(['errmsg'=>$key."不能为空"],JSON_UNESCAPED_UNICODE));
    		}
    	}
    	$merchant_base_data['merchant_name']=$input['merchant_name'];
    	$merchant_base_data['create_time']=time();
    	$id=DB::table("merchant_base")->insertGetId($merchant_base_data);
    	if(!$id){
    		exit(json_encode(['errmsg'=>"该店铺已存在。"],JSON_UNESCAPED_UNICODE));
    	}
    	$mer_user_data['phone']=$input['phone'];
    	$mer_user_data['merchant_id']=$id;
    	$mer_user_data['wx_uid']=session("wx_uid");
    	$mer_user_data['password']=md5($input['password']);
    	$BOOL=DB::table("mer_user")->insert($mer_user_data);
    	$BOOL?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"fail"],JSON_UNESCAPED_UNICODE));

    }
}
