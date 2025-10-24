<?php

namespace App\Livewire;

use App\Models\PriceAlert;
use App\Models\Product;
use Livewire\Component;

class PriceAlertModal extends Component
{
    public $productId;
    public $showModal = false;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais de 255 caracteres.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'Por favor, insira um e-mail válido.',
        'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
        'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetValidation();
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'phone', 'successMessage', 'errorMessage']);
        $this->resetValidation();
    }

    public function submit()
    {
        $this->validate();

        try {
            $product = Product::findOrFail($this->productId);

            PriceAlert::create([
                'product_id' => $this->productId,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'target_price' => $product->price * 0.9,
            ]);

            $this->successMessage = 'Alerta criado com sucesso! Você será notificado quando o preço baixar.';
            $this->reset(['name', 'email', 'phone', 'errorMessage']);
            
            $this->dispatch('alert-created');
        } catch (\Exception $e) {
            $this->errorMessage = 'Erro ao criar alerta. Por favor, tente novamente.';
        }
    }

    public function render()
    {
        return view('livewire.price-alert-modal');
    }
}
