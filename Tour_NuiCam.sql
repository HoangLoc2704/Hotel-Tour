

SET NAMES utf8mb4;
-- ================= CHUC VU =================
CREATE TABLE tbl_ChucVu (
	MaCV INT AUTO_INCREMENT PRIMARY KEY,
	TenCV VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================= NHAN VIEN =================
CREATE TABLE tbl_NhanVien (
	MaNV INT AUTO_INCREMENT PRIMARY KEY,
	TenNV VARCHAR(50) NOT NULL,
	GioiTinh BOOLEAN DEFAULT 1,
	NgaySinh DATE,
	DiaChi VARCHAR(255),
	SDT VARCHAR(10),
	TenTK VARCHAR(100) NOT NULL UNIQUE,
	MatKhau VARCHAR(255) NOT NULL,
	Email VARCHAR(100) UNIQUE,
	TrangThai BOOLEAN DEFAULT 1,
	MaCV INT NOT NULL,
	FOREIGN KEY (MaCV) REFERENCES tbl_ChucVu(MaCV)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================= KHACH HANG =================
CREATE TABLE tbl_KhachHang (
	MaKH INT AUTO_INCREMENT PRIMARY KEY,
	TenKH VARCHAR(50) NOT NULL,
	GioiTinh BOOLEAN DEFAULT 1,
	SDT VARCHAR(10) NOT NULL,
	MatKhau VARCHAR(255),
	TrangThai BOOLEAN DEFAULT 1,
	Email VARCHAR(100) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================= LOAI PHONG =================
CREATE TABLE tbl_LoaiPhong(
	MaLoai INT AUTO_INCREMENT PRIMARY KEY,
	TenLoai VARCHAR(50) NOT NULL,
	GiaPhong DOUBLE,
	SoLuongNguoi INT,
	HinhAnh VARCHAR(255),
	MoTa VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================= PHONG =================
CREATE TABLE tbl_Phong (
	MaPhong int AUTO_INCREMENT PRIMARY KEY,
	TenPhong VARCHAR(10),
	MaLoai INT NOT NULL,
	FOREIGN KEY (MaLoai) REFERENCES tbl_LoaiPhong(MaLoai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE tbl_HuongDanVien(
    MaHDV INT AUTO_INCREMENT PRIMARY KEY,
    TenHDV VARCHAR(50) NOT NULL,
	NgaySinh DATE,
	DiaChi VARCHAR(255) NOT NULL,
 	SDT VARCHAR(10) NOT NULL,
    TrangThai BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



	CREATE TABLE tbl_HoaDon (
		MaHD INT AUTO_INCREMENT PRIMARY KEY,
		MaKH INT NOT NULL,
		NgayTao Date,
		ThanhTien Double,
		TrangThai BOOLEAN default 0,
		ThanhToan BOOLEAN default 0,
		FOREIGN KEY (MaKH) REFERENCES tbl_KhachHang(MaKH)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


	CREATE TABLE tbl_TOUR (
		MaTour VARCHAR(20) PRIMARY KEY,
		TenTour VARCHAR(100) NOT NULL,
		GiaTourNguoiLon DOUBLE,
		GiaTourTreEm DOUBLE,
		ThoiLuong INT NOT NULL,
		DiaDiemKhoiHanh VARCHAR(255),
		SoLuongKhachToiDa INT,
		HinhAnh VARCHAR(255),
		MoTa VarChar(255),
		LichTrinh VARCHAR (255),
		TrangThai BOOLEAN default 1
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


	CREATE TABLE tbl_LichKhoiHanh (
		MaLKH INT AUTO_INCREMENT PRIMARY KEY,
		MaTour VARCHAR(20) NOT NULL,
		NgayKhoiHanh DATE,
		NgayKetThuc DATE,
		SoChoConLai INT,
		MaHDV INT NOT NULL,
		TaiXe VARCHAR (100),
		PhuongTien VARCHAR(100), 
		FOREIGN KEY (MaHDV) REFERENCES tbl_HuongDanVien(MaHDV),
		FOREIGN KEY (MaTour) REFERENCES tbl_TOUR(MaTour)
	)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


	CREATE TABLE tbl_HDTOUR (
		MaHD INT NOT NULL,
		MaLKH INT NOT NULL,
		SoNguoiLon INT,
		SoTreEm INT,
		TongTien DOUBLE,
		TrangThai BOOLEAN default 1,
		ThanhToan BOOLEAN DEFAULT 0,
		PRIMARY KEY (MaHD, MaLKH),
		FOREIGN KEY (MaLKH) REFERENCES tbl_LichKhoiHanh(MaLKH),
		FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



	CREATE TABLE tbl_DichVu(
		MaDV INT AUTO_INCREMENT PRIMARY KEY,
		TenDV VARCHAR(50) NOT NULL,
		GiaDV DOUBLE,
		TrangThai BOOLEAN DEFAULT 1
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	CREATE TABLE tbl_HDDichVu(
		MaHD INT NOT NULL,
		MaDV INT NOT NULL,
		SoLuong INT,
		NgaySuDung Date,
		TongTien DOUBLE,
		TrangThai BOOLEAN DEFAULT 1,
		ThanhToan BOOLEAN DEFAULT 0,
		PRIMARY KEY (MaHD, MaDV),
		FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD),
		FOREIGN KEY (MaDV) REFERENCES tbl_DichVu(MaDV)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	CREATE TABLE tbl_HDPhong(
		MaHD INT NOT NULL,
		MaPhong Int NOT NULL,
		NgayNhanPhong Date,
		NgayTraPhong Date,
		TongTien DOUBLE,
		TrangThai BOOLEAN DEFAULT 1,
		ThanhToan BOOLEAN DEFAULT 0,
		PRIMARY KEY (MaHD, MaPhong),
		FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD),
		FOREIGN KEY (MaPhong) REFERENCES tbl_Phong(MaPhong)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	CREATE TABLE tbl_AnhPhong(
		MaAP INT AUTO_INCREMENT PRIMARY KEY,
		MaLoai Int NOT NULL,
		HinhAnh VARCHAR(255),
		FOREIGN KEY (MaLoai) REFERENCES tbl_LoaiPhong(MaLoai)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	CREATE TABLE tbl_AnhTour(
		MaAT INT AUTO_INCREMENT PRIMARY KEY,
		MaTour varchar(20) NOT NULL,
		HinhAnh VARCHAR(255),
		FOREIGN KEY (MaTour) REFERENCES tbl_TOUR(MaTour)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	CREATE TABLE tbl_AnhDichVu(
		MaADV INT AUTO_INCREMENT PRIMARY KEY,
		MaDV int NOT NULL,
		HinhAnh VARCHAR(255),
		FOREIGN KEY (MaDV) REFERENCES tbl_DichVu(MaDV)

	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




INSERT INTO tbl_ChucVu (TenCV) VALUES
('Quản lý'),
('Nhân viên lễ tân'),
('Nhân viên bán tour');


INSERT INTO tbl_NhanVien
(TenNV, GioiTinh, NgaySinh, DiaChi, SDT, TenTK, MatKhau, Email, MaCV)
VALUES
('Nguyễn Văn A',1,'1995-05-10','An Giang','0911111111','nva','$2y$10$wG3uJX2Y6v1rYFQn8zM1F.1Z8j4z5QmYvZyQZrYw1cZk2p0JYpK9G','a@gmail.com',1),
('Trần Thị B',0,'1998-03-15','Cần Thơ','0922222222','ttb','Ttb@123','b@gmail.com',2),
('Lê Văn C',1,'1993-07-20','Đồng Tháp','0933333333','lvc','Lvc@123','c@gmail.com',3);


-- ================= LOAI PHONG =================
INSERT INTO tbl_LoaiPhong (TenLoai, GiaPhong, SoLuongNguoi, HinhAnh, MoTa) VALUES
-- PHÒNG ĐƠN
('Phòng đơn',500000,2,'Don1.jpg','hòng đơn nhà tranh dành cho tối đa 2 người, thiết kế mộc mạc, gần gũi thiên nhiên. Trang bị giường đôi, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng nước nóng.'),

-- PHÒNG ĐƠN - VIEW
('Phòng đơn View',800000,2,'DonView1.jpg','Phòng đơn view đẹp cho 2 người, không gian thoáng với cửa sổ lớn nhìn ra cảnh quan. Trang bị giường đôi, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng tiện nghi.'),

-- PHÒNG ĐÔI
('Phòng đôi',900000,4,'Doi1.jpg','Phòng đôi cho 4 người, không gian rộng rãi, thiết kế truyền thống. Trang bị 2 giường lớn, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng.'),

-- PHÒNG ĐÔI - VIEW
('Phòng đôi View',1300000,4,'DoiView1.jpg','Phòng đôi view đẹp cho 4 người, có cửa sổ hoặc ban công nhìn ra thiên nhiên. Trang bị 2 giường lớn, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng.'),

-- PHÒNG GIA ĐÌNH 
('Phòng gia đình',1200000,6,'GD1.jpg','Phòng gia đình cho 6 người, không gian rộng, gần gũi thiên nhiên. Trang bị nhiều giường, máy lạnh, quạt, tivi, WiFi và phòng tắm riêng.'),

-- PHÒNG GIA ĐÌNH - VIEW
('Phòng gia đình View',1800000,6,'GDView1.jpg','Phòng gia đình view đẹp cho 6 người, không gian thoáng, tầm nhìn đẹp. Trang bị giường ngủ, máy lạnh, tivi, WiFi, minibar và phòng tắm riêng hiện đại.');

-- ================= PHONG =================
INSERT INTO tbl_Phong (TenPhong, MaLoai) VALUES

-- PHÒNG ĐƠN (MaLoai = 1)
('P101',1),('P102',1),('P103',1),('P104',1),('P105',1),('P106',1),('P107',1),

-- PHÒNG ĐƠN VIEW (MaLoai = 2)
('P108',2),('P109',2),('P110',2),

-- PHÒNG ĐÔI  (MaLoai = 3)
('P201',3),('P202',3),('P203',3),('P204',3),('P205',3),('P206',3),('P207',3),

-- PHÒNG ĐÔI VIEW (MaLoai = 4)
('P208',4),('P209',4),('P210',4),

-- PHÒNG GIA ĐÌNH  (MaLoai = 5)
('P301',5),('P302',5),('P303',5),('P304',5),('P305',5),('P306',5),('P307',5),

-- PHÒNG GIA ĐÌNH VIEW (MaLoai = 6)
('P308',6),('P309',6),('P310',6);

-- ================= HUONG DAN VIEN =================
INSERT INTO tbl_HuongDanVien (TenHDV, NgaySinh, DiaChi, SDT, TrangThai) VALUES
('Nguyễn Văn Hùng','1990-05-12','An Giang','0901234567',1),
('Trần Thị Mai','1995-08-20','Cần Thơ','0912345678',1),
('Lê Quốc Bảo','1988-03-15','Long Xuyên','0987654321',1);

-- ================= TOUR =================
INSERT INTO tbl_TOUR
(MaTour, TenTour, GiaTourNguoiLon, GiaTourTreEm, ThoiLuong, DiaDiemKhoiHanh, SoLuongKhachToiDa, HinhAnh, MoTa, LichTrinh)
VALUES
('T001','Tour Núi Cấm 1 ngày',500000,300000,1,'Long Xuyên',30,'tour1.jpg','Du lịch Núi Cấm','LX - Núi Cấm'),
('T002','Tour Núi Cấm lễ 30/4',550000,350000,1,'Châu Đốc',30,'tour2.jpg','Du lịch lễ','CD - Núi Cấm');

-- ================= LICH KHOI HANH =================
INSERT INTO tbl_LichKhoiHanh
(MaTour, NgayKhoiHanh, NgayKetThuc, SoChoConLai, MaHDV, TaiXe, PhuongTien)
VALUES
('T001','2026-06-01','2026-06-02',30,1,'Nguyễn Văn A','Xe 29 chỗ'),
('T002','2026-04-30','2026-05-01',30,2,'Trần Văn B','Xe 45 chỗ');

-- ================= DICH VU =================
INSERT INTO tbl_DichVu (TenDV, GiaDV) VALUES
('Ăn sáng',50000),
('Ăn trưa',100000);

-- ================anh=============
INSERT INTO tbl_AnhPhong (MaLoai, HinhAnh)
VALUES 
('1', 'Don1.jpg'),
('1', 'Don2.jpg'),
('1', 'Don3jpg'),
('1', 'Don4.jpg'),
('1', 'Don5.jpg'),

('2', 'DonView1.jpg'),
('2', 'DonView2.jpg'),
('2', 'DonView3jpg'),
('2', 'DonView4.jpg'),
('2', 'DonView5.jpg'),
('2', 'DonView6.jpg'),

('3', 'Doi1.jpg'),
('3', 'Doi2.jpg'),
('3', 'Doi3jpg'),
('3', 'Doi4.jpg'),
('3', 'Doi5.jpg'),
('3', 'Doi6.jpg'),

('4', 'DoiView1.jpg'),
('4', 'DoiView2.jpg'),
('4', 'DoiView3jpg'),
('4', 'DoiView4.jpg'),
('4', 'DoiView5.jpg'),

('5', 'GD1.jpg'),
('5', 'GD2.jpg'),
('5', 'GD3.jpg'),
('5', 'GD4.jpg'),
('5', 'GD5.jpg'),
('5', 'GD6.jpg'),

('6', 'GDView1.jpg'),
('6', 'GDView2.jpg'),
('6', 'GDView3.jpg'),
('6', 'GDView4.jpg'),
('6', 'GDView5.jpg');

INSERT INTO tbl_AnhDichVu (MaDV, HinhAnh)
VALUES 
('1', 'DichVu_1_1.jpg'),
('1', 'DichVu_1_2.jpg'),
('1', 'DichVu_1_3.jpg'),

('2', 'DichVu_1_1.jpg'),
('2', 'DichVu_2_2.jpg'),
('2', 'DichVu_2_3.jpg');

INSERT INTO tbl_AnhTour (MaTour, HinhAnh)
VALUES 
('T001', 'TourNuiCam1.jpg'),
('T001', 'TourNuiCam2.jpg'),
('T001', 'TourNuiCam3.jpg'),
('T001', 'TourNuiCam4.jpg'),
('T001', 'TourNuiCam5.jpg'),

('T002', 'Tour30_4_1.jpg'),
('T002', 'Tour30_4_2.jpg'),
('T002', 'Tour30_4_3.jpg'),
('T002', 'Tour30_4_4.jpg'),
('T002', 'Tour30_4_5.jpg');

