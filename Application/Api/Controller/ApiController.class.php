<?php
namespace Api\Controller;

class ApiController extends \Think\Controller{
    
    public $post_data; //微信服务器发送过来的数据
    public $rule;
    public $replyobj;
    public $resultStr;
    
    public function index(){
        if(isset($_GET['echostr']) && isset($_GET["signature"]) && isset($_GET["timestamp"]) && isset($_GET["nonce"])){
            echo $this->valid(C('TOKEN')); //公众平台校验
        }else{
            echo $this->responseMsg();
        }
    }
    
    //解析微信post过来的数据
    private function getPostData(){
        $postObj = simplexml_load_string($GLOBALS["HTTP_RAW_POST_DATA"], 'SimpleXMLElement', LIBXML_NOCDATA);
        // file_put_contents('aaa.txt',$GLOBALS["HTTP_RAW_POST_DATA"]);
        // if(trim($postObj->MsgType) != 'event')file_put_contents('aaa.txt',$GLOBALS["HTTP_RAW_POST_DATA"]);

        $data = array();
        //通用消息
        $data['FromUserName'] = trim($postObj->FromUserName);
        $data['ToUserName'] = trim($postObj->ToUserName);
        $data['FromMsgType'] = trim($postObj->MsgType);
        $data['Keyword'] = trim($postObj->Content);
        $data['CreateTime'] = trim($postObj->CreateTime);
        //图片消息
        $data['PicUrl'] = trim($postObj->PicUrl);
        //语音消息
        $data['Recognition']  = trim($postObj->Recognition);
        //地理位置消息
        $data['Location_X'] = trim($postObj->Location_X);
        $data['Location_Y'] = trim($postObj->Location_Y);
        $data['Label'] = trim($postObj->Label);
        //位置推送事件
        $data['Latitude'] = trim($postObj->Latitude);
        $data['Longitude'] = trim($postObj->Longitude);
        //事件推送
        $data['Event'] = trim($postObj->Event);
        $data['EventKey'] = trim($postObj->EventKey);
        
        if($data['FromMsgType'] == 'image'){
            $data['Keyword'] = '[发送图片]';
        }elseif($data['FromMsgType'] == 'voice'){
            $data['Keyword'] = $data['Recognition'];
        }elseif($data['FromMsgType'] == 'location'){
            $data['Keyword'] = '[发送位置]'.$data['Label'];
        }elseif($data['Event'] == 'CLICK' || $data['Event'] == 'VIEW'){//菜单点击事件
            $data['Keyword'] = $data['EventKey'];
        }
        $data['timesign']=time().rand(1000,9999);
        return $data;
    }
    
    //回复微信
    private function responseMsg(){
        $this->post_data = $this->getPostData();//解析微信post过来的数据
		
        $Member = D('Users');
        //添加或更新会员
        $memberRow = $Member->where(array("openid"=>$this->post_data['FromUserName']))->find();
        $rowMem = array();
        //$rowMem['customer_id']    = $this->customer_id;
        $rowMem['openid']    = $this->post_data['FromUserName'];
       // $rowMem['timesign']    = $this->post_data['timesign'];
        //更新经纬度
        if(strtolower($this->post_data['Event']) == 'location'){
            //$rowMem['lng']    = $this->post_data['Longitude'];
            //$rowMem['lat']    = $this->post_data['Latitude'];
            $url      = "http://api.map.baidu.com/geoconv/v1/?coords={$this->post_data['Longitude']},{$this->post_data['Latitude']}&from=1&to=5&ak=4n0FW06EfFCdXKWchHH0ZFXqi1WVc0VV";
        $res      = curl_request($url);
        $res      = json_decode($res, true);
        $rowMem['lat'] = $res['result'][0]['y'];
        $rowMem['lng'] = $res['result'][0]['x'];

        }
        //file_put_contents('aasssa.txt',json_encode($rowMem));
        if(strtolower($this->post_data['Event']) == 'unsubscribe'){
            $rowMem['unsubscribe_time'] = time();
            $rowMem['status'] = '0';
        }else{
            //非取消关注时获取用户头像昵称等信息
            if($this->post_data['FromUserName'] && strtolower($this->post_data['Event']) != 'location'){
                vendor ( 'Wechat.Wechat' );
                $wechat = new \Wechat(array('appid' => C('APPID'),'appsecret'=> C('APPSECRET')));
                $access_token = $wechat->checkAuth();
                $userInfo = D('Api')->getUserInfo($access_token,$this->post_data['FromUserName']);//获取用户基本信息
                if(!empty($userInfo['nickname'])){
                    // $rowMem['nickname'] = $userInfo['nickname'];
                    // $rowMem['headimgurl'] = $userInfo['headimgurl'];
                    $rowMem['sex'] = $userInfo['sex'];
                }
            }
        }
        file_put_contents('t.txt',$this->post_data['Event']);
        $memberRow && $Member->where(array('openid'=>$rowMem['openid']))->save($rowMem);
        //暂不处理自动上传地理位置的信息
        if(strtolower($this->post_data['Event']) != 'location') return $this->getInfo();
       
        if(strtolower($this->post_data['Event'])=='subscribe'){
        	return $this->create_xmlcontent('text', nl2br('终于等到你，还好没放弃
        										安心找工作，就上安聘网这里有免费的班车接送
												这里有热心的客服姐姐帮助您
												这里是国内最方便、最快捷、最靠谱的大型免费求职平台
												总之来到这里，是您本年度最正确的选择！
												注册后，将为您自动分配专属客服
												也可以拨打24小时客服热线：400-610-9966
												还等啥？快快加入我们吧！'));
        }
    }
    
    //获取回复内容
    public function getInfo(){
        
        if($this->post_data['FromMsgType'] == 'voice'){
           $this->post_data['Keyword'] = rtrim($this->post_data['Keyword'],'。！，？');
        }

        $keywordinfo = D('Keyword')->where(array('keyword'=>$this->post_data['Keyword']))->find();
        if(empty($keywordinfo)){
            return $this->getAutoReply();
        }else{
            $common_info = D('Common')->where(array('id'=>$keywordinfo['common_id'],'state'=>'1'))->find();
            if(empty($common_info)){
                return $this->getAutoReply();
            }else{
                $data = array();
                switch($keywordinfo['type']){
                    case 'text':$data = $this->getText($keywordinfo['common_id']);break;
                    case 'image':$data = $this->getImage($keywordinfo['common_id']);break;
                    case 'single':$data = $this->getSingle($keywordinfo['common_id']);break;
                    case 'muti':$data = $this->getMuti($keywordinfo['common_id']);break;
                }
                if(empty($data)) return $this->getAutoReply();
                return $this->create_xmlcontent($data['msgtype'], $data['content'], $data['title'], $data['description'], $data['picurl'], $data['url'], $data['bodystr']);
            }
        }
    }

    //无匹配回复
    public function getAutoReply(){
        if($replyRow = D("AutoReply")->where(array('type'=>'2','state'=>'1'))->find()){
            return $this->create_xmlcontent('text', $replyRow['content']);
        }else{
            return false;
        }
    }
    
    
    //文字回复
    public function getText($id='',$content = ''){
        if(empty($content)){
            $WxCommon = D('Common');
            $inforesult = $WxCommon->where(array('id'=>$id,'state'=>'1'))->find();
            $WxCommon->where(array('id'=>$id))->setInc('push_num');//推送量+1
            return array('msgtype'=>'text','content'=>$inforesult['content']);
        }else{
            return array('msgtype'=>'text','content'=>$content);
        }
    }
    //图片回复
    public function getImage($id){
        $WxCommon = D('Common');
        $inforesult = $WxCommon->where(array('id'=>$id,'state'=>'1'))->find();
        // file_put_contents('aaa.txt',$inforesult,FILE_APPEND);
        $WxCommon->where(array('id'=>$id))->setInc('push_num');//推送量+1
        if(empty($inforesult['pic'])){
            return false;
        }else{
            //上传图片
            vendor ( 'Wechat.Wechat' );
            $wechat = new \Wechat(array('appid' => C('APPID'),'appsecret'=> C('APPSECRET')));
            $token = $wechat->checkAuth();
            
            $result = D('Api')->upTempImage($token,$_SERVER['DOCUMENT_ROOT'].$inforesult['pic']);
            $result = json_decode($result,true);
            if(!empty($result['media_id'])){
                $data['msgtype'] = 'image';
                $data['bodystr'] = "
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[{$result['media_id']}]]></MediaId>
                    </Image>";
                return $data;
            }else{
                return false;
            }
        }
    }
    //单图文回复
    public function getSingle($id){
        $WxCommon = D('Common');
        $inforesult = $WxCommon->where(array('id'=>$id,'state'=>'1'))->find();
        $WxCommon->where(array('id'=>$id))->setInc('push_num');//推送量+1
        
        $data['msgtype']     = 'news';
        $data['title']       = $inforesult['title'];
        $data['description'] = $inforesult['des'] ? $inforesult['des'] : cut_str(delete_html($data['content']), 80);
        $data['picurl']      = C('PRO_URL').trim($inforesult['pic'],'.');
        $data['url']         = $inforesult['url'];
        if(empty($data['url']))
            $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].U('Home/WxArticle/single',array('id'=>$id));
        
        return $data;
    }
    //多图文回复
    public function getMuti($id,$datas = array()){
        if(empty($datas)){
            $WxCommon = D('Common');
            $result = $WxCommon->where(array('id'=>$id,'state'=>'1'))->select();
            $WxCommon->where(array('id'=>$id))->setInc('push_num');//推送量+1
            $commonDetail = D('CommonDetail');
            $datas = $commonDetail->where(array('common_id'=>$id))->order("order_num asc")->select();
        }
        $i = 0;
        $itemlist = '';
        $infocount = count($datas);
        
        for($i=0; $i < $infocount; $i++){
            if(!$datas[$i]['url']){
                $url = 'http://'.$_SERVER['HTTP_HOST'] .U('Home/WxArticle/muti',array('id'=>$datas[$i]['id']));
            }else{
                $url = $datas[$i]['url'];
            }
            $datas[$i]['description'] = cut_str(trim(strip_tags(html_entity_decode($datas[$i]['content']))),60);
            $itemlist .= "<item>
                 <Title><![CDATA[{$datas[$i]['title']}]]></Title>
                 <Description><![CDATA[{$datas[$i]['description']}]]></Description>
                 <PicUrl><![CDATA[http://{$_SERVER['HTTP_HOST']}{$datas[$i]['pic']}]]></PicUrl>
                 <Url><![CDATA[$url]]></Url>
            </item>";
        }
        if ($i > 0) {
            $Bodystr = "
            <ArticleCount>$infocount</ArticleCount>
            <Articles>
            $itemlist
            </Articles>";
            $data['msgtype']    = 'news';
            $data['bodystr']    = $Bodystr;
            return $data;
        }
        return false;
    }
    
    /**
     * 组合xml内容 
     * @param string $msgtype text文字,news图文,music音乐
     * @param string $content 文字消息体
     * @param string $title 图文标题
     * @param string $description 图文简介
     * @param string $picurl 图文封面
     * @param string $url 图文链接
     * @param string $bodystr 已组合好的格式体，通常用在多图文中
     */
    public function create_xmlcontent($msgtype, $content='', $title='', $description='', $picurl='', $url='', $bodystr=''){
        if($msgtype == 'text'){
            $content = str_replace("\r","",$content);
            $bodystr = '<Content><![CDATA['.$content.']]></Content>';
        }elseif($msgtype == 'news'){
            if ($bodystr == ''){
                $description = cut_str($description, 120);
                $bodystr = "<ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[$title]]></Title>
                    <Description><![CDATA[$description]]></Description>
                    <PicUrl><![CDATA[$picurl]]></PicUrl>
                    <Url><![CDATA[$url]]></Url>
                    </item>
                    </Articles>";
            }
        }elseif($msgtype == 'music'){
            $bodystr = "<Music>
                <Title><![CDATA[$title]]></Title>
                <Description><![CDATA[$description]]></Description>
                <MusicUrl><![CDATA[$url]]></MusicUrl>
                <HQMusicUrl><![CDATA[$url]]></HQMusicUrl>
                </Music>";
        }
        
        $xmlcontent = "<xml>
            <ToUserName><![CDATA[".$this->post_data['FromUserName']."]]></ToUserName>
            <FromUserName><![CDATA[" . $this->post_data['ToUserName']."]]></FromUserName>
            <CreateTime>".time()."</CreateTime>
            <MsgType><![CDATA[".$msgtype."]]></MsgType>
            ".$bodystr."
            <FuncFlag>0</FuncFlag>
            </xml>";
        return $xmlcontent;
    }
    
    //公众平台校验
    private function valid($token){
        $echoStr   = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        // file_put_contents('aaa.txt',json_encode($_REQUEST));
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature) return $echoStr;
    }
}
