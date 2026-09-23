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

/**
 * Without Pro the connector offers the preset alone: the expression, timezone
 * and run type shape a schedule nothing would run, and the cron field they
 * need never ships. What Pro then adds lives in Pro's own suite.
 */
it('offers the preset alone while the pro package is absent', function () {
    $this->loginAsAdmin();

    $job = JobInstancesProxy::create([
        'code'        => 'schedule-teaser-'.uniqid(),
        'type'        => 'export',
        'entity_type' => 'shopifyProduct',
        'action'      => 'export',
        'filters'     => ['schedule_cron_preset' => '*/5 * * * *'],
    ]);

    $html = get(route('admin.settings.data_transfer.exports.edit', $job->id))->assertOk()->getContent();

    expect($html)->toContain('only="schedule_cron_preset"')
        ->not->toContain('v-field-cron');
})->skip(fn (): bool => shopifyProBooted(), 'Pro is the one that ships the rest of the card.');

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
