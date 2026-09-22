import { test, expect } from '@playwright/test';

test.use({ storageState: 'storage/auth.json' });

test.describe('UnoPim Authenticated Tests', () => {
    test('should navigate to dashboard without login', async ({ page }) => {
        await page.goto('/admin/dashboard'); // Directly go to dashboard
        await expect(page).toHaveURL(/\/admin\/dashboard$/);
    });
});
