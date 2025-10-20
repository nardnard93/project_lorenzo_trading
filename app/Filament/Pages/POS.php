<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use BackedEnum;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Icon;

class POS extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected string $view = 'filament.pages.p-o-s';
    protected static ?string $navigationLabel = 'Point of Sale';
    protected static ?string $title = 'Point of Sale';

    public $cart = [];
    public $search = '';
    public $products = [];
    public $total = 0;
    public $showCheckoutModal = false;
    public $paymentMethod = 'cash';

    public function mount(): void
    {
        $this->products = Product::all();
    }

    public function addToCart($productId, $quantity = 1): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        $quantity = max(1, (int) $quantity);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] += $quantity;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        $this->calculateTotal();
    }

    public function removeFromCart($productId): void
    {
        unset($this->cart[$productId]);
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function openCheckoutModal($method = 'cash'): void
    {
        $this->paymentMethod = $method;
        $this->showCheckoutModal = true;
    }

    public function confirmCheckout(): void
    {
        DB::transaction(function () {
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('YmdHis'),
                'total_amount' => $this->total,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => $this->paymentMethod,
                'order_date' => now(),
            ]);

            foreach ($this->cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                Product::where('id', $productId)->decrement('current_stock', $item['quantity']);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $this->total,
                'method' => $this->paymentMethod,
                'status' => 'completed',
                'paid_at' => now(),
            ]);
        });

        Notification::make()
            ->title('Order Completed Successfully!')
            ->success()
            ->send();

        $this->resetCart();
        $this->showCheckoutModal = false;
    }

    public function resetCart(): void
    {
        $this->cart = [];
        $this->total = 0;
    }
}
