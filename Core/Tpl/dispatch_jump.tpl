<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<link rel="stylesheet" href="__TEMPLATE__/Public/style/metro.css" />
<style type="text/css">
.alert{
	width:200px;
	margin:70px auto 0;
	
}
</style>
</head>
<body>
<div class="system-message">
<present name="message">
<div class="alert alert-success" style="border:1px solid #ddd"><p style="font-size:18px;font-weight:bold;"><?php echo($message); ?></p>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p></div>
<else/>
<div class="alert alert-error"  style="border:1px solid #ddd"><p style="font-size:18px;font-weight:bold;"><?php echo($error); ?></p>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p></div>

</present>

</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>