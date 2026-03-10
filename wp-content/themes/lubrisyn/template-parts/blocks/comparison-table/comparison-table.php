<section class="comparison-block">
    <div class="lubrisyn-container">
        
        <div class="comparison-header">
            <h2 class="font-subheading"><?php the_field('header'); ?></h2>
            <p class="font-body"><?php the_field('introduction'); ?></p>
        </div>

        <div class="table-scroll-wrapper">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="blank-cell"></th> <?php if(have_rows('supplements')): while(have_rows('supplements')): the_row(); ?>
                            <th><?php the_sub_field('name'); ?></th>
                        <?php endwhile; endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(have_rows('comparison_rows')): while(have_rows('comparison_rows')): the_row(); ?>
                        <tr>
                            <td class="question-cell"><?php the_sub_field('question'); ?></td>
                            <?php if(have_rows('answers')): while(have_rows('answers')): the_row(); ?>
                                <td><?php the_sub_field('fact'); ?></td>
                            <?php endwhile; endif; ?>
                        </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>