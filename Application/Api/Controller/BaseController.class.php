<?php
namespace Api\Controller;
use Think\Controller;
class BaseController extends Controller
{
    protected $user;
    protected $user_id;
    /*
     * 初始化操作
     */
    public function _initialize()
    {    
        $this->request_data = $_REQUEST;
        $this->user_id      = D('User')->where(array('openid' => $this->request_data['openid']))->getField('id');
    }

    /**
     * 返回带参数，标志信息，提示信息的json 数组
     * @param string $msg
     * @param unknown $data  
     */
    protected function returnSuccess($msg = null,$data = array()){
        $result = array(
            'status' => '1',
            'msg' => $msg,
            'data' =>$data
        );
        exit(json_encode($result));
    }
    
    /**
	 * 返回标志信息 ‘Error'，和提示信息的json 数组
	 * @param string $msg
	 */
    protected function returnError($msg = null){
      $result = array(
        'status' => '2',
        'msg' => $msg,
      );
      exit(json_encode($result));
    }
    /**
	 * 获取用户信息
	 * @param unknown $sessionKey
	 * @param unknown $encryptedData
	 * @param unknown $iv
	 */
    public function getUserInfo(){
        $sessionKey = I('request.session_key');
        $encryptedData = I('request.encryptedData');
        $iv = I('request.iv');
        vendor('WxMini.wxBizDataCrypt');
        $data_dc = new \WXBizDataCrypt(C('APPID'), $sessionKey);
        $data = array();
        $errCode = $data_dc->decryptData($encryptedData, $iv, $data);
        if($errCode == 0){
            //保存用户信息
            if ($data) {
                $user = array();
                $user['openid'] = $data->openId;
                $user['nickname'] = $data->nickName;
                $user['gender'] = $data->gender;
                $user['city'] = $data->city;
                $user['province'] = $data->province;
                $user['country'] = $data->country;
                $user['headimgurl'] = $data->avatarUrl;
                if(!$info = D('User')->where(array('openid'=>$data->openId))->find($user)){
                    D('User')->add($user);
                }else {
                    D('User')->where(array('id'=>$info['id']))->save($user);
                   }
            }
               $this->returnSuccess('',$data);
        }else{
               $this->returnError($errCode);
        }
    } 
    /**
	 * 小程序登录获取session_key
	 * @param unknown $code
	 */
    public function getSessionKey(){
        $code = I('request.code');
        $code || $this->returnError('非法的操作!');
        $jsondata = curl_request(C('APP_AUTH_URL').'appid='.C('APPID').'&secret='.C('APPSECRET').'&js_code='.$code);
        $data = json_decode($jsondata);
        $this->returnSuccess('',$data);
    } 
}