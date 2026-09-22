import { expect } from '@playwright/test';

export async function dismissPromos(page) {
    await page.evaluate(() => {
        document.querySelectorAll('.phpdebugbar').forEach((el) => { el.style.display = 'none'; });
        document.querySelectorAll('button').forEach((btn) => {
            if ((btn.textContent || '').trim() === "Don't show again") btn.click();
        });
    });
}

export async function openDataGridFilters(page) {
    // Vue replaces the v-drawer custom element with its rendered trigger, so
    // locating an ancestor <v-drawer> only works in the unmounted template.
    await page.getByText('Filter', { exact: true }).first().click();
    await expect(page.locator('[data-drawer-panel]')).toBeVisible();
}
