<?php
namespace app\index\controller;

use think\Controller;
use think\Url;
use think\Db;
use think\Session;

use app\index\model\User;
use app\index\model\Sell;
use app\index\model\Photo;
use app\index\model\Collection;

class Index extends Controller
{
    public function index()//首页
    {
        $home_url = Url::build('index/index/index');
        $reg_url = Url::build('index/index/log_reg')."?id=1";//注册路径
        $log_url = Url::build('index/index/log_reg')."?id=2";//登录路径
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $house_details = Url::build('index/index/house_details');
        $this->assign([
            'home_url' => $home_url,
            'reg_url' => $reg_url,
            'log_url' => $log_url,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'house_details' => $house_details
        ]);
        //id为排序方式
        $sell = new Sell();
        $this->assign('id', 1);//初始化id
        $this->assign('against', 0);//初始化倒序
        if (input('?get.id')&&input('?post.search')==false) {
            $this->assign('id', input('get.id'));
            if (input('get.id')==2) {
                $rs = $sell->where('delete_time',0)->order('price')->select();
                if (input('?get.against')) {
                    $rs = $sell->where('delete_time',0)->order('price desc')->select();
                    $this->assign('against', 2);
                }
            } elseif (input('get.id')==3) {
                $rs = $sell->where('delete_time',0)->order('area')->select();
                if (input('?get.against')) {
                    $rs = $sell->where('delete_time',0)->order('area desc')->select();
                    $this->assign('against', 3);
                }
            }
        } elseif (input('?post.search')&&input('?get.id')==false) {
            $search = input('post.search');
            $rs = $sell->where('delete_time',0)->where('address', 'like', '%'.$search.'%')->select();
        } elseif (input('?post.search')&&input('?get.id')) {
            $search = input('post.search');
            $this->assign('id', input('get.id'));
            if (input('get.id')==2) {
                $rs = $sell->where('address', 'like', '%'.$search.'%')->where('delete_time',0)->order('price')->select();
                if (input('?get.against')) {
                    $rs = $sell->where('address', 'like', '%'.$search.'%')->where('delete_time',0)->order('price desc')->select();
                    $this->assign('against', 2);
                }
            } elseif (input('get.id')==3) {
                $rs = $sell->where('address', 'like', '%'.$search.'%')->where('delete_time',0)->order('area')->select();
                if (input('?get.against')) {
                    $rs = $sell->where('address', 'like', '%'.$search.'%')->where('delete_time',0)->order('area desc')->select();
                    $this->assign('against', 3);
                }
            }
        } else {
            $rs = $sell->where('delete_time',0)->order('id desc')->select();
        }
        $this->assign('rs', $rs);
        $photo = new Photo();
        $rs_photo = $photo->order('id desc')->select();
        $this->assign('rs_photo', $rs_photo);
        if (Session::has('user')) {
            $user = new User();
            $rs = $user->where('username', session('user'))->find();
            $nickname = $rs->nickname;
            $this->assign('user', $nickname);
            return $this->fetch('home2');//已登录的界面
        } else {
            return $this->fetch('home');//未登录的界面
        }
    }
    public function log_reg()//注册登录
    {
        $this->assign('reg_error', 0);//初始化 注册是否成功的判断
        $this->assign('log_error', 0);//初始化 登录是否成功的判断
        $this->assign('captcha_error', 0);//初始化 验证码是否正确的判断
        $this->assign('phone_input', 0);//初始化 手机号或用户名输入历史
        $this->assign('username_input', 0);//初始化 用户名输入历史
        $reg_url = Url::build('index/index/log_reg')."?id=1";//注册路径
        $log_url = Url::build('index/index/log_reg')."?id=2";//登录路径
        $home_url = Url::build('index/index/index');
        $this->assign([
            'reg_url' => $reg_url,
            'log_url' => $log_url
        ]);
        $this->assign('id', input('get.id'));//获取id分辨注册和登录
        $this->assign('home_url', $home_url);
        //注册
        if (input('?post.phone')) {
            if ($this->check_verify(input('post.captcha'))==true) {
                $user = new User();
                $usercount = $user->where('phone', input('post.phone'))->count();//查询手机号是否存在
                if ($usercount>0) {
                    $this->assign('reg_error', 1);//手机号已注册 传入1
                    $this->assign('phone_input', input('post.phone'));//传递用户输入过的手机号
                } else {
                    $password = password_hash(input('post.password1'), PASSWORD_DEFAULT);//哈希加密
                    $username = rand(100000, 999999999);
                    $nickname = "用户".$username;
                    //插入数据
                    $user->phone = input('post.phone');
                    $user->username = $username;
                    $user->password = $password;
                    $user->nickname = $nickname;
                    $user->save();

                    Session::set('user', $user->username);//session赋值
                    $this->redirect('index/index/index');
                }
            } elseif ($this->check_verify(input('post.captcha'))==false) {
                $this->assign('phone_input', input('post.phone'));
                $this->assign('captcha_error', 1);//验证码错误 传入1
            }
        }
        //登录
        elseif (input('?post.username')) {
            if ($this->check_verify(input('post.captcha'))==true) {
                $user = new User();
                $rs = $user->where('username', input('post.username'))
                ->whereOr('phone', input('post.username'))
                ->find();
                if ($rs==null) {
                    $this->assign('log_error', 2);
                    $this->assign('username_input', input('post.username'));
                } else {
                    if (password_verify(input('post.password'), $rs->password)) {
                        session('user', $rs->username);//session赋值
                        $this->redirect('index/index/index');
                    } else {
                        $this->assign('log_error', 1);
                        $this->assign('username_input', input('post.username'));
                    }
                }
            } elseif ($this->check_verify(input('post.captcha'))==false) {
                $this->assign('username_input', input('post.username'));
                $this->assign('captcha_error', 1);//验证码错误 传入1
            }
        }
       
        return $this->fetch('newuser');
    }
    public function personal_center()//个人中心
    {
        $home_url = Url::build('index/index/index');
        $user_sell = Url::build('index/index/user_sell');
        $user_message = Url::build('index/index/personal_center');
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $this->assign([
            'home_url' => $home_url,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'user' => session('user'),
            'user_message' => $user_message,
            'user_sell' => $user_sell
        ]);
        $user = new User();
        if (input('?post.nickname')) {
            $user->save(['nickname'=>input('post.nickname')], ['username'=>session('user')]);
        }
        if (input('?post.email')) {
            $user->save(['email'=>input('post.email')], ['username'=>session('user')]);
        }
        
        // 查询单个数据
        //select * from user where username='{$_SESSSION["user"]}';
        $rs=$user->where('username', session('user'))->find();
        $this->assign("username", session('user'));
        $this->assign("nickname", $rs->nickname);
        $this->assign("phone", $rs->phone);
        $this->assign("email", $rs->email);
        
        return $this->fetch('user_info');
    }
    
    public function user_sell()//当房东
    {
        $home_url = Url::build('index/index/index');
        $user_sell = Url::build('index/index/user_sell');
        $user_message = Url::build('index/index/personal_center');
        $sell_info = Url::build('index/index/sell_info');
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $house_details = Url::build('index/index/house_details');
        $this->assign([
            'home_url' => $home_url,
            'user_message' => $user_message,
            'user_sell' => $user_sell,
            'sell_info' => $sell_info,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'user' => session('user'),
            'house_details' => $house_details
        ]);
        if (input('?post.xq_name')) {
            $sell=new Sell;
            $sell->address=input('post.xq_name');
            $sell->building=input('post.building');
            $sell->unit=input('post.unit');
            $sell->area=input('post.area');
            $sell->room=input('post.room');
            $sell->hall=input('post.hall');
            $sell->wash=input('post.wash');
            $sell->price=input('post.price');
            $sell->floor=input('post.myfloor');
            $sell->tall=input('post.allfloor');
            $sell->type=input('post.zhuangxiu');
            $sell->content=input('post.hourse-intro');
            $sell->seller=input('post.seller');
            $sell->phone=input('post.phone');
            $sell->username=session('user');
            $sell->save();
            $sell_id = $sell->id;
        }
        if (input('?get.id')) {
            $id=input('get.id');
            Db::query("update sell set delete_time=1 where id=$id");
        }
        //图片上传
        if (request()->file('photo')!=null) {
            $files = request()->file('photo');
            // var_dump($files);
            date_default_timezone_set('Asia/Shanghai');
            $time = date("Ymd");
            foreach ($files as $file) {
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $photo = new Photo();
                $photo->sell_id = $sell_id;
                $photo->file = $info->getFilename();
                $photo->time = $time;
                $photo->save();
            }
        }
        $sell=new Sell;
        $rs=$sell->where('username', session('user'))->where('delete_time', 0)->order('id desc')->select();
        $this->assign('rs', $rs);
        $photo = new Photo();
        $rs_photo = $photo->order('id desc')->select();
        $this->assign('rs_photo', $rs_photo);
        return $this->fetch('user_sell');
    }

    public function sell_info()//  房东填写房屋信息
    {
        $home_url = Url::build('index/index/index');
        $user_sell = Url::build('index/index/user_sell');
        $user_message = Url::build('index/index/personal_center');
        $sell_info = Url::build('index/index/sell_info');
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $this->assign([
            'home_url' => $home_url,
            'user_message' => $user_message,
            'user_sell' => $user_sell,
            'sell_info' => $sell_info,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'user' => session('user')
        ]);
        return $this->fetch('sell_info');
    }
    public function house_details()//房源具体信息界面
    {
        if (session('?user')==false) {
            $this->error('请先登录');
        }
        $home_url = Url::build('index/index/index');
        $reg_url = Url::build('index/index/log_reg')."?id=1";//注册路径
        $log_url = Url::build('index/index/log_reg')."?id=2";//登录路径
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $user = new User();
        $rs = $user->where('username', session('user'))->find();
        $nickname = $rs->nickname;
        $this->assign([
            'home_url' => $home_url,
            'reg_url' => $reg_url,
            'log_url' => $log_url,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'user' => $nickname
        ]);

        
        
        if (input('?get.id')) {
            $this->assign('id',input('get.id'));
            $sell = new Sell();
            $rs = $sell->where('id', input('get.id'))->find();
            $this->assign('rs', $rs);

            $photo = new Photo();
            $rs_photo = $photo->where('sell_id', $rs->id)->select();
            $this->assign('rs_photo', $rs_photo);
            //收藏与取消收藏操作
            $collection = new Collection();
            if(input('?get.collect')){
                
                if(input('get.collect')==0){
                    $collection->username = session('user');
                    $collection->sell_id = input('get.id');
                    $collection->save();
                }else{
                    $collection->destroy(['username'=>session('user'),'sell_id'=>input('get.id')]);
                }
            }

            
            $count_clc = $collection->where('username', session('user'))->where('sell_id', input('get.id'))->count();
            $this->assign('count', $count_clc);
        }
        return $this->fetch('house_details');
    }
    public function collection()
    {
        $home_url = Url::build('index/index/index');
        $user_sell = Url::build('index/index/user_sell');
        $user_message = Url::build('index/index/personal_center');
        $sell_info = Url::build('index/index/sell_info');
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $house_details = Url::build('index/index/house_details');
        $this->assign([
            'home_url' => $home_url,
            'user_message' => $user_message,
            'user_sell' => $user_sell,
            'sell_info' => $sell_info,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'user' => session('user'),
            'house_details' => $house_details
        ]);
        if(input('?get.id')){
            $collection = new Collection();
            $collection->destroy(['username'=>session('user'),'id'=>input('get.id')]);
            
        }
        //查询收藏的房源
        $sell = new Sell();
        $rs = $sell->alias('a')->join('collection b','a.id=b.sell_id')->where('b.username',session('user'))->where('a.delete_time',0)->order('b.id','desc')->select();
        $this->assign('rs',$rs);
        
        $photo = new Photo();
        $rs_photo = $photo->order('id desc')->select();
        $this->assign('rs_photo', $rs_photo);
        return $this->fetch('collection');
    }
    public function delete_history()   //回收站
    {
        $home_url = Url::build('index/index/index');
        $user_sell = Url::build('index/index/user_sell');
        $user_message = Url::build('index/index/personal_center');
        $sell_info = Url::build('index/index/sell_info');
        $delete_history = Url::build('index/index/delete_history');
        $logout= Url::build('index/index/logout');//退出登录
        $personal_center = Url::build('index/index/personal_center');
        $house_details = Url::build('index/index/house_details');
        $this->assign([
            'home_url' => $home_url,
            'user_message' => $user_message,
            'user_sell' => $user_sell,
            'sell_info' => $sell_info,
            'logout' => $logout,
            'personal_center' => $personal_center,
            'delete_history' => $delete_history,
            'house_details' => $house_details,
            'user' => session('user')
        ]);
        if (input('?get.id')) {
            $id=input('get.id');
            Db::query("delete from sell where id=$id");
            Db::query("delete from photo where sell_id=$id");
            Db::query("delete from collection where sell_id=$id");
        }
        if (input('?get.id2')) {
            $id2=input('get.id2');
            Db::query("update sell set delete_time=0 where id=$id2");
        }
        $sell=new Sell;
        $rs=$sell->where('username', session('user'))->where('delete_time', 1)->order('id desc')->select();
        $this->assign('rs', $rs);
        $photo = new Photo();
        $rs_photo = $photo->order('id desc')->select();
        $this->assign('rs_photo', $rs_photo);
        return $this->fetch('delete_history');
    }




    public function upload()
    {
        $this->assign('do', Url::build('index/index/do_upload'));
        return $this->fetch('upload');
    }
    public function do_upload()
    {
        // var_dump(count($_FILES['photo']['name']));
        $files = request()->file('photo');
        // var_dump($files);
        foreach ($files as $file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename()."<br>";
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

    public function logout()
    {
        session('user', null);
        $this->redirect('index/index/index');
    }
    public function check_verify($code, $id = '')
    {
        $captcha = new \think\captcha\Captcha();
        return $captcha->check($code, $id);
    }
}
