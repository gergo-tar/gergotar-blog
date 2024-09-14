<?php

namespace Database\Factories\Traits;

trait FactoryHasActive
{
    /**
     * Set the model to be active.
     *
     * @return $this
     */
    public function active(): self
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    /**
     * Set the model to be inactive.
     *
     * @return $this
     */
    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
