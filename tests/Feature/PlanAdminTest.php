<?php

use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use App\Models\User;
use Database\Seeders\PlansTableSeeder;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Wave\Plan;
use Wave\Subscription;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
    $this->seed(PlansTableSeeder::class);

    $this->basic = Plan::find(1);
    $this->premium = Plan::find(2);
    $this->pro = Plan::find(3);

    $this->subscribe = fn (Plan $plan, ?User $user = null) => Subscription::create([
        'billable_type' => 'user',
        'billable_id' => ($user ?? User::factory()->create())->id,
        'plan_id' => $plan->id,
        'vendor_slug' => 'stripe',
        'vendor_subscription_id' => 'sub_'.uniqid(),
        'cycle' => 'month',
        'status' => 'active',
        'seats' => 1,
    ]);

    $this->renderPricing = fn () => view('theme::components.marketing.sections.pricing')->render();
});

test('seeded comma-separated features load into the admin form', function () {
    $component = Livewire::test(EditPlan::class, ['record' => $this->basic->id]);

    expect(array_map('trim', $component->get('data.features')))->toBe([
        'Basic Feature Example 1',
        'Basic Feature Example 2',
        'Basic Feature Example 3',
        'Basic Feature Example 4',
    ]);
});

test('features saved in the admin are stored as a comma-separated string and shown on the pricing page', function () {
    Livewire::test(EditPlan::class, ['record' => $this->basic->id])
        ->fillForm(['features' => ['Unlimited projects', 'Priority support']])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(DB::table('plans')->where('id', $this->basic->id)->value('features'))
        ->toBe('Unlimited projects,Priority support')
        ->and($this->basic->fresh()->featureList())->toBe(['Unlimited projects', 'Priority support']);

    expect(($this->renderPricing)())
        ->toContain('Unlimited projects')
        ->toContain('Priority support')
        ->toContain('Premium Feature Example 2');
});

test('plans created with a features array render on the pricing page', function () {
    $plan = Plan::create([
        'name' => 'Team',
        'features' => ['Shared workspaces', 'SSO'],
        'role_id' => $this->pro->role_id,
        'monthly_price_id' => 'price_team',
    ]);

    expect(($this->renderPricing)())->toContain('Shared workspaces')->toContain('SSO')
        ->and($plan->fresh()->features)->toBe('Shared workspaces,SSO');
});

test('features stored as JSON by the former array cast are read as a comma-separated string', function () {
    DB::table('plans')->where('id', $this->basic->id)->update(['features' => '"One,Two"']);
    DB::table('plans')->where('id', $this->premium->id)->update(['features' => '["Three","Four"]']);
    DB::table('plans')->where('id', $this->pro->id)->update(['features' => '']);

    expect(($this->renderPricing)())->toContain('Two')->toContain('Four')
        ->and($this->basic->fresh()->features)->toBe('One,Two')
        ->and($this->premium->fresh()->features)->toBe('Three,Four')
        ->and($this->pro->fresh()->featureList())->toBe([]);
});

test('limits saved in the admin keep their documented meaning', function () {
    Livewire::test(EditPlan::class, ['record' => $this->basic->id])
        ->fillForm(['limits' => ['api_keys' => '-1', 'projects' => '', 'seats' => '0', 'exports' => '5']])
        ->call('save')
        ->assertHasNoFormErrors();

    $plan = $this->basic->fresh();

    expect($plan->getLimit('api_keys'))->toBeNull()
        ->and($plan->getLimit('projects'))->toBeNull()
        ->and($plan->getLimit('seats'))->toBe(0)
        ->and($plan->getLimit('exports'))->toBe(5);

    $user = User::factory()->create();
    ($this->subscribe)($plan, $user);

    expect($user->featureLimit('api_keys'))->toBeNull()
        ->and($user->canUseFeature('api_keys'))->toBeTrue()
        ->and($user->canUseFeature('projects'))->toBeTrue()
        ->and($user->canUseFeature('seats'))->toBeFalse();
});

test('the admin form rejects limits that are not whole numbers', function (string $limit) {
    Livewire::test(EditPlan::class, ['record' => $this->basic->id])
        ->fillForm(['limits' => ['api_keys' => $limit]])
        ->call('save')
        ->assertHasFormErrors(['limits']);
})->with(['ten', '2.5', '-2']);

test('a null default limit means unlimited for users without a plan', function () {
    config(['limits.defaults.api_keys' => null]);

    $user = User::factory()->create();

    expect($user->featureLimit('api_keys'))->toBeNull()
        ->and($user->canUseFeature('api_keys'))->toBeTrue();
});

test('deleting a plan with subscriptions from the table is refused', function () {
    ($this->subscribe)($this->pro);

    Livewire::test(ListPlans::class)
        ->callTableAction(DeleteAction::class, $this->pro)
        ->assertNotified('This plan has subscriptions');

    expect(Plan::find($this->pro->id))->not->toBeNull()
        ->and(Subscription::where('plan_id', $this->pro->id)->count())->toBe(1);
});

test('deleting a plan with subscriptions from the edit page is refused', function () {
    ($this->subscribe)($this->pro);

    Livewire::test(EditPlan::class, ['record' => $this->pro->id])
        ->callAction(DeleteAction::class)
        ->assertNotified('This plan has subscriptions');

    expect(Plan::find($this->pro->id))->not->toBeNull()
        ->and(Subscription::where('plan_id', $this->pro->id)->count())->toBe(1);
});

test('bulk deleting plans is refused when any of them has subscriptions', function () {
    ($this->subscribe)($this->pro);

    Livewire::test(ListPlans::class)
        ->callTableBulkAction('delete', [$this->basic, $this->pro])
        ->assertNotified('This plan has subscriptions');

    expect(Plan::count())->toBe(3)
        ->and(Subscription::count())->toBe(1);
});

test('plans without subscriptions can still be deleted', function () {
    ($this->subscribe)($this->pro);

    Livewire::test(ListPlans::class)->callTableAction(DeleteAction::class, $this->basic);
    Livewire::test(ListPlans::class)->callTableBulkAction('delete', [$this->premium]);

    expect(Plan::pluck('id')->all())->toBe([$this->pro->id]);
});

test('admin edits and deletes refresh the cached plans', function () {
    expect(Plan::getActivePlans()->pluck('name')->all())->toBe(['Basic', 'Premium', 'Pro'])
        ->and(Plan::getByName('Premium'))->not->toBeNull()
        ->and(Plan::getByName('Basic'))->not->toBeNull();

    Livewire::test(EditPlan::class, ['record' => $this->premium->id])
        ->fillForm(['name' => 'Business'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(ListPlans::class)->callTableAction(DeleteAction::class, $this->basic);

    expect(Plan::getActivePlans()->pluck('name')->all())->toBe(['Business', 'Pro'])
        ->and(Plan::getByName('Premium'))->toBeNull()
        ->and(Plan::getByName('Basic'))->toBeNull()
        ->and(Plan::getByName('Business')?->id)->toBe($this->premium->id);
});
