<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Override;
use Tests\TestCase;

class ProductUpdateApiTest extends TestCase
{   

    use RefreshDatabase;

    private Product $product;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create();
    }

    /**
     * Teste para o endpoint de Preço
     */
    public function test_price_update_is_accepted_with_valid_data(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/price', ['price' => 199.99])
            ->assertStatus(202)
            ->assertJsonPath('status', 'success');
    }

    public function test_price_update_fails_with_invalid_data(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/price', ['price' => -10])
            ->assertStatus(422);
    }

    /**
     * Teste para o endpoint de Estoque
     */
    public function test_stock_update_is_accepted_with_valid_data(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/stock', ['stock' => 50])
            ->assertStatus(202);
    }

    public function test_stock_update_fails_with_decimal_value(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/stock', ['stock' => 10.5])
            ->assertStatus(422);
    }

    /**
     * Teste para o endpoint de Descrição
     */
    public function test_description_update_is_accepted_with_valid_data(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/description', ['description' => 'Descrição detalhada do produto.'])
            ->assertStatus(202);
    }

    /**
     * Teste para o endpoint de Imagens
     */
    public function test_images_update_is_accepted_with_valid_data(): void
    {
        $payload = [
            'images' => [
                'https://hub-irroba.com/storage/products/1.jpg',
                'https://hub-irroba.com/storage/products/2.jpg'
            ]
        ];

        $this->patchJson('/api/products/{$this->product->sku}/images', $payload)
            ->assertStatus(202);
    }

    public function test_images_update_fails_if_not_an_array(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/images', ['images' => 'https://link.com/imagem.jpg'])
            ->assertStatus(422);
    }

    /**
     * Teste para o endpoint de Tags
     */
    public function test_tags_update_is_accepted_with_valid_data(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/tags', ['tags' => ['eletronicos', 'oferta']])
            ->assertStatus(202);
    }

    public function test_tags_update_fails_with_invalid_characters(): void
    {
        $this->patchJson('/api/products/{$this->product->sku}/tags', ['tags' => ['tag com espaço']])
            ->assertStatus(422);
    }
}