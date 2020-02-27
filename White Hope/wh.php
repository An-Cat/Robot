<?php
# 检测程序 Ver0.1
# 作者：AnMao
# IDOLL

######################

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

# 初始检查
# 检测用户授权
$user = $_POST['u'];
if ($user == '') {
	exit('Your User is NULL');
}
$key = $_POST['k'];
if ($key == '') {
	exit('Your Key is NULL');
}
##################################################

# 转义消息
$msg = str_replace('.','=',$_POST['msg']);
$msg = str_replace('*','+',$msg);
$msg = base64_decode($msg);
//echo "$msg";
####################################################################

# 引用/获取词汇列表
# 自行制作，数组为二维数组，一维中，越后面的，LV越低，二维为关键词
include 'WH/BanUrl.php';
include 'WH/BanAbc.php';

$blv = 0;

# 检测
# 数组形式 0=9 8=1
# 9-x    9-0=9  9-8=1
$BAN = -1;
$BANC = array();
IA($BanUrl);
IA($BanAbc);
#############################################
if ($BAN > -1) {
	$bn = count($BANC);
	if ($bn != $BAN + 1) {
		exit('ERROR');
	}else{
		for ($i=0; $i < $bn; $i++) { 
			$blv = $blv + $BANC[$i];
		}
	}
}
if ($blv > 9) {
	exit('9');
}
echo $blv;
exit();
 

function IA($x){
	$AN = count($x);
	for ($i=0; $i < $AN; $i++) { 
		CM($x[$i],9-$i);
	}
}
function CM($a,$b){
	# a 检测数组
	# b 等级
	# m 检测信息
	$Num = count($a);//数组数量
	for ($i=0; $i < $Num; $i++) { 
		if ($a[$i] == '') {//判断是否为空
			continue;//跳至下一循环
		}
		$d = 0;//设置起始搜寻
		$f = 0;//设置失败记录为0
		if (stripos($a[$i],'*') === false) {//判断是否包含*
			$d = CA($a[$i],$d);//检测
			if ($d === false) {
				$f = $f + 1;//增加一次失败记录
			}else{
				$d = 2;//置入其他位置
			}
		}else{//包含*时
			$abc = explode('*',$a[$i]);//分割数据
			$N = count($abc);//取数据组
			for ($ii=0; $ii < $N; $ii++) { //检测循环
				$dmp = $d;//记录位置
				$d = CA($abc[$ii],$d);//检测
				if ($d === false) {//判断返回
					$d = $dmp;//false时重新设定位置为原始
					$f = $f + 1;//增加一次失败记录
				}
			}
		}
		if ($d != 0 && $f == 0) {//若$d为0，且$f小于1则此次检测成功
			$temp = $GLOBALS['BAN'] + 1;//取回数量
			$GLOBALS['BAN'] = $temp;//设置数量
			$GLOBALS['BANC'][$temp] = $b;//设置对应等级
		}
	}
}
function CA($abc,$dd){
	# 检测字符
	# 判断是否为空
	if ($abc == '') {
		return false;
	}
	# 检测
	return stripos($GLOBALS['msg'],$abc,$dd);
}
?>