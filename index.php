<?
  require_once "./tech/parsedown.php";
  $Parsedown = new Parsedown();

  $rss_mode = isset($_GET['rss']) ? 1 : 0;


    $dirs = array("tech", "blog", "culture");

    $ts = array();

    foreach($dirs as $dir) {
      if ($handle = opendir($dir)) {

          while (false !== ($entry = readdir($handle))) {

              if (strpos($entry, ".md") > 0 && strpos($entry, "nopub") == 0) {
                    array_push($ts, $dir . '/' .$entry);
                }
          }

          closedir($handle);
      }
    }

    $ks = array();

    foreach($ts as $t) {
        $r = explode('/', $t, 2);
        $k = $r[1] . '/' . $r[0];
        array_push($ks, $k);
    }

    arsort($ks, SORT_NUMERIC);

    $ts = array();
    foreach($ks as $k) {
        $r = explode('/', $k, 2);
        $t = $r[1] . '/' . $r[0];
        array_push($ts, $t);
    }

    if ($rss_mode == 1) {

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
<?
$rrr = 0;
foreach($ts as $t) {
    if ($rrr == 10) break;
    $rrr += 1;
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
} else {
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="robots" content="index,follow" />
<title>[t:/] troot</title>
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css">
<link rel="stylesheet" type="text/css" href="https://spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-jp.css">
<link rel=stylesheet type=text/css href="style.css">
<!--[if (lt IE 9) & (!IEMobile)]>
<link rel=stylesheet type=text/css href="style2.css">
<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
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
    <div class=home><a href="http://www.troot.co.kr/">[t:/]</a></div>
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


            echo '<li><a class=uline href="./' . $t . '">' . $aa . "</a> <span>" . $date . "</span></li>";
    }
?>
</ul>
<!--
<div class=frontlist>
<ul>
	<li style='padding-bottom:2em'><a class=menulink3 href='http://www.troot.co.kr/'>troot/ <span style='font-weight:200; font-size:0.9em'>처음으로</span></a></li>
  <li style='padding-bottom:2em'><a class=menulink3 href='http://www.troot.co.kr/tech/'>tech/ <span style='font-weight:200; font-size:0.9em'>과학, 공학, 기술, IT</span></a></li>
  <li style='padding-bottom:2em'><a class=menulink3 href='http://www.troot.co.kr/blog/'>blog/ <span style='font-weight:200; font-size:0.9em'>에세이, 정치, 경제, 사회</span></a></li>
  <li style='padding-bottom:2em'><a class=menulink3 href='http://www.troot.co.kr/culture/'>culture/ <span style='font-weight:200; font-size:0.9em'>문화, 감상, 영화, 음악.. </span></a></li>
</ul>
</div>
-->
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
</div>
<a name='comment'></a>
<div style='border: 1px solid #eeeeee; '><div class="fb-comments" data-href="http://www.troot.co.kr<?=$_SERVER['REQUEST_URI']?>" data-width="100%" data-numposts="5"></div></div>
<br><br><br><br><br>
<h6>[t:/] is not "technology - root". dawnsea, 2016, <a style='color:orange' href=http://www.troot.co.kr/?rss>rss</a></h6>
<br><br><br><br><br>
</div>
</body>
</html>
<?
  }
?>
