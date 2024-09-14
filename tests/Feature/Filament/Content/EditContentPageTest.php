<?php

use Domain\User\Models\User;
use Filament\Actions\DeleteAction;
use Domain\Content\Models\Content;
use App\Filament\Resources\Content\ContentResource;
use App\Filament\Resources\Content\ContentResource\Pages\EditContentPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('content-edit-renders', function () {
    $this->get(
        ContentResource::getUrl(
            'edit',
            ['record' => Content::factory()->create()]
        )
    )->assertSuccessful();
});

test('content-edit-data', function () {
    $content = Content::factory()->hasSupportedTranslations()->create();

    $data = ['title' => $content->title];
    // TODO: TipTap Editor modifies the content. Need to find a way to compare it.
    // foreach ($content->translations as $translation) {
    //     $data[$translation->locale] = [
    //         'content' => $translation->content,
    //     ];
    // }

    // Check if form is filled with the correct data.
    livewire(EditContentPage::class, ['record' => $content->getRouteKey()])
        ->assertFormSet($data);
});

test('content-edit-save', function () {
    $content = Content::factory()->hasSupportedTranslations()->create();

    $data = ['title' => fake()->unique()->word()];
    foreach ($content->translations as $translation) {
        $data[$translation->locale] = [
            'content' => '<p>' . fake()->paragraph() . '</p>',
        ];
    }

    livewire(EditContentPage::class, ['record' => $content->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoFormErrors();

    // Check if content was updated.
    $this->assertDatabaseHas(Content::class, [
        'title' => $data['title'],
    ]);

    // Check if content was updated with the correct translations.
    $content->refresh()->translations->each(function ($translation) use ($data) {
        expect($data[$translation->locale])->toBe([
            'content' => $translation->content,
        ]);
    });
});

test('content-edit-validation', function () {
    $defaultLocale = config('app.locale');
    $content = Content::factory()->hasSupportedTranslations()->create();

    // Required error for Default locale.
    livewire(EditContentPage::class, ['record' => $content->getRouteKey()])
        ->fillForm([
            'title' => null,
            "{$defaultLocale}.content" => null,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'required',
            "{$defaultLocale}.content" => 'required'
        ]);

    // Unique error.
    $data = [
        'title' => $content->title,
    ];

    $otherContent = Content::factory()->hasDefaultTranslation()->create();

    livewire(EditContentPage::class, ['record' => $otherContent->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors([
            'title' => 'unique',
        ]);
});

test('content-delete', function () {
    $content = Content::factory()->create();

    livewire(EditContentPage::class, [
        'record' => $content->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($content);
});
