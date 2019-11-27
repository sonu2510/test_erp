<!-- .breadcrumb -->
<?php
  if(isset($bradcums) && !empty($bradcums)){
	   echo '<ul class="breadcrumb">';
	  foreach ($bradcums as $bradcum) {
		  if($bradcum['class'] == 'active'){
			  echo '<li class="'.$bradcum['class'].'"><i class="fa ' .$bradcum['icon']. '"></i> ' .$bradcum['text']. '</li>'; 
		  }else{
		  	echo '<li><a href="' .$bradcum['href']. '"> <i class="fa ' .$bradcum['icon']. '"></i> ' .$bradcum['text']. '</a></li>'; 
		  }
	  }
	  echo '</ul>';
  }
  ?>

  <?php /*  <ul class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
      <li><a href="#"><i class="fa fa-list-ul"></i>Elements</a></li>
      <li class="active">Components</li>
    </ul> */?>
<!-- / .breadcrumb -->
