<?php

    require_once "parsedown.php";
    require_once "env.php";

    $rss_mode = isset($_GET['rss']) ? 1 : 0;
    $Parsedown = new Parsedown();

    $ts = array();

    if ($handle = opendir('.')) {

        while (false !== ($entry = readdir($handle))) {

            if (strpos($entry, ".md") > 0) {
                  array_push($ts, $entry);
            }
        }

        closedir($handle);
    }

    arsort($ts, SORT_STRING);

    if ($rss_mode == 0) {
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="robots" content="index,follow" />
<title>[t:/] <?=$ct?></title>
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css">
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css">

<link rel=stylesheet type=text/css href="style.css">
<!--[if (lt IE 9) & (!IEMobile)]>
<link rel=stylesheet type=text/css href="style2.css">
<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
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
<div class=frontlist>
<a class=menulink href=http://www.troot.co.kr/>all</a>/ <a class=menulink href=http://www.troot.co.kr/tech/>tech</a>/  <a class=menulink href=http://www.troot.co.kr/blog/>blog</a>/ <a class=menulink href=http://www.troot.co.kr/culture/>culture</a>/ <a class=menulink href='#searchbox'>search</a>/ <a class=menulink href='#comment'>comment</a>/
<hr class='menuhr'>
<ul>

<?php
    foreach($ts as $t) {

            $r = explode('_', $t, 2);

            if (count($r) == 2) {
                $aa = $r[1] == '' ? $t : $r[1];
                $date = $r[0];
            } else {
                $aa = $t;
                $date = "";
            }

            $aa = str_replace(".md", "", $aa);
            if (strpos($aa, "notice") === 0) {
                $aa = str_replace("notice", "", $aa);
                $aa = "<b>".$aa."</b>";

        }

            echo '<li><a class=uline href="./' . $t . '">' . $aa . "</a> <span>" . $date . "</span></li>";
    }
?>
</ul>
<div style='width:20em;'>
    <br><br>
    <a name='searchbox'></a>
    <script>
      (function() {
        var cx = '002323751405420622667:545vq7ukpc8';
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
      })();
    </script>
    <gcse:searchbox></gcse:searchbox>
    <gcse:searchbox-only></gcse:searchbox-only>
</div>
<a name='comment'></a>
<div style='border: 1px solid #eeeeee; '><div class="fb-comments" data-href="http://www.troot.co.kr<?=$_SERVER['REQUEST_URI']?>" data-width="100%" data-numposts="5"></div></div>
</div>
<br><br><br><br><br>
<h6>[t:/] is not "technology - root". dawnsea, 2016, <a style='color:orange' href=http://www.troot.co.kr/?rss>rss</a></h6>
<br><br><br><br><br>
</div>
</body>
</html>
<?php
} else {
  header("Content-type: text/xml;charset=utf-8");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
  $now_date = date("r");
?>

<rss version="2.0">
    <channel>
        <title>[t:/]</title>
        <link>http://www.troot.co.kr/<?=$cate?></link>
        <description>[t:/] is not "technology - root". dawnsea, 2016</description>
        <language>ko</language>
        <pubDate><?=$now_date?></pubDate>
<?php
foreach($ts as $t) {

    $h = fopen($t, "r");
    $line = "";
    if ($h) {
        for ($i = 0; $i < 10; $i++) {
            $line .= fgets($h);
        }
        fclose($h);
    }



        $r = explode('_', $t, 2);

        if (count($r) == 2) {
            $aa = $r[1] == '' ? $t : $r[1];
            $date1 = $r[0];
        } else {
            $aa = $t;
            $date1 = "";
        }

        $date1 = date('r', strtotime($date1));
        $line = htmlspecialchars($Parsedown->text($line));

        $aa = str_replace(".md", "", $aa);
echo "        <item>
              <title>$aa</title>
              <link>http://www.troot.co.kr/$cate/$t</link>
              <description>$line</description>
              <pubDate>$date1</pubDate>
              <guid>http://www.troot.co.kr/$cate/$t</guid>
              <author>[t:/dawnsea]</author>
        </item>
";
}
?>
  </channel>
</rss>
<?php
}
?>
