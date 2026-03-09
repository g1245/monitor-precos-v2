<?php

namespace App\Livewire\Concerns;

trait ScrollsToProductsOnPageChange
{
    /**
     * Scroll the products grid into view when the user navigates to a new page.
     */
    public function updatedPage(): void
    {
        $this->js("document.getElementById('filters')?.scrollIntoView({behavior: 'smooth'})");
    }
}
