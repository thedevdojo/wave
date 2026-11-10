<?php

use App\Filament\Resources\Forms\Pages\CreateForms;
use App\Filament\Resources\Forms\Pages\EditForms;
use App\Models\Forms;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

it('fills the slug as the form name is typed', function () {
    Livewire::test(CreateForms::class)
        ->fillForm(['name' => 'Contact Us'])
        ->assertSchemaStateSet(['slug' => 'contact-us']);
});

it('stores the fields of a new form as an array', function () {
    Livewire::test(CreateForms::class)
        ->fillForm([
            'name' => 'Contact Us',
            'slug' => 'contact-us',
            'fields' => [['label' => 'Email', 'type' => 'TextInput', 'rules' => 'required|email']],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $form = Forms::where('slug', 'contact-us')->firstOrFail();

    expect($form->fields)->toBe([['label' => 'Email', 'type' => 'TextInput', 'rules' => 'required|email']]);
});

it('still opens forms whose fields were stored as a JSON string', function () {
    $fields = [['label' => 'Email', 'type' => 'TextInput', 'rules' => null]];
    $form = Forms::create(['name' => 'Old Form', 'slug' => 'old-form', 'fields' => []]);
    DB::table('forms')->where('id', $form->id)->update(['fields' => json_encode(json_encode($fields))]);

    $page = Livewire::test(EditForms::class, ['record' => $form->id])->assertOk();

    expect(array_values($page->get('data.fields')))->toHaveCount(1)
        ->and(array_values($page->get('data.fields'))[0]['label'])->toBe('Email');
});
