<?php global $site_url,$wcode; ?>
<div class="search">
    <form method="get" id="searchform2" action="<?php echo $site_url; ?>">
        <fieldset>
            <input name="s" type="text" PLACEHOLDER="<?php echo SEARCH;?>" value="<?php echo SEARCH;?>" />
			 <?php if($wcode == 1){ ?>
			 <input name="lang" id="lang" type="hidden" class="s" PLACEHOLDER="<?php echo $wcode;?>" value="1" /> 
			 <?php } ?>
            <button type="submit"></button>
        </fieldset>
    </form>
</div>
