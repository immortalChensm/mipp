<?php
namespace Common\Model;

use Think\Model;
class WorkcardModel extends Model
{
    protected $tablePrefix = "zxx_";
   
    //获取工牌信息
    public function getworkcard($user)
    {
        $userid = $user['id'];//当前登录的会员id
        
        $sql = "SELECT 
        a.companyid as workcardid,
        a.cardsn,
        a.card_fgpic,
        b.name 
        FROM zxx_workcard a
        left join 
        rt_company b 
        on a.companyid=b.id WHERE a.userid='{$userid}' limit 1";
        $profile = $this->query($sql);
        $profile[0]['card_fgpic_new'] = "https://recruit.mppstore.com".substr($profile[0]['card_fgpic'], 1);
        file_put_contents("workcard.log", $sql);
        return($profile);
    }
    
    //获取企业信息
    public function getcompanylist()
    {
            $name = I("post.name");

            $condition['name'] = ['like','%'.$name.'%'];
    
            $list = M("Company")->where($condition)->field("id,name")->select();
    
            //汉字按字母排序
            $overcome_data = chinese_character_sort($list);
            $new_data = [];
            foreach ($overcome_data as $k=>$v){
    
                $rows['id'] = $k;
                $rows['name'] = $v;
                $new_data[] = $rows;
            }
    
            return($new_data);
 
    }
    
    //添加工牌
    public function addworkcard($user)
    {
            //所选择的企业id
            $data['companyid'] = I("post.companyid");
            //填写的工牌号
            $data['cardsn'] = I("post.cardsn");
            $data['status'] = 1;//添加工牌时状态为１　表示待审核
            //当前的登录会员id
            $data['userid'] = $user['id'];
            $data['card_fgpic'] = I("post.card_fgpic");
            $upload = new \Think\Upload();
            $upload->exts = ['jpg','png','gif'];
            $upload->rootPath = C("WORKCARD");
            $info = $upload->upload();
            if($info){
                //上传的工牌图片
                $data['card_fgpic'] = __ROOT__.C("WORKCARD").$info['file']['savepath'].$info['file']['savename'];
    
                //验证
                $rules = [
                    ['companyid','require','请选择您所在的厂企名称'],
                    ['cardsn','require','请填写您的工牌号'],
                    ['card_fgpic','require','请上传您的工牌图片'],
                ];
    
                if($this->validate($rules)->create($data)){
                    //检查是否绑定了工牌
                    $map['userid'] = $data['userid'];
    
                    if($this->where($map)->find()){
                        //更新工牌信息
                        return $this->where($map)->save($data)?1:0;
                    }else{
    
                        //没有绑定工牌时
                        return $this->add()?1:0;
                    }
                }else{
                    return $this->getError();
                }
            }else{
                return $upload->getError();
            }

    }
    
    /**
     * 添加工牌　　
     * **/
    public function addworkcardnew($user)
    {
        //所选择的企业id
        $data['companyid'] = I("post.companyid");
        //填写的工牌号
        $data['cardsn'] = I("post.cardsn");
        $data['status'] = 1;//添加工牌时状态为１　表示待审核
        //当前的登录会员id
        $data['userid'] = $user['id'];
        $data['card_fgpic'] = I("post.card_fgpic");
        /*
        $picbinary = trim(I("post.card_fgpic"));
        file_put_contents("addworkcard.log", $picbinary);
        $ext = I("post.picext");

        if(is_dir(C("WORKCARD"))&&is_writable(C("WORKCARD"))){
            $filename = C("WORKCARD").date("ymdhis",time()).mt_rand(1, 9999).$ext;
            $file = file_put_contents($filename, base64_decode($picbinary));
            
        }else{
            return 'direrror';
        }
        
        if(file_exists($filename)){
            //上传的工牌图片
            $data['card_fgpic'] = $filename;
        }else{
            return "notuploaad";
        }
        //$data['card_fgpic'] = $filename;
         * */
        
   
        //验证
        $rules = [
            ['companyid','require','-1'],
            ['cardsn','require','-2'],
            ['card_fgpic','require','-3'],
        ];
    
        if($this->validate($rules)->create()){
            
            //绑定工牌先验证当前会员的入职名单表得到其所在企业防止乱绑定企业
            $condition['user_id'] = $user['id'];
            $belong_companyid = M()->table("rt_entry")->field("company_id,job_number")->where()->find();
            
            //所绑定的企业必须和入职名单表的企业一致
            if($data['companyid']!=$belong_companyid['company_id']){
                return 3;
            }
            //验证填写的工牌是否合法
            if($data['cardsn']!=$belong_companyid['job_number']){
                return 4;
            }
            
            //检查是否绑定了工牌
            $map['userid'] = $data['userid'];
    
            if($this->where($map)->find()){
                //更新工牌信息
                return $this->where($map)->save($data)?2:0;
            }else{
    
                //file_put_contents("addworkcard.lot", serialize($data));
                //没有绑定工牌时
                return $this->add($data)?1:0;
            }
        }else{
            return $this->getError();
        }

        
    }
    
    //工牌状态
    public function workstatus($user)
    {
        $condition['userid'] = $user['id'];
        return $this->field("status")->where($condition)->find()['status'];
    }
}
?>