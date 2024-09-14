<?php

use Domain\Form\Models\Form;
use Domain\Form\Models\FormData;

test('form-has-form-data', function () {
    $form = Form::factory()->create();
    $formData = FormData::factory()->create(['form_id' => $form->id]);

    expect($form->formData->first())->toBeInstanceOf(FormData::class);
    expect($form->formData->count())->toBe(1);
    expect($form->formData->first()->id)->toBe($formData->id);
    expect($form->formData->first()->form_id)->toBe($form->id);
    expect($form->formData->first()->data)->toBe($formData->data);
});
