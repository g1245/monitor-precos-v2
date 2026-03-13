<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class WelcomeProducts extends Component
{
    public string $tab = 'destaques';
    public int $page = 1;
    public bool $hasMore = true;

    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function loadMore(): void
    {
        $this->page++;
    }

    public function render()
    {
        $limit = $this->page * 16;

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

        $products = $query->limit($limit + 1)->get();

        $this->hasMore = $products->count() > $limit;
        $products = $products->take($limit);

        return view('livewire.welcome-products', compact('products', 'limit'));
    }
}
