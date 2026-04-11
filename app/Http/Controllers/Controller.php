<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function wantsJson(Request $request): bool
    {
        return $request->expectsJson() || $request->wantsJson() || $request->is('api/*');
    }

    protected function imageUploadMaxKb(): int
    {
        return max((int) config('uploads.image_max_kb', 900), 1);
    }

    protected function imageUploadLimitLabel(): string
    {
        $maxKb = $this->imageUploadMaxKb();

        if ($maxKb >= 1024) {
            $maxMb = $maxKb / 1024;
            $formatted = rtrim(rtrim(number_format($maxMb, 2, '.', ''), '0'), '.');

            return $formatted . ' MB';
        }

        return $maxKb . ' KB';
    }

    protected function imageUploadValidationMessages(): array
    {
        $label = $this->imageUploadLimitLabel();

        return [
            'image_file.image' => 'Vui lòng chọn đúng tệp hình ảnh.',
            'image_file.max' => 'Ảnh tải lên không được vượt quá ' . $label . '.',
            'image_file.required_without' => 'Vui lòng chọn ảnh để tải lên hoặc nhập tên ảnh.',
        ];
    }

    protected function jsonSuccess(mixed $data = null, ?string $message = null, int $status = 200, array $meta = []): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function jsonPaginated(LengthAwarePaginator $paginator, ?string $message = null): JsonResponse
    {
        return $this->jsonSuccess(
            $paginator->items(),
            $message,
            200,
            [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        );
    }
}