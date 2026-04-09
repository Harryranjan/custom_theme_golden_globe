import { test, expect } from "@playwright/test";

const BASE_URL = process.env.WP_URL || "http://localhost:10000";

test.describe("Homepage", () => {
  test("loads and has correct title", async ({ page }) => {
    await page.goto(BASE_URL);
    await expect(page).toHaveTitle(/.+/);
  });

  test("skip link is present and functional", async ({ page }) => {
    await page.goto(BASE_URL);
    const skipLink = page.locator(".skip-link");
    await expect(skipLink).toBeAttached();

    await skipLink.focus();
    await expect(skipLink).toBeVisible();
  });

  test("navigation is accessible", async ({ page }) => {
    await page.goto(BASE_URL);
    const nav = page.locator('[role="navigation"]');
    await expect(nav).toHaveAttribute("aria-label", /.+/);
  });

  test("images have alt attributes", async ({ page }) => {
    await page.goto(BASE_URL);
    const images = page.locator("img:not([alt])");
    await expect(images).toHaveCount(0);
  });

  test("no console errors", async ({ page }) => {
    const errors = [];
    page.on("console", (msg) => {
      if (msg.type() === "error") errors.push(msg.text());
    });
    await page.goto(BASE_URL);
    expect(errors).toHaveLength(0);
  });

  test("main content landmark exists", async ({ page }) => {
    await page.goto(BASE_URL);
    const main = page.locator("#main-content");
    await expect(main).toBeVisible();
  });

  test("page has meta viewport tag", async ({ page }) => {
    await page.goto(BASE_URL);
    const viewport = page.locator('meta[name="viewport"]');
    await expect(viewport).toHaveAttribute("content", /width=device-width/);
  });
});

test.describe("Portfolio Filter", () => {
  test("AJAX filter returns results", async ({ page }) => {
    await page.goto(`${BASE_URL}/portfolio`);
    const filterBtn = page.locator("[data-filter]").first();

    if (await filterBtn.isVisible()) {
      await filterBtn.click();
      await page.waitForResponse((resp) =>
        resp.url().includes("admin-ajax.php"),
      );
      const grid = page.locator("[data-grid]");
      await expect(grid).toBeVisible();
    }
  });
});

test.describe("404 Page", () => {
  test("shows 404 content on missing page", async ({ page }) => {
    const response = await page.goto(
      `${BASE_URL}/this-page-does-not-exist-xyz123`,
    );
    expect(response?.status()).toBe(404);
    await expect(page.locator(".error-404")).toBeVisible();
  });
});
