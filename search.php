<?php get_header(); ?>
<div class="container in-column ph--40 pv--40">

    <div class="search-input-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M19.0008 19.0008L13.8038 13.8038M13.8038 13.8038C15.2104 12.3972 16.0006 10.4895 16.0006 8.50028C16.0006 6.51108 15.2104 4.60336 13.8038 3.19678C12.3972 1.79021 10.4895 1 8.50028 1C6.51108 1 4.60336 1.79021 3.19678 3.19678C1.79021 4.60336 1 6.51108 1 8.50028C1 10.4895 1.79021 12.3972 3.19678 13.8038C4.60336 15.2104 6.51108 16.0006 8.50028 16.0006C10.4895 16.0006 12.3972 15.2104 13.8038 13.8038Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div class="search-wrapper">
            <input type="text" id="searchInput" placeholder="<?php _e('Type to search in any field.', 'mongabay'); ?>" autocomplete="off" />
            <div class="search-actions"></div>
        </div>
        <div id="articles-suggestions" class="dropdown hide"></div>
    </div>
    <div class="tax-wrapper">
        <div class="tax-item-wrapper topics">
            <input type="text" id="searchTopic" placeholder="<?php _e('Topic', 'mongabay'); ?>" />
            <div class="tax-search-actions topic"></div>
            <div id="topics-suggestions" class="dropdown hide"></div>
            <div id="topics-results" class="dropdown hide"></div>
        </div>
        <div class="tax-item-wrapper locations">
            <input type="text" id="searchLocation" placeholder="<?php _e('Location', 'mongabay'); ?>" onchange="" />
            <div class="tax-search-actions location"></div>
            <div id="locations-suggestions" class="dropdown hide"></div>
            <div id="locations-results" class="dropdown hide"></div>
        </div>
        <div class="tax-item-wrapper format">
            <input type="text" id="searchFormat" placeholder="<?php _e('Format', 'mongabay'); ?>" onchange="" />
            <div class="tax-search-actions format"></div>
            <div id="formats-suggestions" class="dropdown hide"></div>
            <div id="formats-results" class="dropdown hide"></div>
        </div>
    </div>
    <div class="container pv--8">
        <label for="featured">
            <input id="featured" type="checkbox" name="featured" value="false" aria-label="Featured">
            <?php _e('Featured only', 'mongabay'); ?>
        </label>
    </div>
    <div id="default" class="">
        <h1><?php _e('Try out our suggestions', 'mongabay'); ?></h1>
        <div class="suggestions">
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=<?php _e('forests', 'mongabay'); ?>"><?php _e('Forest articles', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=topics=<?php _e('wildlife', 'mongabay'); ?>&formats=videos"><?php _e('Wildlife Videos', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=&topics=<?php _e('conservation', 'mongabay'); ?>&formats=podcasts"><?php _e('Conservation Podcasts', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=a&topics=<?php _e('oceans', 'mongabay'); ?>&formats=specials"><?php _e('Ocean Specials', 'mongabay'); ?></a></div>
        </div>
    </div>
    <div id="no-results" class="hide">
        <h1><?php _e('No results found', 'mongabay'); ?></h1>
        <div class="suggestions">
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=<?php _e('forests', 'mongabay'); ?>"><?php _e('Forest articles', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=&topics=<?php _e('wildlife', 'mongabay'); ?>&formats=videos"><?php _e('Wildlife Videos', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=&topics=<?php _e('conservation', 'mongabay'); ?>&formats=podcasts"><?php _e('Conservation Podcasts', 'mongabay'); ?></a></div>
            <div class="suggestion-item"><a href="<?php echo home_url(); ?>/?s=&topics=<?php _e('oceans', 'mongabay'); ?>&formats=specials"><?php _e('Ocean Specials', 'mongabay'); ?></a></div>
        </div>
    </div>
    <div class="results-wrapper">
        <div id="results" class="hide"></div>
        <div class="results-footer"></div>
    </div>
</div>

<?php tools_slider(); ?>
<?php inspiration_banner(); ?>
</div>
<?php get_footer(); ?>