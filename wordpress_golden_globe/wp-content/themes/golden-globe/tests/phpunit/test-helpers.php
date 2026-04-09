<?php
/**
 * Tests: Helper Functions
 *
 * @package GoldenGlobe
 */

class Test_Agency_Helpers extends WP_UnitTestCase {

    public function test_agency_excerpt_trims_text(): void {
        $text   = str_repeat('word ', 30);
        $result = agency_excerpt($text, 10);

        $this->assertStringContainsString('&hellip;', $result);
        $this->assertLessThanOrEqual(100, strlen($result));
    }

    public function test_agency_hex_to_rgba(): void {
        $result = agency_hex_to_rgba('#2563eb', 0.5);
        $this->assertStringStartsWith('rgba(', $result);
        $this->assertStringContainsString('0.5', $result);
    }

    public function test_agency_option_returns_default(): void {
        $result = agency_option('nonexistent_key', 'fallback');
        $this->assertEquals('fallback', $result);
    }

    public function test_cpt_portfolio_is_registered(): void {
        $this->assertTrue(post_type_exists('portfolio'));
    }

    public function test_cpt_service_is_registered(): void {
        $this->assertTrue(post_type_exists('service'));
    }

    public function test_cpt_testimonial_is_registered(): void {
        $this->assertTrue(post_type_exists('testimonial'));
    }

    public function test_cpt_team_member_is_registered(): void {
        $this->assertTrue(post_type_exists('team_member'));
    }

    public function test_taxonomy_portfolio_cat_is_registered(): void {
        $this->assertTrue(taxonomy_exists('portfolio_cat'));
    }

    public function test_taxonomy_service_type_is_registered(): void {
        $this->assertTrue(taxonomy_exists('service_type'));
    }

    public function test_agency_excerpt_respects_word_limit(): void {
        $text   = 'one two three four five six seven eight nine ten eleven twelve';
        $result = agency_excerpt($text, 5);
        $words  = str_word_count(strip_tags(str_replace('&hellip;', '', $result)));
        $this->assertLessThanOrEqual(5, $words);
    }

    public function test_agency_hex_to_rgba_three_char_hex(): void {
        $result = agency_hex_to_rgba('#fff', 1);
        $this->assertStringStartsWith('rgba(', $result);
    }

    public function test_agency_is_template_returns_bool(): void {
        // Not on a template page in test env, should return false.
        $this->assertIsBool(agency_is_template('template-landing'));
    }
}
