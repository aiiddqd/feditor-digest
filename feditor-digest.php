<?php 
/*
 * Plugin Name: @ Feditor Digest
 * Description: Digest feature for Feditor
 * Author: uptimizt
 * Version: 0.1
 */

namespace Feditor\Digest{
    
    add_action('feditor_fields', __NAMESPACE__ . '\\view', 55, 2);
    add_action('feditor_post_save_data_after', __NAMESPACE__ . '\\save', 11, 2);
    add_filter('the_content', __NAMESPACE__ . '\\view_front', 11);

    function view_front($content){

        if(!is_single()){
            return $content;
        }
        $post = get_post();
        $url = get_post_meta($post->ID, 'feditor_digest_url', true);
        $url = wp_http_validate_url($url);
        if(empty($url)){
            return $content;
        }

        ob_start();
        ?>
        <div><a href="<?= $url ?>", target="_blank">More...</a></div>
        <?php 
        // $content .= sprintf('', $url);
        return $content . ob_get_clean();
    }
    function view($post_id, $args)
    {
        if (empty($post_id)) {
            $url = '';
        } else {
            $url = get_post_meta($post_id, 'feditor_digest_url', true);
        }

        ?>

        
        <div class="feditor-digest wp-block-group">
        <?php 
            printf('<input type="url" name="feditor_digest_url" placeholder="URL..." class="form-control" value="%s" />', $url);
        ?>
        </div>
        <?php
    }

    function save($post_id, $data)
    {

        if(empty($data['feditor_digest_url'])){
            return;
        }

        $url = wp_http_validate_url($data['feditor_digest_url']);
        if(empty($url)){
            return;
        }

        update_post_meta($post_id, 'feditor_digest_url', $url);

    }

}
