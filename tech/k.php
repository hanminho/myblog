<?php

    if (isset($_GET['md'])) $md = trim($_GET['md']); else exit();

    require_once "parsedown.php";
//    require_once "env.php";




    $Parsedown = new Parsedown();

    if (!file_exists($md)) exit();

    $v = fopen($md, 'r');
    if ($v == FALSE) exit();

    $k = fread($v, filesize($md));

    $t = explode("/", $md);
    $cate = $t[5];
    $ct = $cate;


    $t = $t[6];

    $t = explode("_", $t);
    $t = $t[1];


    fclose($v);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>[t:/] <?=$t?></title>
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css">
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css">
<link rel=stylesheet type=text/css href="style.css">
<!--[if (lt IE 9) & (!IEMobile)]>
<link rel=stylesheet type=text/css href="style2.css">
<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
<link rel="stylesheet" href="github.css">
<script src="highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<title><?=$t?></title>
</head>
<body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.8&appId=176381142821821";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div class=page>
    <div class=home><a href="http://www.troot.co.kr/">[t:/</a><a href="http://www.troot.co.kr/<?=$cate?>/"><?=$ct?>]</a></div>
    이사 오고 있는 중입니다.. 개판 오분 전..
<?php


    echo $Parsedown->text($k);
?>
<br><br><br><br>
<div class="fb-like" data-href="http://www.troot.co.kr<?=$_SERVER['REQUEST_URI']?>" data-layout="button" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
<br><br><br><br><br><br><br><br>
<div style='border: 1px solid #eeeeee'><div class="fb-comments" data-href="http://www.troot.co.kr<?=$_SERVER['REQUEST_URI']?>" data-width="100%" data-numposts="5"></div></div>
<br><br><br><br><br>
<h6>[t:/] is not "technology - root". dawnsea, 2016, <a style='color:orange' href=http://www.troot.co.kr/?rss>rss</a></h6>
<br><br><br><br><br>
</div>
</body>
</html>
