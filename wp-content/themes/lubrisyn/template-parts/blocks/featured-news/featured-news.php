<?php
$featured_posts = get_field('featured_articles');
?>

<section class="featured-news-block">
    <div class="lubrisyn-container">
        <h2 class="section-title font-heading">Featured Articles</h2>
        
        <?php if ($featured_posts): ?>
            <div class="news-grid">
                <?php 
                $count = 0;
                foreach ($featured_posts as $post_obj): // Renamed to $post_obj for clarity
                    $count++;
                    
                    // 1. Get ID for images/links
                    $p_id = $post_obj->ID; 
                    
                    // 2. Access Title & Permalink directly from the object
                    $title = $post_obj->post_title;
                    $link  = get_permalink($p_id);
                    
                    // 3. Get Featured Image URL
                    $bg_image = get_the_post_thumbnail_url($p_id, 'large');
                    
                    // 4. Get Category Name
                    $categories = get_the_category($p_id);
                    $category_name = !empty($categories) ? $categories[0]->name : 'Blog';
                ?>
                    <a href="<?php echo esc_url($link); ?>" 
                       class="news-card card-<?php echo $count; ?>" 
                       style="background-position: top; background-image: url('<?php echo esc_url($bg_image); ?>');">
                        
                        <div class="card-overlay">
                            <div class="card-content">
                                <span class="card-category font-heading"><?php echo esc_html($category_name); ?></span>
                                <h3 class="card-title"><?php echo esc_html($title); ?></h3>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>