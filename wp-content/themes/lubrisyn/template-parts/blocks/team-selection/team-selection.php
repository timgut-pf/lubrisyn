<?php
$section_title = get_field('section_title');
$team_members  = get_field('selected_team'); // ACF Post Object field
?>

<section class="team-block">
    <div class="lubrisyn-container">
        
        <?php if ($section_title) : ?>
            <h2 class="team-section-header font-heading"><?php echo esc_html($section_title); ?></h2>
        <?php endif; ?>

        <div class="team-list">
            <?php if ($team_members) : ?>
                <?php foreach ($team_members as $member) : 
                    $post_id = $member->ID;
                    $job_title = get_field('member_title', $post_id);
                    $bio       = get_field('member_description', $post_id);
                    $photo     = get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'member-img']);
                ?>
                    <div class="member-row">
                        <div class="member-image-col">
                            <?php echo $photo; ?>
                        </div>
                        
                        <div class="member-content-col">
                            <h3 class="member-name"><?php echo get_the_title($post_id); ?></h3>
                            
                            <?php if ($job_title) : ?>
                                <p class="member-job-title"><?php echo esc_html($job_title); ?></p>
                            <?php endif; ?>

                            <?php if ($bio) : ?>
                                <div class="member-bio font-body">
                                    <?php echo wp_kses_post($bio); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>