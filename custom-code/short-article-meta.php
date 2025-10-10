<?php

// Add meta box for Short Article fields
function short_article_meta_box()
{
    add_meta_box(
        'short_article_details',
        __('Short Article Details', 'mongabay'),
        'short_article_meta_box_callback',
        'short-article',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'short_article_meta_box');

// Meta box callback function
function short_article_meta_box_callback($post)
{
    // Add nonce for security
    wp_nonce_field('short_article_meta_box', 'short_article_meta_box_nonce');

    // Get existing values
    $article_type = get_post_meta($post->ID, 'article_type', true);
    $article_link = get_post_meta($post->ID, 'article_link', true);
    $video_link = get_post_meta($post->ID, 'video_link', true);
    $audio_link = get_post_meta($post->ID, 'audio_link', true);

    // Default to 'content' if not set
    if (empty($article_type)) {
        $article_type = 'content';
    }
?>
    <style>
        .short-article-meta-field {
            margin-bottom: 20px;
        }
        .short-article-meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .short-article-meta-field input[type="text"],
        .short-article-meta-field input[type="url"],
        .short-article-meta-field select {
            width: 100%;
            max-width: 600px;
            padding: 8px;
        }
        .short-article-meta-field .description {
            font-style: italic;
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>

    <div class="short-article-meta-field">
        <label for="article_type"><?php _e('Article Type', 'mongabay'); ?></label>
        <select name="article_type" id="article_type">
            <option value="content" <?php selected($article_type, 'content'); ?>><?php _e('Content', 'mongabay'); ?></option>
            <option value="video" <?php selected($article_type, 'video'); ?>><?php _e('Video', 'mongabay'); ?></option>
            <option value="audio" <?php selected($article_type, 'audio'); ?>><?php _e('Audio', 'mongabay'); ?></option>
        </select>
        <p class="description"><?php _e('Select the type of short article (content, video, or audio)', 'mongabay'); ?></p>
    </div>

    <div class="short-article-meta-field" id="article_link_field">
        <label for="article_link"><?php _e('Content URL', 'mongabay'); ?></label>
        <input type="url" name="article_link" id="article_link" value="<?php echo esc_attr($article_link); ?>" placeholder="https://example.com/article">
        <p class="description"><?php _e('Enter the URL for the content article (shown when Article Type is "Content")', 'mongabay'); ?></p>
    </div>

    <div class="short-article-meta-field" id="video_link_field">
        <label for="video_link"><?php _e('Video URL', 'mongabay'); ?></label>
        <input type="url" name="video_link" id="video_link" value="<?php echo esc_attr($video_link); ?>" placeholder="https://example.com/video.mp4">
        <p class="description"><?php _e('Enter the URL for the video (shown when Article Type is "Video")', 'mongabay'); ?></p>
    </div>

    <div class="short-article-meta-field" id="audio_link_field">
        <label for="audio_link"><?php _e('Audio URL', 'mongabay'); ?></label>
        <input type="url" name="audio_link" id="audio_link" value="<?php echo esc_attr($audio_link); ?>" placeholder="https://example.com/audio.mp3">
        <p class="description"><?php _e('Enter the URL for the audio file (shown when Article Type is "Audio")', 'mongabay'); ?></p>
    </div>

    <script>
        // Show/hide link fields based on article type
        (function($) {
            function toggleLinkFields() {
                var articleType = $('#article_type').val();
                
                $('#article_link_field, #video_link_field, #audio_link_field').hide();
                
                if (articleType === 'content') {
                    $('#article_link_field').show();
                } else if (articleType === 'video') {
                    $('#video_link_field').show();
                } else if (articleType === 'audio') {
                    $('#audio_link_field').show();
                }
            }
            
            $(document).ready(function() {
                toggleLinkFields();
                $('#article_type').on('change', toggleLinkFields);
            });
        })(jQuery);
    </script>
<?php
}

// Save meta box data
function save_short_article_meta_box($post_id)
{
    // Check nonce
    if (!isset($_POST['short_article_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['short_article_meta_box_nonce'], 'short_article_meta_box')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if this is a short-article post type
    if (get_post_type($post_id) !== 'short-article') {
        return;
    }

    // Save article_type
    if (isset($_POST['article_type'])) {
        $article_type = sanitize_text_field($_POST['article_type']);
        if (in_array($article_type, array('content', 'video', 'audio'))) {
            update_post_meta($post_id, 'article_type', $article_type);
        }
    }

    // Save article_link
    if (isset($_POST['article_link'])) {
        $article_link = esc_url_raw($_POST['article_link']);
        update_post_meta($post_id, 'article_link', $article_link);
    }

    // Save video_link
    if (isset($_POST['video_link'])) {
        $video_link = esc_url_raw($_POST['video_link']);
        update_post_meta($post_id, 'video_link', $video_link);
    }

    // Save audio_link
    if (isset($_POST['audio_link'])) {
        $audio_link = esc_url_raw($_POST['audio_link']);
        update_post_meta($post_id, 'audio_link', $audio_link);
    }
}
add_action('save_post', 'save_short_article_meta_box');

