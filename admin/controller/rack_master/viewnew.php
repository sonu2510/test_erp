<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';
if(isset($_GET['goods_master_id']) && !empty($_GET['goods_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$goods_master_id = base64_decode($_GET['goods_master_id']);
		$goods_data = $obj_goods_master->getGoodsData($goods_master_id);
		$stock_data = $obj_rack_master->getStockByGoodsId($goods_master_id);
	}
}
//Close : edit
if($display_status){
	
?>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="jquery,ui,easy,easyui,web">
	<meta name="description" content="easyui help you build your web page easily!">

<section id="content">
	<section class="main padder">
		<div class="clearfix">
			<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
		</div>
		<div class="row">
			<div class="col-lg-12">
			<?php include("common/breadcrumb.php");?>	
			</div> 
		<div class="col-sm-12">
        
        <section class="panel">  
        
        	<header class="panel-heading bg-white">
        		<span>Rack Detail</span> 
        		</header>
        
        	<?php if($goods_data) { ?>
        	<div class="panel-body">
        		<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
        
        		<!--div class="form-group">
        			<label class="col-lg-3 control-label">Row</label>
        			<div class="col-lg-4">
        				<label class="control-label normal-font">
        				<?php echo $goods_data['row'];?>
        				</label>
        			</div>
        		</div>
        
        		<div class="form-group">
        			<label class="col-lg-3 control-label">Column</label>
        			<div class="col-lg-4">
        				<label class="control-label normal-font">
        				<?php echo $goods_data['column_name'];?>
        				</label>
        			</div>
        		</div-->
<ul class="products">
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg"/>
				<div>
					<p>Balloon</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg"/>
				<div>
					<p>Feeling</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg" height="100" width="100"/>
				<div>
					<p>Elephant</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg" height="100" width="100"/>
				<div>
					<p>Stamps</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg" height="100" width="100"/>
				<div>
					<p>Monogram</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="item">
				<img src="../upload/Chrysanthemum.jpg" height="100" width="100"/>
				<div>
					<p>Rolling</p>
					<p>Price:$25</p>
				</div>
			</a>
		</li>
	</ul>
	<div class="cart">
		<h1>Shopping Cart</h1>
		<div style="background:#fff">
		<table id="cartcontent" fitColumns="true" style="width:300px;height:auto;">
			<thead>
				<tr>
					<th field="name" width=140>Name</th>
					<th field="quantity" width=60 align="right">Quantity</th>
					<th field="price" width=60 align="right">Price</th>
				</tr>
			</thead>
		</table>
		</div>
		<p class="total">Total: $0</p>
		<h2>Drop here to add to cart</h2>
	</div>
        </form>
    </div>
    
    <?php } else { ?>
    <div class="text-center">No Data Available</div>
    <?php } ?>
    </section>    
  </div>
</div>
</section>
</section>

<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>

 <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/main.css">
<script>
		var data = {"total":0,"rows":[]};
		var totalCost = 0;
		
		$(function(){
			$('#cartcontent').datagrid({
				singleSelect:true
			});
			$('.item').draggable({
				revert:true,
				proxy:'clone',
				onStartDrag:function(){
					$(this).draggable('options').cursor = 'not-allowed';
					$(this).draggable('proxy').css('z-index',10);
				},
				onStopDrag:function(){
					$(this).draggable('options').cursor='move';
				}
			});
			$('.cart').droppable({
				onDragEnter:function(e,source){
					$(source).draggable('options').cursor='auto';
				},
				onDragLeave:function(e,source){
					$(source).draggable('options').cursor='not-allowed';
				},
				onDrop:function(e,source){
					var name = $(source).find('p:eq(0)').html();
					var price = $(source).find('p:eq(1)').html();
					addProduct(name, parseFloat(price.split('$')[1]));
				}
			});
		});
		
		function addProduct(name,price){
			function add(){
				for(var i=0; i<data.total; i++){
					var row = data.rows[i];
					if (row.name == name){
						row.quantity += 1;
						return;
					}
				}
				data.total += 1;
				data.rows.push({
					name:name,
					quantity:1,
					price:price
				});
			}
			add();
			totalCost += price;
			$('#cartcontent').datagrid('loadData', data);
			$('div.cart .total').html('Total: $'+totalCost);
		}
	</script>
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="http://www.jeasyui.com/easyui/themes/icon.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="http://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
	<style type="text/css">
.products{
			list-style:none;
			margin-right:300px;
			padding:0px;
			height:100%;
		}
		.products li{
			display:inline;
			float:left;
			margin:10px;
		}
		.item{
			display:block;
			text-decoration:none;
		}
		.item img{
			border:1px solid #333;
		}
		.item p{
			margin:0;
			font-weight:bold;
			text-align:center;
			color:#c3c3c3;
		}
		.cart{
			position:fixed;
			right:0;
			top:0;
			width:300px;
			height:100%;
			background:#ccc;
			padding:0px 10px;
		}
		h1{
			text-align:center;
			color:#555;
		}
		h2{
			position:absolute;
			font-size:16px;
			left:10px;
			bottom:20px;
			color:#555;
		}
		.total{
			margin:0;
			text-align:right;
			padding-right:20px;
		}
		
	</style>	
    
    
   

		
	

