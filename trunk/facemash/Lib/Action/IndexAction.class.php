<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action{
    public function index(){
	
		if   (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))      
		  {    
		  $realip   =   $_SERVER["HTTP_X_FORWARDED_FOR"];    
		  }    
		  elseif   (isset($_SERVER["HTTP_CLIENT_IP"]))      
		  {    
		  $realip   =   $_SERVER["HTTP_CLIENT_IP"];    
		  }    
		  else      
		  {    
		  $realip   =   $_SERVER["REMOTE_ADDR"];    
		  }

		$file=fopen("record.txt","a");
		fwrite($file,$realip.'@'.gmdate(mb_convert_encoding('Y年m月d日 H:i:s', "GBK", "UTF-8"),time()+8*3600)."\r\n");

		
		if (isset($_GET['school']) && $_GET['school']!=''){
		$school=$_GET['school'];
		$model = M($school);   
		$user = $model->query('select * from __TABLE__ order by rand() limit 2;');
		//print_r($user);
		$this->assign('school',$school);
		$this->assign('user1',$user[0]);
		$this->assign('user2',$user[1]);
		$this->display("Index:user");
		}
		else
		{
			$this->display("Index:index");
		}
	}
	public function choose(){
		print_r($_POST);
		if (isset($_POST['school']) && $_POST['school']!='' && isset($_POST['leftid']) && isset($_POST['rightid']) &&(isset($_POST['left']) || isset($_POST['right'])) ){
			$school=$_POST['school'];
			$model = M($school);
			$user1 = $model->where('sid ='.$_POST['leftid'])->find();
			$user2 = $model->where('sid ='.$_POST['rightid'])->find();
			$e1 = 1/(1+pow(10, ( ($user2['points']-$user1['points'])/400 )));
			$e2 = 1/(1+pow(10, ( ($user1['points']-$user2['points'])/400 )));
				
			if (isset($_POST['left']) && $_POST['left']=="I Love This!"){
				$s1=1;
				$s2=0;
			}
			if (isset($_POST['right']) && $_POST['right']=="I Love This!"){
				$s1=0;
				$s2=1;
			}
			echo $user1['points'].' '.$user2['points'];
			$user1['points'] += round(32*($s1-$e1));
			$user2['points'] += round(32*($s2-$e2));
			echo $user1['points'].' '.$user2['points'];
			$user1['rounds']++;
			$user2['rounds']++;
			$model->where('sid ='.$user1['sid'])->save($user1);
			$model->where('sid ='.$user2['sid'])->save($user2);
			$this->redirect('Index/index', array('school'=>$school), 0,'页面跳转中~');
			$this->display("Index:rank");
			
		}
	}
	public function rank(){
		if (isset($_GET['school']) && $_GET['school']!=''){
			$school=$_GET['school'];
			$model = M($school);  
			$user = $model->query('select * from __TABLE__ where points >1500 order by points DESC limit 10;');
			$this->assign('user',$user);
			$this->assign('school',$school);
			$this->display("Index:rank");
		}
	}
	
		
}
?>