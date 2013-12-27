<!doctype html>
<html>
  <head>
    <title>img load</title>
    <link rel="stylesheet" href="ball/css/bootstrap.css" />
    <link rel="stylesheet" href="ball/css/bootstrap-responsive.css" />
    <style>
      #images{
        width: 100px;
        height: 100px;
        padding: 20px;
      }
      #imagesClicked{
        z-index: 20;
        width: 100%;
        height: 100%;
        padding: 20px;
      }
    </style>
  </head>
  <body>
    <div id="form">
    <form enctype="multipart/form-data" action="/getImgData.php" method="POST">
        <input class="btn" type="file" name="dataFile" size="40" />
        <input type="submit" class="btn btn-inverse" />
    </form>
    </div>
    <div>
<?php
$host='localhost';
$database='webData';
$usr='root';
$psw='';
$dbh=mysql_connect($host,$usr,$psw,$database) or die("Нет соединения!");

$allowedExt = array("gif","jpeg","jpg","png");
$temp = explode(".",$_FILES['dataFile']['name']); //Разделение строки по точке и вставка в массив
$extension = end($temp);

if((($_FILES['dataFile']['type'] == "image/gif")
||($_FILES['dataFile']['type'] == "image/jpeg")
||($_FILES['dataFile']['type'] == "image/jpg")
||($_FILES['dataFile']['type'] == "image/pgpeg")
||($_FILES['dataFile']['type'] == "image/x-png")
||($_FILES['dataFile']['type'] == "image/png"))
&& ($_FILES['dataFile']['size']>20000)
&& in_array($extension,$allowedExt)){
    if($_FILES['dataFile']['error']>0){
        echo "<div>Файл не был загружен ибо ".$_FILES['dataFile']['size']."</div>";
    }else{
        if(file_exists("img/".$_FILES['dataFile']['name'])){
            echo "<div>Файл не был загружен ибо уже существует</div>";
        }else{
            $query = "INSERT INTO webData.images (src,filename) VALUES ('/img/','".$_FILES['dataFile']['name']."')";
            $rez = mysql_query($query);
            move_uploaded_file($_FILES['dataFile']['tmp_name'],"img/".$_FILES['dataFile']['name']);
            echo "<div>Файл был загружен и путь к нему лежит по адресу /img/".$_FILES['dataFile']['name']."</div>";
        }
    }
}else{
    echo "<div>Инвалид файл</div>";
}

/*if(strlen($_FILES['dataFile']['name'])>0){
    echo "<div>Был загружен, мать его, файл: ".$_FILES['dataFile']['name']."</div>";
}else{
    echo "<div>Файл не был загружен.</div>";
}
*/

$query = "SELECT * FROM webData.images";
$rez = mysql_query($query);
while($row = mysql_fetch_array($rez))
{
    echo "<img onclick='onImgClick(this)' id='images' src=".$row['src'].$row['filename']."></img>";
    echo "<br />";
}
mysql_close($dbh);
?>
    </div>
    <script type="text/javascript" src="ball/js/jquery.js"></script>
    <script type="text/javascript" src="ball/js/bootstrap.min.js"></script>
    <script>
        function onImgClick(image){
            if(image.id == "images"){
                image.id = "imagesClicked";
            }else{
                image.id = "images";
            }
            
        }
    </script>
  </body>
</html>