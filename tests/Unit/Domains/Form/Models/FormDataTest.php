<?php

use Domain\Form\Models\Form;
use Domain\Form\Models\FormData;

test('form-data-has-form', function () {
    $form = Form::factory()->create();
    $formData = FormData::factory()->create(['form_id' => $form->id]);

    expect($formData->form->id)->toBe($form->id);
    expect($formData->form->name)->toBe($form->name);
    expect($formData->form->slug)->toBe($form->slug);
    expect($formData->form->fields)->toBe($form->fields);
});
