<?php
//common function

//print array
function printr($array){
	echo "<pre>";
		print_r($array);
	echo "</pre>";
}

//return post data
function post($data){
	return $data;
}

//redirect page
function redirect($url){
	?>
    <script type="text/javascript">
		window.location = '<?php echo $url;?>';
	</script>
    <?php
}

?>