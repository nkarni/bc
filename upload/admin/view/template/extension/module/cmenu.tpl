<?php echo $header; ?>
<link href="view/javascript/nestable/nestable.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/nestable/jquery.nestable.js"></script>

<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cmenu" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?>  The Menu:</h3>

		  <div class="group-control" style="padding: 12px 24px;">
			  <select id="select_menu_change" class="form-control pull-left" style="max-width: 200px; margin-right:20px">
				  <?php foreach($menus as $key => $value){ ?>
				  		<option <?php if(isset($_REQUEST['menu_name']) && $_REQUEST['menu_name'] == $value){ echo ' selected ' ;}?> value="<?php echo $value ;?>"><?php echo $key ;?></option>
				  <?php } ?>
			  </select>
			  <button id="btn-menu_change" class="btn btn-primary">Load Menu</button>
		  </div>

      </div>
		<?php if(isset($_REQUEST['menu_name'])){ ?>
      <div class="panel-body">
		<div class="row">
			<!-- left -->
			<div class="col-sm-12" id="alert"></div>
			<form id="form-item" enctype="multipart/form-data" method="post">
			<div class="col-sm-4">
				<div class="group-control">
				<select name="type" id="menutype" class="form-control">
					<option value="custom" selected>Custom</option>
					<option value="category">Category</option>
					<option value="product">Product</option>
					<option value="information">Information</option>
					<option value="brand">Brand</option>
				</select>
				</div>
				
				<div class="group-control">
					<input id="vname" type="text" name="vname" placeholder="autocomplete" class="form-control" />
					<input type="hidden" name="value" value=""/>
				</div>
				
				<?php foreach ($languages as $language) { ?>
				 <div>
					 <label style="margin-top: 12px;">Title</label>[<?php echo $language['name']; ?>]<br />
				 <input name="title[<?php echo $language['language_id']; ?>]" class="form-control" />
                 </div>
				<?php } ?>
				
				<div class="group-control" id="url">
					<label style="margin-top: 12px;">url</label><br />
					<input type="text" name="url" class="form-control" />
				</div>
				
				
				<div class="col-sm-4 right" style="margin-top:10px;"><a class="form-control btn-primary" id="btn-addItem">Add Item</a></div>
				<input type="hidden" name="menu_name" value="<?php echo $_REQUEST['menu_name'] ; ?>"/>
				
			</div>
			</form>
			
			<!-- right -->
            <div class="col-sm-6">
				<div class="right" style="margin-top:10px; width:150px;"><a class="form-control btn-primary" id="btn-save">Save Menu</a></div>
				<div class="row"></div>
				<div id="cmenu" class="dd">
				</div>
			</div>
		</div>
		
      </div>
		<?php } // end if(isset($_REQUEST['menu_name']) ?>
    </div>
  </div>
</div>

<script type="text/javascript"><!--

$('.dd').nestable('init');

$('#vname').hide();

var url = 'index.php?route=extension/module/cmenu/categoryAutocomplete&token=<?php echo $token; ?>';

$('#menutype').on('change', function(){
	var val = this.value;
	
	if(val == 'custom'){
		$('#vname').hide();
		$('#url').show();
	}else if(val == 'category'){
		$('#vname').show();
		$('#vname').val('');
		$('#url').hide();
		url = 'index.php?route=extension/module/cmenu/categoryAutocomplete&token=<?php echo $token; ?>';
	}else if(val == 'product'){
		$('#vname').show();
		$('#vname').val('');
		$('#url').hide();
		url = 'index.php?route=extension/module/cmenu/productAutocomplete&token=<?php echo $token; ?>';
	}else if(val == 'information'){
		$('#vname').show();
		$('#vname').val('');
		$('#url').hide();
		url = 'index.php?route=extension/module/cmenu/informationAutocomplete&token=<?php echo $token; ?>';
	}else if(val == 'brand'){
		$('#vname').show();
		$('#vname').val('');
		$('#url').hide();
		url = 'index.php?route=extension/module/cmenu/manufacturerAutocomplete&token=<?php echo $token; ?>';
	}
});
// Category
$('input[name=\'vname\']').autocomplete({
	'source': function(request, response) {
		//alert('asd');
		$.ajax({
			url: url + '&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'vname\']').val(item['label']);
		$('input[name=\'value\']').val(item['value']);
	}
});

//--></script> 

<script type="text/javascript"><!--
//addItem
$('#cmenu').load('index.php?route=extension/module/cmenu/menuHtml&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>');
$('#btn-addItem').on('click', function(){
	//alert('ada');
	$.ajax({
		url: 'index.php?route=extension/module/cmenu/addItem&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: $('#form-item').serialize(),
		beforeSend: function(){
			$('#btn-addItem').button('loading');
		},
		success: function(json) {
			$('#cmenu').load('index.php?route=extension/module/cmenu/menuHtml&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>');
		},
		error: function(json){
			alert(json['error']);
		},
		complete: function(){
			$('#btn-addItem').button('reset');
		}
	});
});

//save menutype
$('#btn-save').on('click', function(){
	
	$.ajax({
		url: 'index.php?route=extension/module/cmenu/updateMenu&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: {menu : $('.dd').nestable('serialize')},
		beforeSend: function(){
			$('#btn-save').button('loading');
			$('#alert').html('');
		},
		success: function(json) {
			//alert(json['success']);
			$('#alert').html('<div class="alert alert-success">Success</div>');
		},
		error: function(json){
			alert(json['error']);
		},
		complete: function(){
			$('#btn-save').button('reset');
		}
	});
	
});


    //chaneg menu
    $('#btn-menu_change').on('click', function(){

        /*

        $.ajax({
            url: 'index.php?route=extension/module/cmenu/updateMenu&token=<?php echo $token; ?>',
            type: 'post',
            dataType: 'json',
            data: {menu : $('.dd').nestable('serialize')},
            beforeSend: function(){
                $('#btn-save').button('loading');
                $('#alert').html('');
            },
            success: function(json) {
                //alert(json['success']);
                $('#alert').html('<div class="alert alert-success">Success</div>');
            },
            error: function(json){
                alert(json['error']);
            },
            complete: function(){
                $('#btn-save').button('reset');
            }
        });
        */

		var url = 'index.php?route=extension/module/cmenu&menu_name=' + $('#select_menu_change').val() + '&token=<?php echo $token; ?>';
        window.location = url;

    });
//--></script>
<script type="text/javascript"><!--
function deleteItem(id)
{
  var r=confirm("Do you want to delete this item?")
  if (r==true)
  {
	$.ajax({
		url: 'index.php?route=extension/module/cmenu/deleteItem&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>&id=' + id,
		type: 'post',
		dataType: 'json',
		success: function(json) {
			//alert(json['success']);
			$('#cmenu').load('index.php?route=extension/module/cmenu/menuHtml&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>');
		},
		error: function(json){
			alert(json['error']);
		}
	});
  }else{
  	return true;
  }
}

function updateItem(id){
	//alert('fas');
	$.ajax({
		url: 'index.php?route=extension/module/cmenu/updateItem&token=<?php echo $token; ?>&id=' + id,
		type: 'post',
		dataType: 'json',
		data: $('#form_' + id).serialize(),
		success: function(json) {
			//alert(json['message']);
			$('#cmenu').load('index.php?route=extension/module/cmenu/menuHtml&menu_name=<?php echo $_REQUEST['menu_name'] ; ?>&token=<?php echo $token; ?>');
		},
		error: function(json){
			alert(json['message']);
		}
	});
}
//--></script> 
<?php echo $footer; ?>