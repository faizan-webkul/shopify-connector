<?php

use Illuminate\Support\Facades\Event;
use Webkul\DataTransfer\Models\JobInstancesProxy;
use Webkul\Shopify\Support\ShopifySchedule;

use function Pest\Laravel\get;

/**
 * The schedule fields a Shopify exporter offers, as core renders them from the
 * exporter config.
 *
 * @return array<string, array<string, mixed>>
 */
function scheduleFields(string $entityType = 'shopifyProduct'): array
{
    return collect(config("exporters.{$entityType}.filters.fields", []))
        ->keyBy('name')
        ->all();
}

it('offers every preset, plus disabled and custom', function () {
    $values = array_column(scheduleFields()['schedule_cron_preset']['options'], 'value');

    expect($values)->toContain(ShopifySchedule::DISABLED)
        ->toContain(ShopifySchedule::CUSTOM)
        ->and(array_intersect(array_keys(ShopifySchedule::presets()), $values))
        ->toBe(array_keys(ShopifySchedule::presets()));
});

it('names each preset with the expression it runs on', function () {
    $options = collect(scheduleFields()['schedule_cron_preset']['options'])
        ->reject(fn (array $option): bool => in_array($option['value'], [ShopifySchedule::DISABLED, ShopifySchedule::CUSTOM], true));

    $options->each(fn (array $option) => expect(ShopifySchedule::presets())->toHaveKey($option['value']));
});

it('keeps the cron expression on screen with the rest of the schedule section', function () {
    $field = scheduleFields()['schedule_cron_expression'];

    expect($field['visible_when']['values'])->toContain(ShopifySchedule::CUSTOM)
        ->not->toContain(ShopifySchedule::DISABLED)
        ->and(array_intersect(array_keys(ShopifySchedule::presets()), $field['visible_when']['values']))
        ->toBe(array_keys(ShopifySchedule::presets()))
        ->and($field['depends_on']['field'])->toBe('schedule_cron_preset');
});

it('fills the expression from the preset it names', function () {
    expect(ShopifySchedule::fill(['schedule_cron_preset' => '0 6 * * *']))
        ->toHaveKey('schedule_cron_expression', '0 6 * * *')
        ->and(ShopifySchedule::fill(['schedule_cron_preset' => '0 6 * * *', 'schedule_cron_expression' => '0 0 1 1 *']))
        ->toHaveKey('schedule_cron_expression', '0 6 * * *');
});

it('leaves a custom expression alone', function () {
    expect(ShopifySchedule::fill(['schedule_cron_preset' => ShopifySchedule::CUSTOM, 'schedule_cron_expression' => '5 4 * * *']))
        ->toHaveKey('schedule_cron_expression', '5 4 * * *');
});

it('drops the expression once the schedule is switched off', function () {
    expect(ShopifySchedule::fill(['schedule_cron_preset' => ShopifySchedule::DISABLED, 'schedule_cron_expression' => '0 6 * * *']))
        ->not->toHaveKey('schedule_cron_expression')
        ->and(ShopifySchedule::fill([]))
        ->not->toHaveKey('schedule_cron_expression')
        ->and(ShopifySchedule::fill(['schedule_cron_expression' => '0 6 * * *']))
        ->not->toHaveKey('schedule_cron_expression');
});

it('offers the timezone and the run type on every schedule', function (string $field) {
    $values = scheduleFields()[$field]['visible_when']['values'];

    expect($values)->toContain(ShopifySchedule::CUSTOM)
        ->and(array_intersect(array_keys(ShopifySchedule::presets()), $values))
        ->toBe(array_keys(ShopifySchedule::presets()));
})->with(['schedule_timezone', 'schedule_type']);

it('offers the schedule on every shopify export that can run unattended', function () {
    $entityTypes = config('shopify_schedule.entity_types', []);

    expect($entityTypes)->not->toBeEmpty();

    foreach ($entityTypes as $entityType) {
        expect(array_keys(scheduleFields($entityType)))->toContain('schedule_cron_preset');
    }
});

it('ships the schedule fields to the create screen, expression bound to custom', function () {
    $this->loginAsAdmin();

    $html = get(route('admin.settings.data_transfer.exports.create'))->assertOk()->getContent();

    expect($html)->toContain('schedule_cron_preset')
        ->toContain('"name":"schedule_cron_expression","type":"cron"')
        ->toContain('v-field-cron');
});

it('fills the expression as soon as the first preset mounts the field', function () {
    $this->loginAsAdmin();

    $html = get(route('admin.settings.data_transfer.exports.create'))->assertOk()->getContent();

    expect($html)->toContain('this.applyPreset(this.preset)')
        ->toContain('query_params || {}).preset');
});

it('opens a saved preset on the edit screen without asking for an expression', function () {
    $this->loginAsAdmin();

    $job = JobInstancesProxy::create([
        'code'        => 'schedule-edit-'.uniqid(),
        'type'        => 'export',
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['schedule_cron_preset' => '*/5 * * * *', 'schedule_type' => ShopifySchedule::RECURRING],
    ]);

    $html = get(route('admin.settings.data_transfer.exports.edit', $job->id))->assertOk()->getContent();

    expect($html)->toContain('schedule_cron_preset')
        ->toContain('"schedule_cron_expression":"*\/5 * * * *"')
        ->toContain('v-field-cron');
});

it('asks for an expression only once a schedule is picked', function (array $filters, bool $valid) {
    $validator = resolve(config('exporters.shopifyProduct.validator'));

    $data = array_merge([
        'code'        => 'schedule-rules-'.uniqid(),
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => array_merge(['credentials' => 1, 'channels' => 'default', 'currencies' => 'USD'], $filters),
    ], []);

    $run = fn () => $validator->validate($data);

    $valid ? expect($run)->not->toThrow(Exception::class) : expect($run)->toThrow(Exception::class);
})->with([
    'preset needs nothing typed'     => [['schedule_cron_preset' => '0 * * * *'], true],
    'custom needs an expression'     => [['schedule_cron_preset' => ShopifySchedule::CUSTOM], false],
    'custom with a blank expression' => [['schedule_cron_preset' => ShopifySchedule::CUSTOM, 'schedule_cron_expression' => ''], false],
    'custom with an expression'      => [['schedule_cron_preset' => ShopifySchedule::CUSTOM, 'schedule_cron_expression' => '0 * * * *'], true],
    'disabled needs nothing'         => [['schedule_cron_preset' => ShopifySchedule::DISABLED], true],
    'disabled keeps no expression'   => [['schedule_cron_preset' => ShopifySchedule::DISABLED, 'schedule_cron_expression' => ''], true],
    'no schedule at all'             => [[], true],
]);

it('saves a profile with no schedule without asking for an expression', function () {
    $this->loginAsAdmin();

    $response = $this->post(route('admin.settings.data_transfer.exports.store'), [
        'code'        => 'schedule-off-'.uniqid(),
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['credentials' => 1, 'channels' => 'default', 'currencies' => 'USD'],
    ]);

    $response->assertSessionHasNoErrors();
});

it('clears a stale expression when the schedule is switched off again', function () {
    $job = JobInstancesProxy::create([
        'code'        => 'schedule-off-save-'.uniqid(),
        'type'        => 'export',
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['schedule_cron_preset' => ShopifySchedule::DISABLED, 'schedule_cron_expression' => '0 6 * * *'],
    ]);

    Event::dispatch('data_transfer.exports.update.after', $job);

    expect($job->refresh()->filters)->not->toHaveKey('schedule_cron_expression');
});

it('stores the preset expression with the profile when it is saved', function () {
    $job = JobInstancesProxy::create([
        'code'        => 'schedule-save-'.uniqid(),
        'type'        => 'export',
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['schedule_cron_preset' => '0 6 * * *', 'schedule_cron_expression' => '0 0 1 1 *'],
    ]);

    Event::dispatch('data_transfer.exports.update.after', $job);

    expect($job->refresh()->filters)->toHaveKey('schedule_cron_expression', '0 6 * * *');
});

it('leaves a custom expression untouched when the profile is saved', function () {
    $job = JobInstancesProxy::create([
        'code'        => 'schedule-save-custom-'.uniqid(),
        'type'        => 'export',
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['schedule_cron_preset' => ShopifySchedule::CUSTOM, 'schedule_cron_expression' => '5 4 * * *'],
    ]);

    Event::dispatch('data_transfer.exports.update.after', $job);

    expect($job->refresh()->filters)->toHaveKey('schedule_cron_expression', '5 4 * * *');
});
