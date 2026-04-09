<?php
/**
 * Section: Counter Stats
 */
$bg_color = agency_get_sub_field('bg') ?: 'primary';
?>

<section class="section section-counter-stats section-counter-stats--<?php echo esc_attr($bg_color); ?>">
    <div class="container">
        <?php if (agency_have_rows('stats')) : ?>
            <div class="grid grid--4 reveal-fade">
                <?php while (agency_have_rows('stats')) : agency_the_row(); 
                    $number = agency_get_sub_field('number');
                    $label  = agency_get_sub_field('label');
                    $suffix = agency_get_sub_field('suffix') ?: '+';
                ?>
                    <div class="counter-item">
                        <div class="counter-item__number">
                            <span class="count-me" data-target="<?php echo esc_attr(preg_replace('/[^0-9]/', '', $number)); ?>">0</span><?php echo esc_html($suffix); ?>
                        </div>
                        <div class="counter-item__label"><?php echo esc_html($label); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.section-counter-stats--primary { background: var(--color-primary); color: white; padding: 100px 0; }
.section-counter-stats--dark    { background: var(--color-black); color: white; padding: 100px 0; }
.section-counter-stats--white   { background: var(--color-white); color: var(--color-black); padding: 100px 0; border-top: 1px solid var(--color-gray-100); border-bottom: 1px solid var(--color-gray-100); }

.counter-item { text-align: center; }
.counter-item__number { font-size: 4rem; font-weight: 900; line-height: 1; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
.counter-item__label { font-size: 1rem; text-transform: uppercase; font-weight: 700; opacity: 0.8; letter-spacing: 0.1em; }

@media (max-width: 768px) {
    .counter-item__number { font-size: 3rem; }
}
</style>

<script>
/**
 * Simple Counter Animation
 */
(function() {
    function animateCounters() {
        const counters = document.querySelectorAll('.count-me:not(.is-done)');
        if (!counters.length) return;

        counters.forEach(counter => {
            const rect = counter.getBoundingClientRect();
            if (rect.top <= window.innerHeight * 0.9) {
                const target = parseInt(counter.dataset.target);
                const duration = 2000;
                const startTime = performance.now();
                
                counter.classList.add('is-done');

                function updateCounter(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = progress === 1 ? progress : 1 - Math.pow(2, -10 * progress);
                    
                    const currentCount = Math.floor(eased * target);
                    counter.innerText = currentCount;

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                }
                requestAnimationFrame(updateCounter);
            }
        });
    }

    if (window.IntersectionObserver) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                }
            });
        }, { threshold: 0.1 });

        const items = document.querySelectorAll('.section-counter-stats');
        items.forEach(item => observer.observe(item));
    } else {
        window.addEventListener('scroll', animateCounters);
    }
})();
</script>
