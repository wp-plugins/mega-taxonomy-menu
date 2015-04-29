<form role="search" method="get" class="search-form form-inline" action="<?php echo home_url('/'); ?>">
  <div class="input-group">
    <input type="search" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" placeholder="<?php _e('Search', 'roots'); ?>">    
    <button type="submit" class="search-submit btn btn-default"><?php _e('SEARCH', 'roots'); ?></button>
  </div>
</form>
