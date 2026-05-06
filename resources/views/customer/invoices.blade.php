@extends('customer.layout.main')

@section('title', 'Hóa đơn của tôi')

@php
    $formatDate = fn ($value) => filled($value) ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

@section('content')
    <main class="container py-5">
        <section>
            <div class="booking-wrap">
                <div class="section-title-wrap mb-3">
                    <h2>Hóa đơn của tôi</h2>
                    <p>Danh sách hóa đơn tương ứng với tài khoản khách hàng đang đăng nhập.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @include('customer.partials.profile-card', ['customerProfile' => $customerProfile])

                <form method="GET" action="{{ route('customer.invoices') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="thanh_toan" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" @selected(($filters['thanh_toan'] ?? '') === '1')>Đã thanh toán</option>
                            <option value="0" @selected(($filters['thanh_toan'] ?? '') === '0')>Chưa thanh toán</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-book flex-grow-1">Lọc</button>
                        <a href="{{ route('customer.invoices') }}" class="btn btn-outline-secondary">Xóa</a>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($hoaDons->isEmpty())
                    <div class="alert alert-info mb-0">Bạn chưa có hóa đơn nào.</div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($hoaDons as $hoaDon)
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white d-flex flex-wrap justify-content-between gap-2">
                                    <div>
                                        <strong>Mã hóa đơn:</strong> #{{ $hoaDon->MaHD }}
                                        <span class="ms-3"><strong>Ngày tạo:</strong> {{ $formatDate($hoaDon->NgayTao) }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge text-bg-{{ (int) $hoaDon->TrangThai === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $hoaDon->TrangThai === 1 ? 'Hoạt động' : 'Vô hiệu' }}
                                        </span>
                                        <span class="badge text-bg-{{ (int) $hoaDon->ThanhToan === 1 ? 'success' : 'warning' }}">
                                            {{ (int) $hoaDon->ThanhToan === 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Tổng tiền:</strong>
                                        {{ number_format((float) $hoaDon->ThanhTien, 0, ',', '.') }} VND
                                    </div>

                                    <div class="mb-3 d-flex flex-wrap gap-2">
                                        <a href="{{ route('customer.invoices.show', $hoaDon->MaHD) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>

                                        @if ((int) $hoaDon->TrangThai === 1)
                                            <form method="POST" action="{{ route('customer.invoices.cancel', $hoaDon->MaHD) }}" onsubmit="return confirm('Bạn có chắc muốn hủy hóa đơn này không?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Hủy đơn</button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Đã vô hiệu</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $hoaDons->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection