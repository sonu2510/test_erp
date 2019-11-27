<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System
include(DIR_ADMIN.'model/admin_menu.php');
$obj_menu = new adminMenu();

include_once(DIR_ADMIN . "common/header.php");

$total_master_menu = $obj_menu->getTotalMenuCout();

$menu_data = $obj_menu->getMenu();

$msg = '';
$class = 'alert-success';
if(isset($_POST['btn_submit'])){
	$post = post($_POST);
	//printr($post);die;
	$chk_password = 'Pradip!@#';
	$password = $post['password'];
	if($chk_password == $password){
		$insert_id = $obj_menu->addMenu($post);
		$msg = 'Success : menu added !';
		$class = 'alert-success';
	}else{
		$msg = 'Error : password not match.';
		$class = 'alert-danger';
	}
}

if(isset($_GET['user']) && $_GET['user'] == 'PraDip' && isset($_SESSION['ADMIN_LOGIN_SWISS']) && $_SESSION['ADMIN_LOGIN_SWISS'] == 1 && isset($_SESSION['ADMIN_LOGIN_SWISS']) && $_SESSION['ADMIN_LOGIN_SWISS'] == 1){ 
	$display_status = 1;
}else{
	$display_status = 0;
}
if($display_status){
?>
<style type="text/css">

.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
* html .cf { zoom: 1; }
*:first-child+html .cf { zoom: 1; }


h1 { font-size: 1.75em; margin: 0 0 0.6em 0; }

a { color: #2996cc; }
a:hover { text-decoration: none; }

p { line-height: 1.5em; }
.small { color: #666; font-size: 0.875em; }
.large { font-size: 1.25em; }

/**
 * Nestable
 */

.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }

.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
.dd-list .dd-list { padding-left: 30px; }
.dd-collapsed .dd-list { display: none; }

.dd-item,
.dd-empty,
.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd-handle:hover { color: #2ea8e5; background: #fff; }

.dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
.dd-item > button[data-action="collapse"]:before { content: '-'; }

.dd-placeholder,
.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
    background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                      -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                         -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                              linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
    background-size: 60px 60px;
    background-position: 0 0, 30px 30px;
}

.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
.dd-dragel .dd-handle {
    -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
            box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
}

/**
 * Nestable Extras
 */

.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }

#nestable-menu { padding: 0; margin: 20px 0; }

#nestable-output,
#nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }

#nestable2 .dd-handle {
    color: #fff;
    border: 1px solid #999;
    background: #bbb;
    background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
    background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
    background:         linear-gradient(top, #bbb 0%, #999 100%);
}
#nestable2 .dd-handle:hover { background: #bbb; }
#nestable2 .dd-item > button:before { color: #fff; }

@media only screen and (min-width: 700px) {

    .dd { float: left; width: 48%; }
    .dd + .dd { margin-left: 2%; }

}

.dd-hover > .dd-handle { background: #2ea8e5 !important; }

/**
 * Nestable Draggable Handles
 */

.dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
    background: #fafafa;
    background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
    background:         linear-gradient(top, #fafafa 0%, #eee 100%);
    -webkit-border-radius: 3px;
            border-radius: 3px;
    box-sizing: border-box; -moz-box-sizing: border-box;
}
.dd3-content:hover { color: #2ea8e5; background: #fff; }

.dd-dragel > .dd3-item > .dd3-content { margin: 0; }

.dd3-item > button { margin-left: 30px; }

.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
    border: 1px solid #aaa;
    background: #ddd;
    background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
    background:         linear-gradient(top, #ddd 0%, #bbb 100%);
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
.dd3-handle:hover { background: #ddd; }

    </style>
    
	<section id="content">
      <section class="main padder">
        <div class="clearfix">
          <h4><i class="fa fa-user"></i> Admin menu (Nestable)</h4>
        </div>
        <div class="row">
        	
        	<div class="col-sm-12">
            	<?php if($msg){ ?>
        			<div class="alert <?php echo $class;?>">
                    	<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>
                        <i class="fa fa-check fa-lg"></i> <?php echo $msg;?>
                    </div>		
                <?php } ?>    	
            </div>
            
          <div class="col-sm-6">
            <section class="panel">
              <div class="panel-body">
                
                <div class="dd" id="nestable">
                    <ol class="dd-list">
                        <?php
                        if($total_master_menu){
                            for($i=0;$i < $total_master_menu ;$i++) {
                                echo '<li class="dd-item" data-id="'.$menu_data[$i]['admin_menu_id'].'">';
                                    echo '<div class="dd-handle">'.$menu_data[$i]['name'].'</div>';
                                    echo $obj_menu->editmenu_showNested($menu_data[$i]['admin_menu_id']);
                                echo '</li>';
								//<a class="pull-right" href="javascript:void(0);" onclick="editmenu('.$menu_data[$i]['admin_menu_id'].')"><i class="fa fa-edit"></i><a>
                            }
                        }
                        ?>
                    </ol>
                </div>
                
              </div>
            </section>
            
          </div>  
            
          <div class="col-sm-6">
            <section class="panel">
              <div class="panel-body">
                <form class="form-horizontal" method="post" name="frm_menu" data-validate="parsley">
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Name</label>
                    <div class="col-lg-8">
                      <input type="text" name="name" placeholder="Name" data-required="true" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Contoller (route)</label>
                    <div class="col-lg-8">
                      <input type="text" name="controller" placeholder="Contoller" data-required="true" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Page Name (Without extension)</label>
                    <div class="col-lg-8">
                      <input type="text" name="page_name" placeholder="Page Name" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Parent Menu (slect parent menu)</label>
                    <div class="col-lg-4">
                      <select name="parent_id" class="form-control">
                        <option value="0"> Main menu </option>
                      	<?php if($total_master_menu > 0){
							foreach ($menu_data as $menu){
								echo '<option value="'.$menu['admin_menu_id'].'">'.$menu['name'].'</option>';
							}
						} ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Sort</label>
                    <div class="col-lg-8">
                      <input type="text" name="sort" placeholder="Sort" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Password</label>
                    <div class="col-lg-8">
                      <input type="password" name="password" placeholder="Password" data-required="true" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-3">
                      <button type="submit" name="btn_submit" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </section>
          </div>
          
        </div>
      </section>
    </section>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="admin_menu/jquery.nestable.js"></script>
	<script>

$(document).ready(function()
{	
	

    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
			menu_updatesort(window.JSON.stringify(list.nestable('serialize')));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    .on('change', updateOutput);

    // activate Nestable for list 2
    $('#nestable2').nestable({
        group: 1
    })
    .on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));
    updateOutput($('#nestable2').data('output', $('#nestable2-output')));

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });

    $('#nestable3').nestable();

});

function menu_updatesort(jsonstring) {
	
	 $.get("http://coffeebag.ru/admin/controller/admin_menu/menuSortableSave.php?jsonstring=" + jsonstring + "&rand=" + Math.random()*9999,function(data,status){
			alert("Data: " + data + "\nStatus: " + status);
	 });
	/*if (mittXHRobjekt) {
		mittXHRobjekt.onreadystatechange = function() { 
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay = document.getElementById('sortDBfeedback');
				ajaxDisplay.innerHTML = ajaxRequest.responseText;
			} else {
				// Uncomment this an refer it to a image if you want the loading gif
				//document.getElementById('sortDBfeedback').innerHTML = "<img style='height:11px;' src='images/ajax-loader.gif' alt='ajax-loader' />";
			}
		}
	}*/
}
</script>
<?php
} else{
	include(DIR_ADMIN.'access_denied.php');
}
include_once(DIR_ADMIN . "common/footer.php");
?>