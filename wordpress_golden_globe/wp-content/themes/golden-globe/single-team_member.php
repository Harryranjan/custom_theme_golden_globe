<?php
defined('ABSPATH') || exit;

get_header();

while (have_posts()) : the_post();

    $post_id   = get_the_ID();
    $has_thumb = has_post_thumbnail();
    $thumb_url = $has_thumb ? get_the_post_thumbnail_url($post_id, 'team-square') : '';

    // ACF fields with post_meta fallback
    if (function_exists('get_field')) {
        $role       = agency_get_field('member_role',     $post_id);
        $email      = agency_get_field('member_email',    $post_id);
        $phone      = agency_get_field('member_phone',    $post_id);
        $linkedin   = agency_get_field('member_linkedin', $post_id);
        $twitter    = agency_get_field('member_twitter',  $post_id);
        $github     = agency_get_field('member_github',   $post_id);
        $skills     = agency_get_field('member_skills',   $post_id); // repeater: skill_name, skill_level (0-100)
        $speciality = agency_get_field('member_speciality', $post_id);
    } else {
        $role       = get_post_meta($post_id, 'member_role',       true);
        $email      = get_post_meta($post_id, 'member_email',      true);
        $phone      = get_post_meta($post_id, 'member_phone',      true);
        $linkedin   = get_post_meta($post_id, 'member_linkedin',   true);
        $twitter    = get_post_meta($post_id, 'member_twitter',    true);
        $github     = get_post_meta($post_id, 'member_github',     true);
        $skills     = null;
        $speciality = get_post_meta($post_id, 'member_speciality', true);
    }

    // Prev / next within team_member CPT
    $prev_member = get_previous_post();
    $next_member = get_next_post();
?>

<main id="main-content" class="site-main">

    <!-- ── PROFILE HERO ── -->
    <header class="member-hero">
        <div class="container member-hero__inner">

            <!-- Breadcrumb -->
            <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'golden-globe'); ?>">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item"><a href="<?php echo esc_url(home_url('/team/')); ?>"><?php esc_html_e('Team', 'golden-globe'); ?></a></li>
                    <li class="breadcrumbs__item" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>

            <div class="member-hero__profile">

                <!-- Photo -->
                <div class="member-hero__photo-wrap">
                    <?php if ($has_thumb) : ?>
                        <img class="member-hero__photo"
                             src="<?php echo esc_url($thumb_url); ?>"
                             alt="<?php echo esc_attr(get_the_title()); ?>"
                             width="360" height="360">
                    <?php else : ?>
                        <div class="member-hero__photo member-hero__photo--placeholder">
                            <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="40" cy="30" r="16" fill="#cbd5e1"/>
                                <path d="M8 72c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#cbd5e1"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Meta -->
                <div class="member-hero__meta">
                    <h1 class="member-hero__name"><?php the_title(); ?></h1>

                    <?php if ($role) : ?>
                        <p class="member-hero__role"><?php echo esc_html($role); ?></p>
                    <?php endif; ?>

                    <?php if ($speciality) : ?>
                        <p class="member-hero__speciality"><?php echo esc_html($speciality); ?></p>
                    <?php endif; ?>

                    <!-- Social links -->
                    <?php if ($linkedin || $twitter || $github || $email) : ?>
                        <ul class="member-hero__social" aria-label="<?php esc_attr_e('Social links', 'golden-globe'); ?>">
                            <?php if ($linkedin) : ?>
                                <li>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                                        LinkedIn
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($twitter) : ?>
                                <li>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X">
                                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        Twitter
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($github) : ?>
                                <li>
                                    <a href="<?php echo esc_url($github); ?>" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                                        GitHub
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($email) : ?>
                                <li>
                                    <a href="mailto:<?php echo esc_attr($email); ?>" aria-label="<?php esc_attr_e('Send email', 'golden-globe'); ?>">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                        <?php esc_html_e('Email', 'golden-globe'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div><!-- .member-hero__meta -->

            </div><!-- .member-hero__profile -->
        </div><!-- .container -->
    </header>

    <!-- ── TWO-COLUMN LAYOUT ── -->
    <div class="container member-columns">

        <!-- ── MAIN CONTENT ── -->
        <article class="member-content">

            <?php if (get_the_content()) : ?>
                <section class="member-bio">
                    <h2 class="member-section-title"><?php esc_html_e('About', 'golden-globe'); ?></h2>
                    <div class="member-bio__body prose">
                        <?php the_content(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Skills -->
            <?php if (!empty($skills)) : ?>
                <section class="member-skills">
                    <h2 class="member-section-title"><?php esc_html_e('Skills & Expertise', 'golden-globe'); ?></h2>
                    <ul class="member-skills__list" aria-label="<?php esc_attr_e('Skills', 'golden-globe'); ?>">
                        <?php foreach ($skills as $skill) :
                            $skill_name  = $skill['skill_name']  ?? '';
                            $skill_level = absint($skill['skill_level'] ?? 0);
                            if (!$skill_name) continue;
                        ?>
                            <li class="member-skills__item">
                                <div class="member-skills__label">
                                    <span><?php echo esc_html($skill_name); ?></span>
                                    <?php if ($skill_level) : ?>
                                        <span class="member-skills__pct"><?php echo esc_html($skill_level); ?>%</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($skill_level) : ?>
                                    <div class="member-skills__bar" role="progressbar"
                                         aria-valuenow="<?php echo esc_attr($skill_level); ?>"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <div class="member-skills__fill" style="width:<?php echo esc_attr($skill_level); ?>%"></div>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

        </article><!-- .member-content -->

        <!-- ── SIDEBAR ── -->
        <aside class="member-sidebar" aria-label="<?php esc_attr_e('Team member details', 'golden-globe'); ?>">

            <!-- Contact card -->
            <?php if ($email || $phone) : ?>
                <div class="member-card">
                    <h3 class="member-card__title"><?php esc_html_e('Get in Touch', 'golden-globe'); ?></h3>
                    <ul class="member-card__contact">
                        <?php if ($email) : ?>
                            <li>
                                <span class="member-card__label"><?php esc_html_e('Email', 'golden-globe'); ?></span>
                                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                            <li>
                                <span class="member-card__label"><?php esc_html_e('Phone', 'golden-globe'); ?></span>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn--primary btn--full">
                        <?php esc_html_e('Send a Message', 'golden-globe'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Role / speciality card -->
            <?php if ($role || $speciality) : ?>
                <div class="member-card">
                    <h3 class="member-card__title"><?php esc_html_e('Profile', 'golden-globe'); ?></h3>
                    <ul class="member-card__meta">
                        <?php if ($role) : ?>
                            <li>
                                <span class="member-card__label"><?php esc_html_e('Role', 'golden-globe'); ?></span>
                                <span><?php echo esc_html($role); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($speciality) : ?>
                            <li>
                                <span class="member-card__label"><?php esc_html_e('Speciality', 'golden-globe'); ?></span>
                                <span><?php echo esc_html($speciality); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- All team members list -->
            <?php
            $all_members = new WP_Query([
                'post_type'      => 'team_member',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]);
            if ($all_members->have_posts()) :
            ?>
                <div class="member-card member-card--list">
                    <h3 class="member-card__title"><?php esc_html_e('Meet the Team', 'golden-globe'); ?></h3>
                    <ul class="member-list">
                        <?php while ($all_members->have_posts()) : $all_members->the_post();
                            $is_current = get_the_ID() === $post_id;
                        ?>
                            <li class="member-list__item<?php echo $is_current ? ' member-list__item--current' : ''; ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img class="member-list__avatar"
                                         src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')); ?>"
                                         alt="<?php echo esc_attr(get_the_title()); ?>"
                                         width="40" height="40">
                                <?php else : ?>
                                    <span class="member-list__avatar member-list__avatar--placeholder" aria-hidden="true"></span>
                                <?php endif; ?>
                                <div class="member-list__info">
                                    <?php if ($is_current) : ?>
                                        <strong><?php the_title(); ?></strong>
                                    <?php else : ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <?php endif; ?>
                                    <?php
                                    $m_role = function_exists('get_field')
                                        ? agency_get_field('member_role', get_the_ID())
                                        : get_post_meta(get_the_ID(), 'member_role', true);
                                    if ($m_role) : ?>
                                        <span><?php echo esc_html($m_role); ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>
                </div>
            <?php endif; ?>

        </aside><!-- .member-sidebar -->

    </div><!-- .member-columns -->

    <!-- ── RELATED TEAM MEMBERS ── -->
    <?php
    $related = new WP_Query([
        'post_type'      => 'team_member',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'post__not_in'   => [$post_id],
        'orderby'        => 'rand',
    ]);
    if ($related->have_posts()) :
    ?>
        <section class="member-related">
            <div class="container">
                <h2 class="member-related__title"><?php esc_html_e('Other Team Members', 'golden-globe'); ?></h2>
                <div class="member-related__grid">
                    <?php while ($related->have_posts()) : $related->the_post();
                        $r_id   = get_the_ID();
                        $r_role = function_exists('get_field')
                            ? agency_get_field('member_role', $r_id)
                            : get_post_meta($r_id, 'member_role', true);
                    ?>
                        <article class="member-card-item">
                            <a href="<?php the_permalink(); ?>" class="member-card-item__photo-link" tabindex="-1" aria-hidden="true">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('team-square', ['class' => 'member-card-item__photo', 'loading' => 'lazy']); ?>
                                <?php else : ?>
                                    <div class="member-card-item__photo member-card-item__photo--placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </a>
                            <div class="member-card-item__body">
                                <h3 class="member-card-item__name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <?php if ($r_role) : ?>
                                    <p class="member-card-item__role"><?php echo esc_html($r_role); ?></p>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="member-card-item__link">
                                    <?php esc_html_e('View Profile', 'golden-globe'); ?>
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ── PREV / NEXT NAVIGATION ── -->
    <?php if ($prev_member || $next_member) : ?>
        <nav class="project-nav" aria-label="<?php esc_attr_e('Team member navigation', 'golden-globe'); ?>">
            <div class="project-nav__bar">
                <span></span><span></span><span></span>
            </div>
            <div class="project-nav__inner container">

                <?php if ($prev_member) :
                    $prev_thumb = get_the_post_thumbnail_url($prev_member->ID, 'thumbnail');
                ?>
                    <a href="<?php echo esc_url(get_permalink($prev_member->ID)); ?>" class="project-nav__item project-nav__item--prev">
                        <?php if ($prev_thumb) : ?>
                            <img src="<?php echo esc_url($prev_thumb); ?>" alt="" width="48" height="48" class="project-nav__thumb">
                        <?php endif; ?>
                        <div>
                            <span class="project-nav__label"><?php esc_html_e('Previous', 'golden-globe'); ?></span>
                            <span class="project-nav__name"><?php echo esc_html(get_the_title($prev_member->ID)); ?></span>
                        </div>
                        <svg class="project-nav__arrow" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H6.612l3.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L6.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd"/></svg>
                    </a>
                <?php else : ?>
                    <span></span>
                <?php endif; ?>

                <a href="<?php echo esc_url(home_url('/team/')); ?>" class="project-nav__all" aria-label="<?php esc_attr_e('All team members', 'golden-globe'); ?>">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </a>

                <?php if ($next_member) :
                    $next_thumb = get_the_post_thumbnail_url($next_member->ID, 'thumbnail');
                ?>
                    <a href="<?php echo esc_url(get_permalink($next_member->ID)); ?>" class="project-nav__item project-nav__item--next">
                        <div>
                            <span class="project-nav__label"><?php esc_html_e('Next', 'golden-globe'); ?></span>
                            <span class="project-nav__name"><?php echo esc_html(get_the_title($next_member->ID)); ?></span>
                        </div>
                        <?php if ($next_thumb) : ?>
                            <img src="<?php echo esc_url($next_thumb); ?>" alt="" width="48" height="48" class="project-nav__thumb">
                        <?php endif; ?>
                        <svg class="project-nav__arrow" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                    </a>
                <?php else : ?>
                    <span></span>
                <?php endif; ?>

            </div>
        </nav>
    <?php endif; ?>

</main>

<?php
endwhile;
get_footer();
