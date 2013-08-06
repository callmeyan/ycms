<?php
session_start();
/**
 * php validatecode class
 * @author spedyan<p>
 *  2011-2-23 18:28</p>
 */
class VC{
	/******************公用属性*********************/
	/**
	 * 验证码长度
	 * @var int
	 */
	public $len=4;
	/**
	 * 验证码类型,0.混合(即数字大小写字母混合);1.数字;2.大小写混合字母;3.大写字母,默认为0
	 * @var int
	 */
	public $type=0;
	/**
	 * 验证码图片长度
	 * @var int
	 */
	public $width=100;
	/**
	 * 验证码图片高度
	 * @var int
	 */
	public $height=25;
	//背景色的红绿蓝，默认是浅灰色
	/**
	 * 背景色的红，默认是238,浅灰色可选范围是0-255
	 * @var int
	 */
	public $red=238;
	/**
	 * 背景色的绿，默认是238,浅灰色可选范围是0-255
	 * @var int
	 */
	public $green=238;
	/**
	 * 背景色的蓝，默认是238,浅灰色可选范围是0-255
	 * @var int
	 */
	public $blue=238;
	/**
	 * Y轴是否随机,设为 false 表示不启用,默认true
	 * @var boolean
	 */
	public $isY=TRUE;
	/**
	 * 是否添加干扰点,设为 false 表示不添加,默认false
	 * @var boolean
	 */
	public $isPixel=false;
	/**
	 * 干扰点数目,请开启干扰点后设置此数值,默认100
	 * @var int
	 */
	public $pixelNum=100;
	/**
	 * 是否添加干扰线,设为 false 表示不添加,默认true
	 * @var boolean
	 */
	public $isLines=FALSE;
	/**
	 * 是否启用专有字体,设为 false 表示不启用,默认false
	 * @var boolean
	 */
	public $isTTF=FALSE;
	/**
	 * 是否随机颜色
	 * @var boolean
	 */
	public $isRandColor=TRUE;
	/**
	 * 验证码颜色使用必须将随机颜色设置为false,类似2,2,238
	 * @var String
	 */
	public $color="2,2,238";
	/******************私有属性*********************/
	/**
	 * 验证码字符
	 * @var String
	 */
	private $validateCode;
	/**
	 * 验证码对象
	 * @var Object
	 */
	private $image;
	/**
	 * 验证码Y轴
	 * @var int
	 */
	private $y;
	/**
	 * 随机色
	 * @var Object
	 */
	private $randcolor;

	/**
	 * 干扰线数目
	 * @var int
	 */
	private $lineNum=2;

	/**
	 * 生成验证码字符串,并将验证码保存到session中
	 */
	private function _createRandCode(){
		$validateStr=array();
		$validateStr[0] = "0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$validateStr[1] = "0,1,2,3,4,5,6,7,8,9";
		$validateStr[2] = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$validateStr[3] = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
		$validateStrList = explode(",", $validateStr[$this->type]);
		$code="";
		$count=count($validateStrList);
		for($i=0; $i<$this->len; $i++){
			$randnum = rand(0, $count);
			if($randnum==$count||$randnum==-1){
				$randnum=0;
			}
			$code .= $validateStrList[$randnum];
		}
		$upperCode=strtoupper($code);
		$_SESSION["cwvc_vcode"]=$upperCode;
		$this->validateCode=$code;
	}

	/**
	 * 生成验证码所需图片
	 */
	private function _createImage(){
		//生成图片
		$this->image=imagecreate($this->width, $this->height);
		//加载背景,默认浅灰色
		imagecolorallocate ($this->image, $this->red,$this->green, $this->blue);
	}
	/**
	 * 获得验证码图片Y轴
	 */
	private function _getY () {
		if ($this->isY){
			if($this->height>25){
				$this->y = rand(5, $this->height-15);
			}else{
				$this->y = rand(5, $this->height/5);
			}
		}
		else{
			$this->y = $this->height / 4 ;
		}
	}
	/**
	 * 获得随机色
	 */
	private function _getRandcolor () {
		if($this->isRandColor){
			$this->randcolor = imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
		}else{
			$color=explode(",", $this->color);
			$this->randcolor = imagecolorallocate($this->image,$color[0],$color[1],$color[2]);
		}
	}
	private function _setLines(){
		if ($this->isLines) {
			for($j = 0; $j < $this->lineNum; $j++){
			 $rand_x = rand(2, $this->width);
			 $rand_y = rand(2, $this->height);
			 $rand_x2 = rand(2, $this->width);
			 $rand_y2 = rand(2, $this->height);
			 $this->_getRandcolor();
			 imageline($this->image, $rand_x, $rand_y, $rand_x2, $rand_y2, $this->randcolor);
			}
		}
	}
	/**
	 * 添加干扰点
	 */
	private function _setExtPixel () {
		if ($this->isPixel) {
			for($i = 0; $i < $this->pixelNum; $i++){
				$this->_getRandcolor();
				imagesetpixel($this->image, rand()%100, rand()%100, $this->randcolor);
			}
		}
	}

	/**
	 * 生成验证码
	 */
	public function _createVC(){
		header ("Content-type: image/gif");
		$this->_createRandCode();
		$this->_createImage();
		//逐一添加验证码到图片
		for($i = 0; $i < $this->len; $i++){
			$x    = $i/$this->len * $this->width + rand(1, $this->len);
			$this->_getY();
			$this->_getRandcolor();
			$text=substr($this->validateCode, $i ,1);
			if($this->isTTF){
				$fontNum=rand(1, 5);
				$fontfile="ttf/$fontNum.ttf";
				imagettftext($this->image, 14, 0, $x, $this->height-5, $this->randcolor, $fontfile, $text);
			}else{
				imagestring($this->image, 5, $x, $this->y,$text , $this->randcolor);
			}
		}
		//干扰点
		$this->_setExtPixel();
		$this->_setLines();
		imagegif($this->image);
		imagedestroy($this->image);
	}
	/**
	 * 验证用户输入的验证码是否正确
	 * @param string $codeStr
	 * @return boolean 验证码正确返回true错误返回false
	 */
	public function validation($codeStr){
		if(strtoupper($codeStr)==$_SESSION["vcode"]){
			return true;
		}
		return false;
	}
}
?>