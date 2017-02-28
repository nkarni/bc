<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <h5>Created On :<?php echo $created_on; ?></h5>        
      <?php if(!$is_owner){ ?>
      <h5>Created By :<?php echo $created_by; ?></h5>
      <?php } ?>      
        
          <!-- Content for Popover #1 -->
          <div class="hidden wishlist-container-confirm" id="a1" style="position:absolute;">
              <div class="popover-heading">
                  <span class="close">close &times;</span>
              </div>

              <div class="popover-body-text">
                  <div class="popover-body1">
                 <span>Are you sure to purchase for <?php echo $created_by; ?>?</span>
				  </div>
              </div>
              <div class="input-group"> <input type="text" class="form-control" name="wishlist_name" id="wishlist_name"  ><span class="input-group-btn"><button type="button" product="" class="addlist btn btn-default" >ADD</button></span></div>
          </div>
          <!-- Popover container ends here -->


        <?php if($visiblity) { ?>
        <div syle="width:100%;">

            <div class="goodshare-color row" style="float:right;padding:10px;">
                <?php if($wishlists_copy_url_status) { ?>
                    <span class="show-copy-link btn btn-primary btn-small"  onclick="js:CopyToClipboard('<?php echo $mysharelink; ?>');" style="padding:none !important;line-height:0.5 !important;">Copy URL</span>
                <?php } ?>
                    <a class="show-copy-link btn btn-primary btn-small" href="mailto:?body=<?php echo str_replace("&amp;","%26", $mysharelink); ?>" style="padding:none !important;line-height:0.5 !important;">Email URL</a>
            </div>

        </div>

        <?php } ?>


        <?php if ($wishlistitems) { ?>

      <table class="table table-hover my-wishlist-table">
		  <!--
        <thead>			
          <tr>
            <td class="text-left"></td>

            <td class="text-left"><?php echo $column_name; ?></td>

            <td class="text-right"><?php echo $column_status; ?></td>

            <td class="text-right"><?php echo $column_action; ?></td>            
          </tr>          
        </thead>
        -->
        <tbody>
          <?php foreach ($wishlistitems as $wishlistitem) { ?>
          <tr>
              <td class="text-center"><?php if ($wishlistitem['thumb']) { ?>

                  <a href="<?php echo $wishlistitem['href']; ?>"><img src="<?php echo $wishlistitem['thumb']; ?>" alt="<?php echo $wishlistitem['product_name']; ?>" title="<?php echo $wishlistitem['product_name']; ?>" /></a>
                  <?php } ?></td>
              <td class="text-left"><a href="<?php echo $wishlistitem['href']; ?>"><?php echo $wishlistitem['product_name']; ?></a></td>

              <td class="text-right">
				         <?php if ($wishlistitem['price']) { ?>
                            <p class="price">
                                <?php if (!$wishlistitem['special']) { ?>
                                <?php echo $wishlistitem['price']; ?>
                                <?php } else { ?>
                                <span class="price-new"><?php echo $wishlistitem['special']; ?></span>
                                <!--<span class="price-old"><?php echo $wishlistitem['price']; ?></span>-->
                                <?php } ?>
                            </p>
                            <?php } ?>
              </td>

              <td class="text-right">
                  <?php if($wishlistitem['price_num'] > 0) { ?>
                  <?php if(!$islogged) { ?>
                  <a class="btn btn-primary bt-text" href="js:void();" onclick="cart.add('<?php echo $wishlistitem['product_id']; ?>', '<?php echo $wishlistitem['minimum']; ?>');"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add to Cart</a>
                  <?php } else { ?>
                      <?php if($wishlistitem['purchase_count'] != 0){ ?>
                            <button type="button" class="btn btn-success bt-text" data-toggle="tooltip" title="<?php echo $wishlistitem['purchased_by']; ?>"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Already Bought</button>
                      <?php } elseif($wishlistitem['is_bought'] != $wishlistitem['customer'] && $wishlistitem['is_bought'] !=0) { ?>						 
                            <button type="button" class="btn btn-warning bt-text" data-toggle="tooltip" title="<?php echo $wishlistitem['purchased_by']; ?>"><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Already Bought</button>
                      <?php } else { ?>  
						  <?php if($is_owner){ ?>
                            <button class="btn btn-primary bt-text"  title="" onclick="cart.add('<?php echo $wishlistitem['product_id']; ?>', '<?php echo $wishlistitem['minimum']; ?>');"  tabindex="0"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add to Cart&nbsp;&nbsp;&nbsp;</button>
                          <?php } else { ?>  
                            <button class="wishlist-add-form btn btn-primary bt-text" href="#a1" rel="popover" title="" product="<?php echo $wishlistitem['product_id']; ?>" minimum="<?php echo $wishlistitem['minimum']; ?>"  tabindex="0"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add to Cart&nbsp;&nbsp;&nbsp;</button>
                          <?php } ?>
                      <?php } ?>
                  <?php } ?>
                  <?php } ?>
              </td>
            <?php if($islogged) { ?>
            <td class="text-right">
              <a href="<?php echo $wishlistitem['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-sm btn-default"><i class="fa fa-times-circle" style="color:#CE2727;"></i></a>
            </td>
            <?php } ?>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
    $(document).ready(function(){

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-36251023-1']);
        _gaq.push(['_setDomainName', 'jqueryscript.net']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        $(".show-copy-link").click(function(){
            $(".share-copy-link").css("display","block");
        });


    });
    function popitup(url,share) {
        shareurl = '';
        if(url != ''){

            if(share == "facebook"){
                shareurl = "https://facebook.com/sharer.php?u="+url;
            }
            else if(share == "google+"){
                shareurl = "https://plus.google.com/share?url="+url;
            }
            else if(share == "twitter"){
                shareurl = "https://twitter.com/intent/tweet?url="+url;
            }

        //    alert(shareurl);

            newwindow=window.open(url,'name','height=300,width=350');
            if (window.focus) {newwindow.focus()}
                return false;
        }
    }

    function CopyToClipboard(text) {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
    }
    
    
         
    $('.wishlist-add-form').click(function () {
		
        $currentproduct = $(this).attr('product');  
        $minimum = $(this).attr('minimum');  
		
		$buttontext = '<div class="buttons">';
		
		$buttontext += '<span id="addtoorder" product="'+$currentproduct+'" minimum="'+$minimum+'" class="btn btn-success">Yes</span>';
		
		$buttontext += '&nbsp;&nbsp;&nbsp;&nbsp;<span class="wishlist-close btn btn-danger">No</span>';   
		
		$buttontext += '</div>';   
		
        
       // $addbuttonhtml = 'test';

        $(this).popover({
            html: true,
            trigger: 'manual',
            placement: 'top',

            content: function () {
                $buttons = $('.popover-body1').html();
               // $activeproductrow = 'test';
                return $buttons+$buttontext;
                
            }
            
        }).popover('toggle');

    });
    
    $(document).on('click', '#addtoorder', function() {
				
       // var wishlist_id=1;
		//var product_id=42;
		
        $product_id = $(this).attr('product');  
        $minimum = $(this).attr('minimum');  
        $wishlist_id = <?php echo $_GET['wishlist_id']; ?>;  
        
        if($.isNumeric($currentproduct) && $.isNumeric($minimum)){
			//alert(1);
		        
			$.ajax({
				url: 'index.php?route=account/wishlists/editWishlistitem',
				type: 'post',
				data: 'wishlist_id=' + $wishlist_id + '&product_id=' + $product_id,
				dataType: 'json',
				beforeSend: function() {
					$('#cart > button').button('loading');
				},
				complete: function() {
					$('#cart > button').button('reset');
				},			
				success: function(json) {
					
					}
				});
               $('.popover').popover('hide');
                
		       cart.add($currentproduct, $minimum);
		
		}		
		$('.popover').popover('hide');
		//alert(2);
		});
		
		
   $(document).on('click', '.wishlist-close', function() { 
		$('.popover').popover('hide');
	});

</script>
