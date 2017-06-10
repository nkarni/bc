<style type="text/css">
    footer ul.list-unstyled a{
        color : #FFFFFF !important;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
<footer>
  <div class="container no-print">
    <div class="row">
      <div class="col-sm-3">
          <h5>Our Products</h5>
          <ul class="list-unstyled">
              <?php echo $footer_menu ; ?>
          </ul>
      </div>
      <div class="col-sm-3">
          <h5>Connect</h5>
          <ul class="social_icons">
              <li><a target="_blank" href="https://www.facebook.com/BCS.com.au"><i class="fa fa-facebook"></i></a></li>
              <li><a target="_blank" href="https://twitter.com/Backcareseating"><i class="fa fa-twitter"></i></a></li>
              <li><a target="_blank" href="http://backcareandseating.tumblr.com/"><i class="fa fa-tumblr"></i></a></li>
          </ul>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12 text-right">
        <p>© <?php echo date("Y"); ?> BACKCARE &amp; SEATING. ALL RIGHTS RESERVED. <a href="/disclaimer/">DISCLAIMER</a></p>
      </div>
    </div>

  </div>
</footer>
<script>
    $(document).ready(function(){
        var loggedIn = '<?php echo $loggedIn ; ?>';
        if(loggedIn){
            $("a[href='/index.php?route=account/login']").text('Account');
        }

        $("a[href='/index.php?route=account/wishlists']").click(function () {
            if (!loggedIn) {
                alert('Please register or login to access your wishlists');
            }
        });

        var contactLink = $("a[href='<?php echo $contact_url; ?>']");
        if (contactLink) {
            contactLink.attr('href', '/index.php?route=information/contact')
        }

        <?php if($compare_number > 0){ ?>
            compare.updateCount()
        <?php } ?>

    });
</script>

</body></html>

