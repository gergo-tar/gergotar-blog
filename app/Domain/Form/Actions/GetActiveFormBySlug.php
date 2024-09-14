<?php

namespace Domain\Form\Actions;

use Domain\Form\Models\Form;
use Lorisleiva\Actions\Concerns\AsAction;

class GetActiveFormBySlug
{
    use AsAction;

    /**
     * Get the active form by the given slug.
     *
     * @param string $slug  The form slug.
     * @param array  $columns  The columns to select.
     */
    public function handle(string $slug, array $columns = ['*']): ?Form
    {
        /** @var Form|null $form */
        $form = Form::select($columns)
            ->whereSlug($slug)
            ->whereActive()
            ->first();

        return $form;
    }
}
