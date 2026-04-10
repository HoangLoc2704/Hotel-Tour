<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $hoaDon->MaHD }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            margin: 0;
            color: #1f2937;
        }

        .toolbar {
            max-width: 960px;
            margin: 20px auto 0;
            display: flex;
            gap: 10px;
        }

        .toolbar a,
        .toolbar button {
            border: none;
            background: #2563eb;
            color: #fff;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .toolbar a.secondary {
            background: #6b7280;
        }

        .invoice-sheet {
            max-width: 960px;
            margin: 16px auto 32px;
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted {
            color: #6b7280;
            margin: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 24px;
            margin-bottom: 20px;
        }

        .info-item {
            background: #f9fafb;
            padding: 10px 12px;
            border-radius: 8px;
        }

        .section-title {
            margin: 22px 0 10px;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
        }

        .text-end {
            text-align: right;
        }

        .summary {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }

        .summary-box {
            min-width: 280px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 16px;
        }

        .summary-box h3 {
            margin: 0 0 10px;
        }

        @media print {
            body {
                background: #fff;
            }

            .toolbar {
                display: none !important;
            }

            .invoice-sheet {
                margin: 0;
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $formatDate = fn ($value) => filled($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
    @endphp

    <div class="toolbar">
        <button type="button" onclick="window.print()">In / Lưu PDF</button>
        <a href="{{ route('hoa-don.index') }}" class="secondary">Quay lại</a>
    </div>

    <div class="invoice-sheet">
        <div class="invoice-header">
            <div>
                <h1>HÓA ĐƠN #{{ $hoaDon->MaHD }}</h1>
                <p class="muted">Ngày tạo: {{ $formatDate($hoaDon->NgayTao) }}</p>
            </div>
            <div>
                <p><strong>Trạng thái:</strong> {{ $hoaDon->TrangThai ? 'Hoạt động' : 'Vô hiệu' }}</p>
                <p><strong>Thanh toán:</strong> {{ $hoaDon->ThanhToan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item"><strong>Khách hàng:</strong> {{ $hoaDon->khachHang->TenKH ?? 'N/A' }}</div>
            <div class="info-item"><strong>SĐT:</strong> {{ $hoaDon->khachHang->SDT ?? 'N/A' }}</div>
            <div class="info-item"><strong>Email:</strong> {{ $hoaDon->khachHang->Email ?? 'N/A' }}</div>
            <div class="info-item"><strong>Tổng tiền:</strong> {{ number_format((float) $hoaDon->ThanhTien, 0, ',', '.') }} VND</div>
        </div>

        @if ($hoaDon->hdPhongs->isNotEmpty())
            <h3 class="section-title">Chi tiết phòng</h3>
            <table>
                <thead>
                    <tr>
                        <th>Mã phòng</th>
                        <th>Tên phòng</th>
                        <th>Nhận phòng</th>
                        <th>Trả phòng</th>
                        <th class="text-end">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDon->hdPhongs as $item)
                        <tr>
                            <td>{{ $item->MaPhong }}</td>
                            <td>{{ $item->phong->TenPhong ?? '-' }}</td>
                            <td>{{ $formatDate($item->NgayNhanPhong) }}</td>
                            <td>{{ $formatDate($item->NgayTraPhong) }}</td>
                            <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($hoaDon->hdDichVus->isNotEmpty())
            <h3 class="section-title">Chi tiết dịch vụ</h3>
            <table>
                <thead>
                    <tr>
                        <th>Mã DV</th>
                        <th>Tên dịch vụ</th>
                        <th>Số lượng</th>
                        <th>Ngày sử dụng</th>
                        <th class="text-end">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDon->hdDichVus as $item)
                        <tr>
                            <td>{{ $item->MaDV }}</td>
                            <td>{{ $item->dichVu->TenDV ?? '-' }}</td>
                            <td>{{ $item->SoLuong }}</td>
                            <td>{{ $formatDate($item->NgaySuDung) }}</td>
                            <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($hoaDon->hdTours->isNotEmpty())
            <h3 class="section-title">Chi tiết tour</h3>
            <table>
                <thead>
                    <tr>
                        <th>Mã lịch</th>
                        <th>Tên tour</th>
                        <th>Khởi hành</th>
                        <th>Kết thúc</th>
                        <th>Người lớn</th>
                        <th>Trẻ em</th>
                        <th class="text-end">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDon->hdTours as $item)
                        <tr>
                            <td>{{ $item->MaLKH }}</td>
                            <td>{{ $item->lichKhoiHanh->tour->TenTour ?? '-' }}</td>
                            <td>{{ $formatDate($item->lichKhoiHanh->NgayKhoiHanh ?? null) }}</td>
                            <td>{{ $formatDate($item->lichKhoiHanh->NgayKetThuc ?? null) }}</td>
                            <td>{{ (int) $item->SoNguoiLon }}</td>
                            <td>{{ (int) $item->SoTreEm }}</td>
                            <td class="text-end">{{ number_format((float) $item->TongTien, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="summary">
            <div class="summary-box">
                <h3>Tổng thanh toán</h3>
                <div><strong>{{ number_format((float) $hoaDon->ThanhTien, 0, ',', '.') }} VND</strong></div>
            </div>
        </div>
    </div>
</body>
</html>
