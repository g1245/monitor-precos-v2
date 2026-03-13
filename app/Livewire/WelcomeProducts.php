<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class WelcomeProducts extends Component
{
    public string $tab = 'destaques';
    public int $limit = 16;
    public bool $hasMore = true;

    public function loadMore(): void
    {
        $this->limit += 16;
    }

    public function render()
    {
        $query = Product::query()
            ->active()
            ->where('is_parent', 0)
            ->with('store');

        $query = match ($this->tab) {
            'recentes'        => $query->orderByDesc('created_at'),
            'mais-acessados'  => $query->orderByDesc('views_count'),
            default           => $query->whereColumn('price', '<', 'price_regular')
                                       ->orderByDesc('discount_percentage'),
        };

        $products = $query->limit($this->limit + 1)->get();

        $this->hasMore = $products->count() > $this->limit;
        $products = $products->take($this->limit);

        return view('livewire.welcome-products', compact('products'));
    }
}
