<?php
get_header(); ?>

<?php

$categories = get_categories([
    'taxonomy' => 'tax_categories'
]);

$postsItems = [];

foreach ($categories as $category) {
    $myPosts = get_posts([
        'post_type' => 'oferty',
        'tax_query' => [
            [
                'taxonomy' => 'tax_categories',
                'field'    => 'term_id',
                'terms'    => $category->term_id
            ]
        ]
    ]);
    foreach ($myPosts as $myPost) {
        $myPost->term_id = $category->term_id;
        if (count(array_filter($postsItems, function ($existPost) use ($myPost) {
                return $existPost->ID === $myPost->ID;
            })) === 0) {
            if (count(array_filter($postsItems, function ($existPost) use ($myPost) {
                    return $existPost->term_id === $myPost->term_id;
                })) === 0) {

                $myPost->image = get_field('acf__offer--cat--image', get_term_by('id', $category->term_id, 'tax_categories'));
                $myPost->video = get_field('acf__offer--cat--video', get_term_by('id', $category->term_id, 'tax_categories'));

                $postsItems[] = $myPost;
            }
        }
    }
}

shuffle($postsItems);
$postsItems = array_slice($postsItems, 0, 3);

?>


<?php get_template_part('template-parts/front-page/section-hero'); ?>
<?php get_template_part('template-parts/front-page/section-job-offers'); ?>
<?php get_template_part('template-parts/front-page/section-slider-phone'); ?>
<?php get_template_part('template-parts/front-page/section-legal-job'); ?>
<?php get_template_part('template-parts/front-page/section-why'); ?>
<?php if (isset($postsItems[0])) get_template_part('template-parts/front-page/section-tablet', null, ['tabletQuery' => $postsItems[0]]); ?>
<?php get_template_part('template-parts/front-page/section-costs'); ?>
<?php if (isset($postsItems[1])) get_template_part('template-parts/front-page/section-savings', null, ['savingsQuery' => $postsItems[1]]); ?>
<?php if (isset($postsItems[2])) get_template_part('template-parts/front-page/section-image', null, ['imageQuery' => $postsItems[2]]); ?>
<?php get_template_part('template-parts/front-page/section-opinions'); ?>


<?php
get_footer();
