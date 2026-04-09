<?php
defined('ABSPATH') || exit;

class Agency_Component {

    private string $slug;
    private array  $args;

    public function __construct(string $slug, array $args = []) {
        $this->slug = $slug;
        $this->args = $args;
    }

    public function render(): void {
        get_template_part('template-parts/' . $this->slug, null, $this->args);
    }

    public function get(): string {
        ob_start();
        $this->render();
        return ob_get_clean();
    }

    public static function make(string $slug, array $args = []): self {
        return new self($slug, $args);
    }
}
