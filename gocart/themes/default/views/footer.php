<div class="footer">
	<div class="container">
		<ul class="list_inline inline">
        <?php foreach($this->pages as $menu_page):?>
                <li>
                <?php if(empty($menu_page->content)):?>
                    <a href="<?php echo $menu_page->url;?>" <?php if($menu_page->new_window ==1){echo 'target="_blank"';} ?>><?php echo $menu_page->menu_title;?></a>
                <?php else:?>
                    <a href="<?php echo site_url($menu_page->slug);?>"><?php echo $menu_page->menu_title;?></a>
                <?php endif;?>
                </li>								
            <?php endforeach;?>
			
		</ul>
		<span class="block"> Copyright © 2004 - <?php echo date('Y');?> Swap Moto, Inc. All Rights Reserved</span>
	</div>
</div>
	
</body>
</html>

