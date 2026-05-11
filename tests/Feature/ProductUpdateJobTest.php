<?php

namespace Tests\Feature\Jobs;

use App\Enums\ProductUpdateType;
use App\Jobs\ProductUpdateJob;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductUpdateJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_updates_product_price_successfully(): void
    {
        $product = Product::factory()->create(['price' => 100.00]);
        $newPrice = 149.99;

        $job = new ProductUpdateJob(
            $product->sku, 
            ProductUpdateType::PRICE, 
            ['price' => $newPrice]
        );
        $job->handle();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => $newPrice,
        ]);
    }

    public function test_job_throws_exception_if_product_not_found(): void
    {
        $skuInvalido = 'SKU-INVALIDO';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("O produto não foi encontrado.");

        $job = new ProductUpdateJob(
            $skuInvalido, 
            ProductUpdateType::PRICE, 
            ['price' => 149.99]
        );
        
        $job->handle(); 
    }

    public function test_job_generates_correct_unique_id_for_idempotency(): void
    {
        $sku = 'SKU-1234';
        $type = ProductUpdateType::STOCK;
        $data = ['stock' => 50];
        $job = new ProductUpdateJob($sku, $type, $data);
        $expectedId = "{$sku}-{$type->value}-" . md5(json_encode($data));

        $this->assertEquals($expectedId, $job->uniqueId());
    }
}