<?php

use Domain\Form\Models\Form;

test('where-active', function () {
    // Create an inactive form
    Form::factory()->inactive()->create();
    // Create an active form
    $form = Form::factory()->active()->create();

    $forms = Form::query()->whereActive()->get();

    expect($forms->count())->toBe(1);
    expect($forms->first()->id)->toBe($form->id);
});

test('where-inactive', function () {
    // Create an active form
    Form::factory()->active()->create();
    // Create an inactive form
    $form = Form::factory()->inactive()->create();

    $forms = Form::query()->whereInactive()->get();

    expect($forms->count())->toBe(1);
    expect($forms->first()->id)->toBe($form->id);
});
