<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
    <link rel="stylesheet" type="text/css" href="/public/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/public/css/demo.css" />
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="/public/css/component.css" />
    <!--[if IE]>
    <script src="/public/js/html5.js"></script>
    <![endif]-->
</head>
<body>
<div class="container demo-1">
    <div class="content">
        <div id="large-header" class="large-header">
            <canvas id="demo-canvas"></canvas>
            <div class="logo_box">
                <h3>欢迎你</h3>
                <form id="formid" onsubmit="return false"  action="<?php echo U('Login/do_login');?>" method="post">
                    <div class="input_outer">
                        <span class="u_user"></span>
                        <input name="classname" class="text" style="color: #FFFFFF !important" type="text" placeholder="请输入账户">
                    </div>
                    <div class="input_outer">
                        <span class="us_uer"></span>
                        <input name="password" class="text" style="color: #FFFFFF !important; position:absolute; z-index:100;"value="" type="password" placeholder="请输入密码">
                    </div>
                    <div class="mb2"><input class="act-but submit" type="submit" value="登录" style="color: #FFFFFF;width: 340px;"></div>
                </form>
            </div>
        </div>
    </div>
</div><!-- /container -->
<script src="/public/js/TweenLite.min.js"></script>
<script src="/public/js/EasePack.min.js"></script>
<script src="/public/js/rAF.js"></script>
<script src="/public/js/demo-1.js"></script>
<script type="text/JavaScript" src='/public/js/jquery.min.js'></script>
<script type="text/JavaScript" src='/public/layer/layer.js'></script>
<script type="text/javascript">
    $('#formid').submit(function () {
        $.ajax({
            url:"<?php echo U('Login/do_login');?>",
            type:'POST',
            data:$('#formid').serialize(),
            success:function(result){
                if(result.status)
                {
                    layer.msg(result.msg, function(){
                        window.location.href="index.php/home/index/welcome";
                    });
                }else{
                    layer.msg(result.msg, function(){
                        window.location.href="index.php/home/index/index";
                    });
                }
            },
        });
    });
</script>
</body>
</html>