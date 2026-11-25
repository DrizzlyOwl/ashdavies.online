<?php

namespace Ash;

/**
 * Search form template
 */

$unique_id = wp_unique_id('search-form-');
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr($unique_id); ?>">
        <?php esc_html_e('Search for:', 'ashdavies'); ?>
    </label>
    <input
        type="search"
        id="<?php echo esc_attr($unique_id); ?>"
        class="search-form__input"
        placeholder="<?php esc_attr_e('Search&hellip;', 'ashdavies'); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        name="s"
    >
    <button type="submit" class="search-form__submit">
        <?php esc_html_e('Search', 'ashdavies'); ?>
    </button>
</form>
