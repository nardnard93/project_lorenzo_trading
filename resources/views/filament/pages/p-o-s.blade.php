<x-filament-panels::page>
    <div class="space-y-6">

        {{-- 🔍 Search Bar --}}
        <div class="flex justify-between items-center">
            <x-filament::input.wrapper class="w-1/3">
                <x-filament::input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search products..."
                    class="w-full" />
            </x-filament::input.wrapper>
        </div>

        {{-- 🛒 Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($products->filter(fn($p) => str_contains(strtolower($p->name), strtolower($search))) as $product)
            <x-filament::card>
                <div class="space-y-3">
                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">₱{{ number_format($product->price, 2) }}</p>
                    <div class="flex items-center gap-2">
                        <x-filament::input
                            type="number"
                            wire:model.defer="cart.{{ $product->id }}.temp_quantity"
                            placeholder="Qty"
                            min="1"
                            class="w-20" />
                        <x-filament::button
                            color="primary"
                            wire:click="addToCart({{ $product->id }}, {{ $cart[$product->id]['temp_quantity'] ?? 1 }})">
                            Add
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::card>
            @endforeach
        </div>

        {{-- 🧾 Cart Summary --}}
        <div class="bg-white shadow-md rounded-xl p-6 space-y-4">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <x-filament::icon name="heroicon-o-shopping-cart" class="w-6 h-6 text-primary-500" />
                Cart Summary
            </h2>

            @if (count($cart) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $productId => $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item['name'] }}</td>
                        <td class="px-4 py-2">
                            <x-filament::input
                                type="number"
                                min="1"
                                wire:model.lazy="cart.{{ $productId }}.quantity"
                                wire:change="calculateTotal"
                                class="w-20" />
                        </td>
                        <td class="px-4 py-2">₱{{ number_format($item['price'], 2) }}</td>
                        <td class="px-4 py-2">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td class="px-4 py-2 text-right">
                            <x-filament::button color="danger" size="sm" wire:click="removeFromCart({{ $productId }})">
                                Remove
                            </x-filament::button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 💰 Total --}}
            <div class="flex justify-end mt-4">
                <h3 class="text-xl font-bold">
                    Total: ₱{{ number_format($total, 2) }}
                </h3>
            </div>

            {{-- 💳 Checkout Buttons --}}
            <div class="flex justify-end gap-2 mt-4">
                <x-filament::button color="gray" wire:click="openCheckoutModal('cash')">
                    Pay by Cash
                </x-filament::button>

                <x-filament::button color="success" wire:click="openCheckoutModal('gcash')">
                    Pay by GCash
                </x-filament::button>
            </div>
            @else
            <p class="text-gray-500 italic">Your cart is empty.</p>
            @endif
        </div>
    </div>

    {{-- 🪟 Checkout Confirmation Modal --}}
    <x-filament::modal wire:model="showCheckoutModal" width="lg">
        <x-slot name="title">Confirm Checkout</x-slot>

        <div class="space-y-4">
            <p class="text-gray-600">
                Please confirm your payment for this order.
            </p>

            <div class="border-t pt-3 space-y-2">
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total:</span>
                    <span>₱{{ number_format($total, 2) }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Payment Method:</span>
                    <span class="capitalize font-medium">{{ $paymentMethod }}</span>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-filament::button color="gray" wire:click="$set('showCheckoutModal', false)">
                    Cancel
                </x-filament::button>

                <x-filament::button color="success" wire:click="confirmCheckout">
                    Confirm Payment
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>