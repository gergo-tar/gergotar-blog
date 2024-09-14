<?php

use Domain\Form\Models\Form;
use Domain\Form\Actions\GetActiveFormBySlug;

test('get-active-form-by-slug', function () {
    // Create inactive forms
    Form::factory(3)->inactive()->create();
    // Create active forms
    Form::factory(3)->active()->create();
    // Create the specific form
    $form = Form::factory()->active()->create();
    $activeForm = GetActiveFormBySlug::run($form->slug);

    expect($activeForm->id)->toBe($form->id);
    expect($activeForm->name)->toBe($form->name);
    expect($activeForm->slug)->toBe($form->slug);
    expect($activeForm->fields)->toBe($form->fields);
});

test('get-active-form-by-slug-not-found', function () {
    // Create inactive forms
    Form::factory(3)->inactive()->create();
    // Create active forms
    Form::factory(3)->active()->create();
    // Create the specific form
    $form = Form::factory()->inactive()->create();
    $activeForm = GetActiveFormBySlug::run($form->slug);

    expect($activeForm)->toBeNull();
});
