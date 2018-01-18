<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\phpStudy\WWW\excel\public/../application/index\view\interview\regular.html";i:1516070506;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/JavaScript" src='/static/jquery.min.js'></script>
</head>
<body>
    <form>
        <input type="text" class="inp" name=""/>
        <input id="btn" type="button" value="锁定" />
    </form>
    <script type="text/javascript">
        var button = document.getElementById('btn');
        button.onclick=function()
        {
            if(button.value=='锁定')
            {
                $('.inp').attr('disabled',true);
                button.value='未锁定';
            }else{
                $('.inp').attr('disabled',false);
                button.value='锁定';
            }
        }
    </script>
</body>
</html>