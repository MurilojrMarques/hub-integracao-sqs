<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\ProductUpdateType;
use App\Http\Requests\UpdatePriceRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Requests\UpdateDescriptionRequest;
use App\Http\Requests\UpdateImagesRequest;
use App\Http\Requests\UpdateTagsRequest;
use Illuminate\Http\JsonResponse;

class ProductUpdateController extends Controller
{
    public function updatePrice(UpdatePriceRequest $request, string $sku): JsonResponse
    {
        return $this->dispatchUpdate($sku, ProductUpdateType::PRICE, $request->validated());
    }

    public function updateStock(UpdateStockRequest $request, string $sku): JsonResponse
    {
        return $this->dispatchUpdate($sku, ProductUpdateType::STOCK, $request->validated());
    }

    public function updateDescription(UpdateDescriptionRequest $request, string $sku): JsonResponse
    {
        return $this->dispatchUpdate($sku, ProductUpdateType::DESCRIPTION, $request->validated());
    }

    public function updateImages(UpdateImagesRequest $request, string $sku): JsonResponse
    {
        return $this->dispatchUpdate($sku, ProductUpdateType::IMAGES, $request->validated());
    }

    public function updateTags(UpdateTagsRequest $request, string $sku): JsonResponse
    {
        return $this->dispatchUpdate($sku, ProductUpdateType::TAGS, $request->validated());
    }

    private function dispatchUpdate(string $sku, ProductUpdateType $type, array $data): JsonResponse
    {
        // ProductUpdateJob::dispatch($sku, $type, $data);

        return response()->json([
            'status' => 'success',
            'message' => "Solicitação de atualização de {$type->value} aceita e enviada para a fila.",
            'data' => [
                'sku' => $sku,
                'type' => $type->value,
                'payload' => $data
            ]
        ], 202);
    }
}
