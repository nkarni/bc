

CMENU EXTENSION

The extension is loaded with many modification to make it multi-menu compatible, don't even think about upgrading it from the vendor...

To add a new menu:
1. add it in admin /admin/controller/extension/module/cmenu.php near line 81: $data['menus'] = array('Very Top' => 'very_top', 'Footer' => 'footer');

2. add a dunction in  /catalog/controller/common/cmenu.php like:
public function getFooterMenu(){
        return $this->getOnemenu('footer');
    }

3. add a call in relevant controller like: $data['footer_menu'] = $this->load->controller('common/cmenu/getFooterMenu');

4. call it inside the relevant template like:
<ul class="nav navbar-nav"><?php   echo $footer_menu ; ?> </ul>