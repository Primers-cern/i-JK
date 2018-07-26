<?php
namespace App\Http\Controllers\Merchant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Config;

class MerchantUserController extends Controller{
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
   

    public function updateMerchant(Request $request){
        $license_path=UploadFile($request,"license","license");
        $merchant_logo_path=UploadFile($request,"merchant_logo","merchant_logo");
        $input=$_POST; 
    	unset($input['tbname'],$input['secret']);
    	foreach ($input as $key => $value) {
    		if($value==''){
    			exit(json_encode(['errmsg'=>$key."不能为空"],JSON_UNESCAPED_UNICODE));
    		}
    	}
    	unset($input['where']);
    	$input['license']=$license_path;
    	$input['merchant_logo']=$merchant_logo_path;
    	$input['create_time']=time();
    	$bool=DB::table("merchant_ auditing")->insert($input);
    	$bool?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"数据出错。"],JSON_UNESCAPED_UNICODE));

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
        $merchant_name_arr=DB::table("merchant_base")->where("merchant_name",$merchant_base_data['merchant_name'])->first();
        if(!empty($merchant_name_arr)){
            exit(json_encode(['errmsg'=>"该店铺已存在。"],JSON_UNESCAPED_UNICODE));
        }
        $mer_user_data['phone']=$input['phone'];
        $mer_user_data['wx_uid']=session("wx_uid");
        $mer_user_data['password']=md5($input['password']);
        $id=DB::table("mer_user")->insertGetId($mer_user_data);
        $merchant_base_data['shopowner_meruser_id']=$id;
    	$bool=DB::table("merchant_base")->insert($merchant_base_data);
    	$bool?exit(json_encode(['errmsg'=>"success"],JSON_UNESCAPED_UNICODE)):exit(json_encode(['errmsg'=>"fail"],JSON_UNESCAPED_UNICODE));

    }
   


}
