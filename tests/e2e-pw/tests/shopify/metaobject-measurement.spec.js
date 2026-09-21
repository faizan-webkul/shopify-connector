import { test, expect } from '@playwright/test';

test.use({ storageState: 'storage/auth.json' });

/**
 * The Pro package widens the connector's metaobject screens from Shopify's three
 * original measurement types to the full catalogue. These run against the seeded
 * "electrician" definition, which carries both legacy and Pro measurement fields.
 */
const definitionUrl = 'admin/shopify/metaobject/1/edit';

const clearOverlays = (page) =>
    page.evaluate(() => {
        document.querySelectorAll('#agenting-pim-panel, .phpdebugbar').forEach((element) => element.remove());
    });

test.describe('metaobject measurement support', () => {
    test('renders every measurement field as a number input', async ({ page }) => {
        await page.goto(definitionUrl);
        await clearOverlays(page);

        await page.locator('button', { hasText: 'Add Entry' }).first().click();
        await expect(page.getByText('Add Entry', { exact: true }).last()).toBeVisible();

        const numeric = page.locator('input[type="number"]');

        await expect(numeric).toHaveCount(5);
        await expect(numeric.first()).toHaveAttribute('step', 'any');
    });

    test('reports no failed extension', async ({ page }) => {
        const warnings = [];
        page.on('console', (message) => {
            if (message.text().includes('could not extend')) {
                warnings.push(message.text());
            }
        });

        await page.goto(definitionUrl);
        await page.waitForTimeout(2000);

        expect(warnings).toEqual([]);
    });

    test('validates pro measurement values the way shopify does', async ({ page }) => {
        await page.goto(definitionUrl);
        await page.waitForTimeout(2000);

        const result = await page.evaluate(() => {
            const entries = window.app.component('v-metaobject-entries');
            const form = window.app.component('v-metaobject-field-form');
            const field = (type, validations = {}) => ({ shopify_type: type, validations });
            const rules = (type, list, validations) =>
                entries.methods.passesFieldRules.call({}, field(type, validations), list);

            return {
                stringInPower: rules('power', ['sadasdfd']),
                stringInCapacitance: rules('capacitance', ['sdsfd']),
                stringInFlowRate: rules('volumetric_flow_rate', ['galvine']),
                stringInLegacyVolume: rules('volume', ['abc']),
                numberInPower: rules('power', ['12.5']),
                aboveMaximum: rules('power', ['50'], { max: '10' }),
                belowMinimum: rules('power', ['1'], { min: '10' }),
                proInputType: entries.methods.inputType.call({}, field('capacitance')),
                textInputType: entries.methods.inputType.call({}, field('single_line_text_field')),
                proUnits: form.computed.unitsFor.call({ field: { type: 'power' } }),
                legacyUnits: form.computed.unitsFor.call({ field: { type: 'weight' } }),
                proHasUnit: form.computed.hasUnit.call({ field: { type: 'capacitance' } }),
                textHasUnit: form.computed.hasUnit.call({ field: { type: 'single_line_text_field' } }),
            };
        });

        expect(result.stringInPower).toBe(false);
        expect(result.stringInCapacitance).toBe(false);
        expect(result.stringInFlowRate).toBe(false);
        expect(result.stringInLegacyVolume).toBe(false);
        expect(result.numberInPower).toBe(true);
        expect(result.aboveMaximum).toBe(false);
        expect(result.belowMinimum).toBe(false);

        expect(result.proInputType).toBe('number');
        expect(result.textInputType).toBe('text');

        expect(result.proUnits).toEqual(['milliwatts', 'watts', 'horsepower', 'kilowatts']);
        expect(result.legacyUnits).toEqual(['g', 'kg', 'oz', 'lb']);
        expect(result.proHasUnit).toBe(true);
        expect(result.textHasUnit).toBe(false);
    });
    test('offers a unit and bounds when a pro measurement type is chosen', async ({ page }) => {
        await page.goto(definitionUrl);
        await clearOverlays(page);

        await page.locator('button', { hasText: 'Add Field' }).first().click();
        await expect(page.getByPlaceholder('Field name')).toBeVisible();

        await page.locator('.multiselect').first().click();
        await page.locator('.multiselect__element', { hasText: 'Power (Pro)' }).first().click();

        await expect(page.locator('.multiselect', { hasText: 'Unit' }).first()).toBeVisible();
        await expect(page.getByPlaceholder('Min')).toBeVisible();
        await expect(page.getByPlaceholder('Max')).toBeVisible();
    });
});
