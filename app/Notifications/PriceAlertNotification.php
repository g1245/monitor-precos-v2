<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\UserWishProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product,
        public UserWishProduct $wish
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $currentPrice = 'R$ ' . number_format($this->product->price, 2, ',', '.');
        $targetPrice = $this->wish->target_price ? 'R$ ' . number_format($this->wish->target_price, 2, ',', '.') : null;

        $message = 'O preço do produto "' . $this->product->name . '" mudou para ' . $currentPrice . '.';

        if ($targetPrice) {
            $discountPercentage = round((($this->wish->target_price - $this->product->price) / $this->wish->target_price) * 100, 1);
            $message .= ' Isso representa ' . $discountPercentage . '% abaixo da sua meta de ' . $targetPrice . '.';
        }

        return (new MailMessage)
            ->subject('Alerta de Preço: ' . $this->product->name)
            ->greeting('Olá!')
            ->line($message)
            ->action('Ver Produto', route('product.show', ['id' => $this->product->id, 'slug' => $this->product->permalink]))
            ->line('Obrigado por usar nosso serviço!')
            ->salutation('Atenciosamente,')
            ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_price' => $this->product->price,
            'target_price' => $this->wish->target_price,
        ];
    }
}
