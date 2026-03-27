<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    public function handle(Request $request, Closure $next, string ...$allowedRoles): Response
    {
        $currentRole = $this->normalizeRole(Session::get('user_role_name'));

        $normalizedAllowedRoles = array_map(function (string $role): string {
            return $this->normalizeRole($role);
        }, $allowedRoles);

        if (!in_array($currentRole, $normalizedAllowedRoles, true)) {
            return redirect()->route('admin')->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        return $next($request);
    }

    private function normalizeRole(?string $roleName): string
    {
        if ($roleName === null) {
            return '';
        }

        $normalized = mb_strtolower(trim($roleName));

        $map = [
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
            'đ' => 'd',
        ];

        $normalized = strtr($normalized, $map);
        $normalized = preg_replace('/[^a-z0-9]+/u', '-', $normalized) ?? '';
        $normalized = trim($normalized, '-');

        // Allow both display names and compact aliases in route middleware.
        return match ($normalized) {
            'quan-ly', 'manager' => 'quan-ly',
            'nhan-vien-le-tan', 'le-tan', 'receptionist' => 'le-tan',
            'nhan-vien-tour', 'tour', 'tour-staff' => 'nhan-vien-tour',
            default => $normalized,
        };
    }
}
