<?php
namespace App\Http\Controllers\Merchant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogincheckController extends Controller{

	public function __construct() {
		session(['wx_login'=>1]);
        if(!session('wx_login')){
        	//未登录微信
        	echo "微信登录失败，前往微信登录页。";
        	exit;
        }else if(!session('meruser_id')){
        	//未登录商家、店员帐号
        	return view("Merchant.Logincheck.merchantLogin");
        	exit;
        }
    }

     public function displayReg(){
        return view("Merchant.Logincheck.merchantLogin");
    }

      public function regCode(Request $request){
        $phone=$request->input("phone");
        $code=mt_rand(100000,999999);
        $mer_user=DB::table("mer_user")->where("phone",$phone)->first();
        if(!empty($mer_user)){
            exit(json_encode(['errmsg'=>"该手机已注册。"],JSON_UNESCAPED_UNICODE));
        }
        if(send_yzm($phone,$code)){
             echo json_encode(['reg_code'=>$code]);
        }else{
             echo json_encode(['errmsg'=>"验证码发送失败。"],JSON_UNESCAPED_UNICODE);
        }
        
    }

    public function merLogin(Request $request){
        $phone=$request->input("phone");
        $password=$request->input("password");
        $mer_user=DB::table("mer_user")->where("phone",$phone)->first();
        if(md5($password)==$mer_user->password){
            session(['meruser_id'=>$mer_user->meruser_id]);
            $merchant_data=DB::select("select merchant_id,merchant_logo,merchant_name from merchant_base where shopowner_meruser_id={$mer_user->meruser_id} or find_in_set({$mer_user->meruser_id},clerk_meruser_id)");
            if(!empty($merchant_data)){
            	$return['result_code']="success";
            	$return['phone']=$phone;
            	$return['shopList']=$merchant_data;
            	echo json_encode($return);exit;
            }
            
        }else{
            echo json_encode(["result_code"=>"fail"]);
        }
       
        
    }
}




?>