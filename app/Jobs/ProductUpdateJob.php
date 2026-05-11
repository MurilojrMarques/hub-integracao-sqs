<?php

namespace App\Jobs;

use App\Models\Product;
use App\Enums\ProductUpdateType;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProductUpdateJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    public $tries = 3;
    public $backoff = [60, 300, 600];

    public function __construct(
        public string $sku,
        public ProductUpdateType $type,
        public array $data
    ) {}

    public function handle(): void
    {
        $product = Product::where('sku', $this->sku)->first();

        if (!$product) {
            throw new \Exception("O produto não foi encontrado."); 
        }

        match($this->type) {
            ProductUpdateType::PRICE => $product->price = $this->data['price'],
            ProductUpdateType::STOCK => $product->stock = $this->data['stock'], 
            ProductUpdateType::DESCRIPTION => $product->description = $this->data['description'],
            ProductUpdateType::IMAGES => $product->images = $this->data['images'],
            ProductUpdateType::TAGS => $product->tags = $this->data['tags'],
        };

        $product->save();
    }

    public function uniqueId(): string
    {
        return $this->sku . '-' . $this->type->value . '-' . md5(json_encode($this->data));
    }
}