<?php

/*
 * Generate Slug
 * 
 * Can generate user friendly url when pass in any scring
 * echo GenerateUrl ("Pâtisserie (Always FRESH!)"); //returns "patisserie-always-fresh"
 */

function getLimit(){
	$limit_array=array('10','20','30','40','50','100','200','500');
	return $limit_array;	
}

function slug($s) {
    $from = explode(',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,(,),[,],'");
    $to = explode(',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,,,,,,');
    $s = preg_replace('~[^\w\d]+~', '-', str_replace($from, $to, trim($s)));
    return strtolower(preg_replace('/^-/', '', preg_replace('/-$/', '', $s)));
}

function post($post){
	global $obj_db;
	$new_post = array();
	foreach($post as $key=>$val){
		if(!is_array($val)){
			$new_post[$key] = $obj_db->escape(trim($val));
		}else{
			$new_post[$key] = $val;
		}
	}
	return $new_post;
}




function send_email($to, $from, $subject,$message,$attachments,$url='',$n='0',$to_bcc = '0',$to_cc='0') {
	
//	printr($n);//die;
	
	include_once 'php_mailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
    $mailibcc = ' ';
  //  $mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->SMTPSecure = "tls"; // sets the prefix to the servier
//	$mail->SMTPDebug = true;
	$mail->Host ="smtp.gmail.com"; // sets GMAIL as the SMTP server "smtp.gmail.com    //mail.swissonline.in
    $mail->Username = "swisspacproduction@gmail.com"; // GMAIL username
    $mail->Password = "swisspac@123"; // GMAIL passward
    $test_email='swisspacproduction@gmail.com';
    $from='swisspacproduction@gmail.com';
	$mail->Port =587;// set the SMTP port for the GMAIL server   587
	$referer = $from;
	$mail->setFrom($from,$referer);

	$mail->AddAddress($test_email); 
    if($to_bcc==0)
    {   
        $mail->AddBCC($mailibcc);
    }
    else
    {  
    }   
    
    if($to_cc!=0)
    {  
    }
     
	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->IsHTML(true);                                  // Set email format to HTML
	$mail->Subject =$subject;
	$mail->Body    =$message;
	$mail->AltBody ="View the mail";
	
	
	if(isset($url) && !empty($url)) {		
		$string = file_get_contents($url);
		$mail->AddStringAttachment($string, $subject.'.pdf', $encoding = 'base64', $type = 'application/doc');
	}
		// printr($attachments);//die;	
		// printr($mail);
    if(isset($attachments) && !empty($attachments)){
		//Attach an image file
		 //var_dump($attachments);
	
	    foreach($attachments as $attachments){
			if($n=='1'){
		//	   printr($attachments['image_path']);
				$mail->addAttachment($attachments);
		    }  else{
		       // printr('1111');
				$mail->addAttachment($attachments['image_path']);
		    }
	    }
	}
	//var_dump($attachments);
    $mail->addAttachment($attachments[0]);
	
	 //	printr($mail);
	 //	die;
	if(!$mail->Send())
	{
	   echo 'Mailer Erroor:'.$mail->ErrorInfo;
		return false;
	}
	else
	{
		$mailerror = 'Email Has Been Sent ';
	
		return true;
	}
	
	//die;	
}

function excerpt($string, $length = 60) {
    $return = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length)) . "...";
    return $return;
}

/*
 * Returns Physical Page title without extension
 * 
 * @return curent php file name
 */

function current_file() {
    $pageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", basename($_SERVER['PHP_SELF']));
    return $pageName;
}

/**
 * Helper function to work out the base URL
 *
 * @return string the base url
 */
function base_url() {
    global $config;
    if (isset($config['base_url']) && $config['base_url'])
        return $config['base_url'];

    $url = '';
    $request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
    $script_url = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';
    if ($request_url != $script_url)
        $url = trim(preg_replace('/' . str_replace('/', '\/', str_replace('index.php', '', $script_url)) . '/', '', $request_url, 1), '/');

    $protocol = get_protocol();
    return rtrim(str_replace($url, '', $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), '/');
}

/**
 * Tries to guess the server protocol. Used in base_url()
 *
 * @return string the current protocol
 */
function get_protocol() {
    preg_match("|^HTTP[S]?|is", $_SERVER['SERVER_PROTOCOL'], $m);
    return strtolower($m[0]);
}


/**
	 * Runs a query and record update.
	 *	
	 * @return string Get page name
*/
function curPageName()
{	
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}  


/**
	 * Runs a Function and get Latitude and Longitude.
	 *
	 * @param string   Pass Address
	 * @param string   Pass City
	 * @param string   Pass State
 	 * @param string   Pass Country
	 *
	 * @return string
*/
function getLatandLong($addr,$city,$state,$country)
{
	try{
			global $lat;
			global $lng;
			 
			$doc = new DOMDocument();
			//echo "http://maps.google.com/maps/api/geocode/xml?address=".$addr.",+".$city.",+".$state."&sensor=false";
			$doc->load("http://maps.google.com/maps/api/geocode/xml?address=".$addr."+".$city."+".$state."+".$country."&sensor=true"); //input address
			$results = $doc->getElementsByTagName("status");
			
			if($results->item(0)->nodeValue == "OK"){
					$results = $doc->getElementsByTagName("result");
					$results = $results->item(0);
					$results = $results->getElementsByTagName("geometry");
					$results = $results->item(0);
					$results = $results->getElementsByTagName("location");
					 
					foreach($results as $result){
						$lats = $result->getElementsByTagName("lat");
						$lat = $lats->item(0)->nodeValue;
						 
						$lngs = $result->getElementsByTagName("lng");
						$lng = $lngs->item(0)->nodeValue;
						$str = $lat.",".$lng;
				   }
			}else{
				$doc->load("http://maps.google.com/maps/api/geocode/xml?address=".$city."+".$state."+".$country."&sensor=true"); //input address
				$results = $doc->getElementsByTagName("status");
				if($results->item(0)->nodeValue == "OK"){
						$results = $doc->getElementsByTagName("result");
						$results = $results->item(0);
						$results = $results->getElementsByTagName("geometry");
						$results = $results->item(0);
						$results = $results->getElementsByTagName("location");
						 
						foreach($results as $result){
							$lats = $result->getElementsByTagName("lat");
							$lat = $lats->item(0)->nodeValue;
							 
							$lngs = $result->getElementsByTagName("lng");
							$lng = $lngs->item(0)->nodeValue;
							$str = $lat.",".$lng;
					   }
				}else{
					$str = ",";
				}
			}
	}
	catch(Exception $e){
		$str = 0;
	}
   
	return $str;
 }
 
 
/**
	 * Runs a Function and rediract to other page.
	 *
	 * @param string   Pass Page name 
	 *
	 * @return ''
*/
function page_redirect($page){

	$page = str_replace(" ","-",$page);
	/*
	print "<script type="text/javascript">";
	print "window.location = '$page'";
	print "</script>";*/
	?>
	<script type="text/javascript">
	 	var getPage = "<?php echo $page;?>";
		var setPage = getPage.replace(/&amp;/g, '&');
		window.location = setPage;
	</script>
    <?php 
	die;
}


/**
	 * Runs a Function and Check this value exist or not.
	 *
	 * @param string   Pass Table Name
	 * @param string   Pass Col name
	 * @param string   Pass Col Value
	 * @param int   Pass id
	 * @param int   Pass Field Value [ Pass Id]
	 *
	 * @return int 
*/
function check_value($table,$col,$val,$id=NULL,$field_name=NULL)
{
	
	$r = "";
	if($field_name != "")
	{
		$sql="select * from $table where $col = '$val' and  $field_name != '$id'";
	}
	else
	{
		$sql="select * from $table where $col = '$val' ";
	}
	
	$ref= mysql_query($sql) or die(" :: ERROR ::".mysql_error());
	$num = mysql_num_rows($ref);
	if($num > 0)
	{
		$r = "0";
	}
	else
	{
		$r = "1";
	}
	return $r;
} 



/**
	 * Runs a Function and  Change Date and time format
	 *
	 * @param date   Pass Date in this param
	 * @param int    Pass Format number [ (1) DATE_FORMATE (2) TIME_FORMATE (3) DATE_TIME_FORMATE ] 
	 * @param string Pass if date pass any value if date not pass in strtotime. if this paramiter is empty then not convert into strttotime
	 *
	 * @return date 
*/
function get_date_time_format($date,$format_number,$strtotime=NULL){
	
	if($strtotime != ""){
		$date_to_strtotime = strtotime($date);
	}else{
		$date_to_strtotime = $date;
	}
	switch($format_number)
	{
		case 1:  return date(DATE_FORMATE,$date_to_strtotime);
				 break;
		case 2:  return date(TIME_FORMATE,$date_to_strtotime);
				 break;
				 
		case 3:  return date(DATE_TIME_FORMATE,$date_to_strtotime);
				 break; 
	}
} 

function dateFormat($format_number,$date){
	$date_to_strtotime = strtotime($date);
	switch($format_number)
	{
		case 1:  return date(DATE_FORMATE1,$date_to_strtotime);
				 break;
		case 2:  return date(DATE_FORMATE2,$date_to_strtotime);
				 break;
		case 3:  return date(DATE_FORMATE3,$date_to_strtotime);
				 break; 
		case 4:  return date(DATE_FORMATE4,$date_to_strtotime);
				 break; 
		case 5:  return date(DATE_FORMATE5,$date_to_strtotime);
				 break; 		 		 
	}
} 

/**
	 * Runs a Function and  Change Number format
	 *
	 * @param Number   Pass Number and get Number format 
	 *
	 * @return number 
*/

function NumberFormat($amount,$type=1) 
{	
	if($type ==1){
		return number_format($amount,2);
	}
}


/**
	 * Runs a Function and get array in format
	 *
	 * @param array   Pass array and get formated array
	 *
	 * @return array 
*/
function printr($arr)
{	

	?>
	<script type="text/javascript">
	 	$("#nav").remove();
	</script>
    <?php 
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}  

/**
	 * Runs a Function and Convert in Encode
	 *
	 * @param String   Pass string and conver into Encode string
	 *
	 * @return string 
*/
function encode($str)
{
	return base64_encode($str);
}

/**
	 * Runs a Function and Convert in Dencode
	 *
	 * @param String   Pass string and conver into Dencode string
	 *
	 * @return string 
*/
function decode($str)
{
	return base64_decode($str);
}

/**
	 * Runs a Function
	 *
	 * @param String   Pass string and remove space 
	 *
	 * @return string 
*/
function get($get)
{
	return mysql_real_escape_string(trim($get));
}


/**
	 * Runs a Function
	 *
	 * @param String   Replace Special character in url string 
	 *
	 * @return string 
*/
function htaccess_url($name){
	$replace_text = array("!", "@", "#", "$", "%", "^", "&", "*", "(", "{", "}", " ", ",",":",";",".");
	return str_replace($replace_text, "-",$name);
}
	
/**
	 * Runs a Function
	 *
	 * @param String   Replace Special character in File Name
	 *
	 * @return string 
*/

function get_file_extension($file_name)
{
  return substr(strrchr($file_name,'.'),1);
}

function file_name($name){
	$replace_text = array(" ", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "{", "}", "[", "]", "'", ";", ":", "/", ">",
	 "<", "?", ",");
	return str_replace($replace_text, "-",$name);
}

/**
	 * Runs a Function
	 *
	 * @param Date   Pass Date of Birthday and get Age
	 *
	 * @return int [Age] 
*/	
function age_from_dob($dob) {
	return floor((time() - strtotime($dob)) / 31556926);
}

/*
 	* Runs a Function
	 *
	 * @param Number   Pass  Lat
 	 * @param Number   Pass  Long
 	 * @param Number   Pass  Lat
 	 * @param Number   Pass  Long
	 * @param Char     Pass  Char 
	 *
	 * @return int [ Get Number of KM between two Lat and Long] 
	User 	
	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "m") . " miles<br>";	
*/
function distance($lat1, $lon1, $lat2, $lon2, $unit){
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

/*
 	* Runs a Function  : Get List of Time Zone 
	 *
	 * @param string  Pass selected Time Zone
	 *
	 return drop down list 
	 
*/

function gettimezone_combo($sel='')
{
	$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
	if(!empty($tzlist)){
		$tzlistcombo = '<select name="selecttime_zone" id="selecttime_zone">';
		foreach($tzlist as $key=>$val){
			//echo $val.'<br>';
			if($sel == $val){
				$tzlistcombo .= '<option value="'.$val.'" selected = "selected" >'.$val.'</option>';
			}else{
				$tzlistcombo .= '<option value="'.$val.'">'.$val.'</option>';
			}
		}
		$tzlistcombo .= '</select>';
	}else{
		$tzlistcombo = '';
	}
	return $tzlistcombo;
}
 
/*
 	* Runs a Function  : Move Image in upload folder
	 *
	 * @param string  Pass Temp Dir path
 	 * @param string  Pass File Name 
	 * @param string  Pass File Path	 
	 *
	 return drop down list 
	 
*/
function uploadfile($temp,$filename,$path)
{
	//echo $temp."=====".$filename."======".$path;die;			
	move_uploaded_file($temp,$path.$filename);	
	return "<br>".$path.$filename."<br>";
}

/*
 	 * Runs a Function  : Include Modules and Files
	 *
	 * @param string  Pass Modules Name
 	 * @param string  Pass Page Name 
	 *
	 return Path
	 
*/ 
function dispaly_include_page($module=NULL,$pagename=NULL)
{
	$path='';
	if(isset($module) && isset($pagename) && $module != "" && $pagename !=""){
		$path = $module."/".$pagename;
	}else if (isset($module) && $module != ""){
		$path = $module."/index.php";
	}else{		
		$path = "dashboard/index.php";
	} 	 
	return "controller/".$path;
}

function include_controller_page($module){
	if (isset($module) && $module != ""){
		$path = $module;
	}else{		
		$path = "dashboard/index.php";
	}
	return "controller/".$path;
}


/*
 	Retrun sub string 
*/ 
function string_limit_words($string, $word_limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
} 

/*
Run this function for get full url
*/
function full_url($s)
{
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
    return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
}


//general function for used in over code

function checkUserSession(){
	if(isset($_SESSION['USER_LOGIN_ID_456']) && !empty($_SESSION['USER_LOGIN_ID_456']) && isset($_SESSION['USER_LOGIN_EMAIL_456']) && !empty($_SESSION['USER_LOGIN_EMAIL_456']) ){
		return true;
	} else {
		return false;
	}
}

function checkCookie(){
	if(isset($_COOKIE['cook_country']) && !empty($_COOKIE['cook_country']) && isset($_COOKIE['cook_state']) && !empty($_COOKIE['cook_state']) && isset($_COOKIE['cook_city']) && !empty($_COOKIE['cook_city'])){
		return true;
	} else {
		return false;
	}
}

function display_desc($text){
	return strip_tags($text);
}

//check admin is login or not
function is_loginAdmin(){
	global $obj_session;
	//printr($obj_session->data);die;
	if(isset($obj_session->data['ADMIN_LOGIN_SWISS']) && !empty($obj_session->data['ADMIN_LOGIN_SWISS']) && (int)$obj_session->data['ADMIN_LOGIN_SWISS'] > 0 && isset($obj_session->data['ADMIN_LOGIN_EMAIL_SWISS']) && !empty($obj_session->data['ADMIN_LOGIN_EMAIL_SWISS']) )
	{
		return true;
	} else {
		return false;
	}
}

function getName($tableName,$coloumName,$coloumValue,$returnColoum){
	global $obj_db;
	$sql = "SELECT $returnColoum FROM " . DB_PREFIX . "$tableName WHERE $coloumName = '".$coloumValue."'";
	$data = $obj_db->query($sql);
	//printr($data);die;
	if($data->num_rows){
		return $data->row["$returnColoum"];
	}else{
		return '-';
	}
}

function sortAssociateArrayByKey($array,$sortingKey,$sortOrder){
	$newArray = array();
	foreach ($array as $key => $row)
	{
		$newArray[$key] = $row["$sortingKey"];
	}
	array_multisort($newArray, $sortOrder, $array);
	return $array;
}

function getExtension($str){
	$i = strrpos($str,".");
	if (!$i)
	{
		return "";
	}
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

function validateUploadImage($data){
	$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
	$imagename = $data['name'];
	$size = $data['size'];
	//echo $size;die;
	if(strlen($imagename))
	{
		$ext = strtolower(getExtension($imagename));
		if(in_array($ext,$valid_formats))
		{
			//if($size<(1024*1024)) // Image size max 1 MB
			return  strtolower($ext);
		}
	}
	else
	{
		return false;
	}
}
function validateUploadPdf($data){
	$valid_formats = array("pdf");
	$pdfname = $data['name'];
	$size = $data['size'];
	//echo $size;die;
	if(strlen($pdfname))
	{
		$ext = strtolower(getExtension($pdfname));
		if(in_array($ext,$valid_formats))
		{
			//if($size<(1024*1024)) // Image size max 1 MB
			return  strtolower($ext);
		}
	}
	else
	{
		return false;
	}
}
function compressImage($ext,$uploadedfile,$path,$actual_image_name,$newwidth)
{
	
	list($width,$height)=getimagesize($uploadedfile);
	$newheight=($height/$width)*$newwidth;
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	if($ext=="jpg" || $ext=="jpeg" )
	{
		$src = imagecreatefromjpeg($uploadedfile);
	}
	else if($ext=="png")
	{
		$src = imagecreatefrompng($uploadedfile);
	}
	else if($ext=="gif")
	{
		$src = imagecreatefromgif($uploadedfile);
	}
	else
	{
		$src = imagecreatefrombmp($uploadedfile);
	}
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	
	$filename = $path.$newwidth.'_'.$actual_image_name; //PixelSize_TimeStamp.jpg
	imagejpeg($tmp,$filename,100);
	imagedestroy($tmp); 
	 
	return $filename;
}
/*$logoPath = DIR_STOREADMIN."controller/store/logo-copy.png";
function watermarkProcess($oldImageName, $newImageName)
{
	global $logoPath;
    list($oldImageWidth,$oldImageHeight) = getimagesize($oldImageName);
    $width  = 300;
	$height = 300;  
	
	// CREATING TEMPORARY IMAGE
    $imageTmp = imagecreatetruecolor($width, $height) or die('Cannot Initialize new GD image stream');
	
	// GET TEMPORARY IMAGE INFO(WIDTH & HEIGHT)
	$info = getimagesize($oldImageName);
	
	// Get image path from jpg/jpeg types
	if($info[2] == IMAGETYPE_JPEG){
	 	$imageTmpPath = imagecreatefromjpeg($oldImageName) or die('JPEG/JPEG Image type is open failed');
	}
	
	// Get image path from gif type
	if($info[2] == IMAGETYPE_PNG){
		$imageTmpPath = imagecreatefrompng($oldImageName) or die('PNG Image type is open failed');
	}
	
	// Get image path from png type
	if($info[2] == IMAGETYPE_GIF){
		$imageTmpPath = imagecreatefromgif($oldImageName) or die('GIF Image type is open failed');
	}

	// Copy and resize part of an image with resampling
    imagecopyresampled($imageTmp, $imageTmpPath, 0, 0, 0, 0, $width, $height, $oldImageWidth, $oldImageHeight);
	
	// Get watermark image temp path
    $watermark = imagecreatefrompng($logoPath);
	
	// Assigning images size to the array 
    list($tempWidth, $tempHeight) = getimagesize($logoPath);  
	
	// Deciding watermark image position      
    $pos_x = $width - $tempWidth; 
    $pos_y = $height - $tempHeight;
	
	// Creating/Copy image by inserting watermark
    imagecopy($imageTmp, $watermark, $pos_x, $pos_y, 0, 0, $tempWidth, $tempHeight);	
	// Create image for jpg/jpeg types
	if($info[2] == IMAGETYPE_JPEG){
	 	imagejpeg($imageTmp, $newImageName, 100);
	}	
	// Create image for gif type
	if($info[2] == IMAGETYPE_PNG){
		imagepng($imageTmp, $newImageName, 0);
	}	
	// Create image for png type
	if($info[2] == IMAGETYPE_GIF){
		imagegif($imageTmp, $newImageName, 100);
	}   
    // delete temporary black color image
    imagedestroy($imageTmp); 	
	// delete uploaded original image
    unlink($oldImageName); 	
    return true;
}*/
function getDevice(){
	$tablet_browser = 0;
	$mobile_browser = 0;
	 
	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$tablet_browser++;
	}
	 
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
	}
	 
	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}
	 
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');
	 
	if (in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}
	 
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		$mobile_browser++;
		//Check for tablets on opera mini alternative headers
		$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
		  $tablet_browser++;
		}
	}
	 
	if ($tablet_browser > 0) {
	   // do something for tablet devices
	   $device = 'tablet';
	}
	else if ($mobile_browser > 0) {
	   // do something for mobile devices
		$device = 'mobile';
	}
	else {
	   // do something for everything else
		$device = 'desktop';
	} 
	return $device;
}



?>
