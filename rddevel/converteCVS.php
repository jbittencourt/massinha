#!/usr/local/bin/php
<?

$user_server = "rafaello@cvs.lec.ufrgs.br";
$server_path = "/export/cvsroot";

// File listing function
function filelist ($currentdir, $startdir=NULL, $files=array()) {
  global $user_server,$server_path;
   chdir ($currentdir);

   // remember where we started from
   if (!$startdir) {
       $startdir = $currentdir;
   }
   
   $d = opendir (".");

   //list the files in the dir
   while ($file = readdir ($d)) {
       if ($file != ".." && $file != ".") {
          if (is_dir ($file)) {
             // If $file is a directory take a look inside
             $files = filelist (getcwd().'/'.$file, getcwd(), $files);
          } else {
             // If $ file is not a directory then add it to our output array
	     if($file=="Root") {
	        $dados = file($file);
                $temp = explode(":",$dados[0]);
		$temp[2] = $user_server;
                $temp[3] = $server_path; 
		$dados = implode(":",$temp)."\n";
                
                $fd = fopen($file,"w");
               
                fwrite($fd,$dados);
                fclose($fd);
                
                $files[] = $file;
	     }
             
          }
       }
   }

   closedir ($d);
   chdir ($startdir);
   return $files;

}


$return = filelist("./");

print_r($return);

?>
fc
