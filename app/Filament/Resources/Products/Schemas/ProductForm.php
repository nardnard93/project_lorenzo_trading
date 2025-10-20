<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2) // Two-column layout for better design
            ->components([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->label('SKU/Barcode')
                    ->required()
                    ->maxLength(100),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Selling Price')
                    ->numeric()
                    ->required(),

                TextInput::make('cost_price')
                    ->label('Cost Price')
                    ->numeric(),

            // ✅ Store image as BLOB instead of file path
            FileUpload::make('image')
                ->label('Product Image')
                ->image()
                ->directory('temp-products')
                ->visibility('public')
                ->maxSize(2048),

                TextInput::make('current_stock')
                    ->label('Current Stock')
                    ->numeric()
                    ->required()
                    ->default(0),

                TextInput::make('min_stock_level')
                    ->label('Minimum Stock Level')
                    ->numeric()
                    ->required()
                    ->default(0),
            ]);
    }
}
