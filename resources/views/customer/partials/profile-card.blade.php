@if ($customerProfile)
    <div class="card border-0 shadow-sm mb-4" id="customer-profile">
        <div class="card-header bg-white">
            <h5 class="mb-1">Thông tin khách hàng</h5>
            <p class="text-muted small mb-0">Cập nhật họ tên, giới tính và đổi mật khẩu nếu cần.</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('customer.profile.update') }}" class="row g-3">
                @csrf
                @method('PATCH')

                <div class="col-md-6">
                    <label for="profileHoTen" class="form-label">Họ tên</label>
                    <input
                        type="text"
                        id="profileHoTen"
                        name="ho_ten"
                        class="form-control @error('ho_ten') is-invalid @enderror"
                        value="{{ old('ho_ten', $customerProfile->TenKH ?? '') }}"
                        required
                    >
                    @error('ho_ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="profileGioiTinh" class="form-label">Giới tính</label>
                    @php
                        $selectedGender = (string) old('gioi_tinh', isset($customerProfile->GioiTinh) ? (int) $customerProfile->GioiTinh : 1);
                    @endphp
                    <select id="profileGioiTinh" name="gioi_tinh" class="form-select @error('gioi_tinh') is-invalid @enderror" required>
                        <option value="1" {{ $selectedGender === '1' ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ $selectedGender === '0' ? 'selected' : '' }}>Nữ</option>
                    </select>
                    @error('gioi_tinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">SĐT</label>
                    <input type="text" class="form-control" value="{{ $customerProfile->SDT ?? '' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $customerProfile->Email ?? '' }}" readonly>
                </div>

                <div class="col-md-3">
                    <label for="profilePassword" class="form-label">Mật khẩu mới</label>
                    <input
                        type="password"
                        id="profilePassword"
                        name="mat_khau"
                        class="form-control @error('mat_khau') is-invalid @enderror"
                        placeholder="Để trống nếu không đổi"
                    >
                    @error('mat_khau')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="profilePasswordConfirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input
                        type="password"
                        id="profilePasswordConfirmation"
                        name="mat_khau_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                    >
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-book">Cập nhật thông tin</button>
                </div>
            </form>
        </div>
    </div>
@endif
