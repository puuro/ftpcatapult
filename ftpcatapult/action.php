<?php
include("conf.php");
$cmd=$_POST['cmd'];
echo $cmd."<br>";

if($cmd=="commit"){
	copy_dir($local_path,"c:/last_commit/");
	//echo shell_exec ( "copy c:/xampp/htdocs/*.* c:/last_commit/ /Y" );
	
	echo "<h1>Done</h1>";
}
else if($cmd=="push"){
	//hae dirlist last_commitista
	$start_dir="c:/last_commit/";
	$target_dir="c:/last_push/";
	$dir_length=strlen($start_dir);
	$dir_list=array();
	$dir_list[]=substr($start_dir,0,$dir_length-1);
	$dir_list=find_dir($start_dir, $dir_list);
	
	set_time_limit(0);
	$file = '';
	$remote_file = '';

	// set up basic connection
	
	$conn_id = ftp_connect($ftp_server);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	
	
	
	
	//käy läpi joka tiedosto joka kansiossa
	foreach($dir_list as $d){
		
		$target=substr($d,$dir_length)."/";
		if($target=="/"){$target="";}
		$files=scandir($d);
		foreach($files as $f){
			//mitä tehdään jokaisella kansiolle
			$target_path=$target_dir.$target.$f;
			//echo "Löytyi ".$d."//".$f."<br>";
			if(is_dir($d."/".$f)&& $f!="." && $f!=".."){
				if(!file_exists($target_path)){
					echo "FTP tee kansio "."/".$target.$f."/"."<br>";
					//kansiota ei ole
					//FTP tee kansio
					$newdir=$remote_path.$target.$f."/";
					if (ftp_mkdir($conn_id, $newdir)) {
					 echo "successfully created ".$newdir."<br>";
					} else {
					 echo "There was a problem while creating ".$newdir."<br>";
					 exit;
					}
				}
			}
			//mitä tehdään jokaiselle tiedostolle
			else if(!is_dir($d."/".$f)&& $f!="." && $f!=".."){
				if(!file_exists($target_path)){
					echo $d."/".$f." on uusi.<br>";
					echo "FTP siirrä tiedosto -> "."/".$target.$f."<br>";
					//tiedostoa ei ole					
					//FTP siirrä tiedosto
					// upload a file
					$file=$d."/".$f;
					$remote_file=$remote_path.$target.$f;
					if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
					 echo "successfully uploaded ".$file."<br>";
					} else {
					 echo "There was a problem while uploading ".$file."<br>";
					}								
				}
				else if(!files_are_equal($d."/".$f,$target_path)){
					echo $d."/".$f." on muuttunut.<br>";
					echo "FTP siirrä tiedosto -> "."/".$target.$f."<br>";				
					//tiedosto on muuttunut
					//FTP siirrä tiedosto
					// upload a file
					$file=$d."/".$f;
					$remote_file=$remote_path.$target.$f;
					if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
					 echo "successfully uploaded ".$file."<br>";
					} else {
					 echo "There was a problem while uploading".$file."<br>";
					}			
					
				}
			}
			
		}
	}
		//onko tiedosto last_pushissa
		//onko tiedosto sama
		//jos ei: tarkista onko kansio olemassa, siirrä ftp:llä
		//jos on: älä siirrä
	//kopioi last_commit->last_push
	ftp_close($conn_id);

	//push();
	copy_dir("c:/last_commit/","c:/last_push/");
	echo "<h1>Done</h1>";
}else {
echo "Unknown command.";
}
//$dir_list=array();
function find_dir($dir, $dir_list){
	$files=scandir($dir);
	//$directories=array();
	foreach ($files as $f){	
		if(is_dir($dir.$f)&& $f!="." && $f!=".."){
			if($f!="ftpcatapult" && $f!="lib"){
				$dir_list[]=$dir.$f;
				//echo $dir.$f."<br>";
				//echo json_encode($dir_list);
				$dir_list=find_dir($dir.$f."/",$dir_list);
			}
		}
	}
	return $dir_list;
}

function files_are_equal($a, $b)
{
  // Check if filesize is different
  if(filesize($a) !== filesize($b))
      return false;

  // Check if content is different
  $ah = fopen($a, 'rb');
  $bh = fopen($b, 'rb');

  $result = true;
  while(!feof($ah))
  {
    if(fread($ah, 8192) != fread($bh, 8192))
    {
      $result = false;
      break;
    }
  }

  fclose($ah);
  fclose($bh);

  return $result;
}
//substr($row['juttu'],0,4)
function copy_dir($start_dir, $target_dir){
	$dir_length=strlen($start_dir);
	$dir_list=array();
	$dir_list=find_dir($start_dir, $dir_list);
	$dir_list[]=substr($start_dir,0,$dir_length-1);
	//echo "Loppu: ".json_encode($dir_list)."<br>";
		echo substr($start_dir,$dir_length)."<br>";
	foreach($dir_list as $d){
		$target=substr($d,$dir_length);
		//echo "Copying ".$d." to ".$target_dir.$target."<br>";
		if(!file_exists($target_dir.$target)){
			shell_exec ( str_replace("/","\\","mkdir ".$target_dir.$target ));			
		}
		//echo str_replace("/","\\", "copy ".$d."/*.* ".$target_dir.$target)." /Y";
		shell_exec ( str_replace("/","\\", "copy ".$d."/*.* ".$target_dir.$target)." /Y" );		
	}
	
}


?>