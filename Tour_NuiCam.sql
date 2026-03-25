

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
	TenLoai VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================= PHONG =================
CREATE TABLE tbl_Phong (
	MaPhong VARCHAR(10) PRIMARY KEY,
	TenPhong VARCHAR(100),
	GiaPhong DOUBLE,
	SoLuongNguoi INT,
	HinhAnh VARCHAR(255),
	MoTa VARCHAR(255),
	MaLoai INT NOT NULL,
	FOREIGN KEY (MaLoai) REFERENCES tbl_LoaiPhong(MaLoai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================= HUONG DAN VIEN =================
CREATE TABLE tbl_HuongDanVien(
    MaHDV INT AUTO_INCREMENT PRIMARY KEY,
    TenHDV VARCHAR(50) NOT NULL,
	NgaySinh DATE,
	DiaChi VARCHAR(255) NOT NULL,
 	SDT VARCHAR(10) NOT NULL,
    TrangThai BOOLEAN DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================= HOA DON =================
CREATE TABLE tbl_HoaDon (
	MaHD INT AUTO_INCREMENT PRIMARY KEY,
	MaKH INT NOT NULL,
	NgayTao Date,
	ThanhTien Double,
	TrangThai BOOLEAN default 1,
	FOREIGN KEY (MaKH) REFERENCES tbl_KhachHang(MaKH)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================= TOUR =================
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

-- ================= HOA DON TOUR =================
CREATE TABLE tbl_HDTOUR (
	MaHD INT NOT NULL,
	MaLKH INT NOT NULL,
	SoNguoiLon INT,
	SoTreEm INT,
	TongTien DOUBLE,
	TrangThai BOOLEAN default 1,
	PRIMARY KEY (MaHD, MaLKH),
	FOREIGN KEY (MaLKH) REFERENCES tbl_LichKhoiHanh(MaLKH),
	FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================= DICH VU =================
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
	TongTien DOUBLE,
	TrangThai BOOLEAN DEFAULT 1,
	PRIMARY KEY (MaHD, MaDV),
	FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD),
	FOREIGN KEY (MaDV) REFERENCES tbl_DichVu(MaDV)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE tbl_HDPhong(
	MaHD INT NOT NULL,
    	MaPhong varchar(10) NOT NULL,
	NgayNhanPhong Date,
	NgayTraPhong Date,
	TongTien DOUBLE,
	TrangThai BOOLEAN DEFAULT 1,
	PRIMARY KEY (MaHD, MaPhong),
	FOREIGN KEY (MaHD) REFERENCES tbl_HoaDon(MaHD),
	FOREIGN KEY (MaPhong) REFERENCES tbl_Phong(MaPhong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============insert========

-- ================= CHUC VU =================
INSERT INTO tbl_ChucVu (TenCV) VALUES
('Quản lý'),
('Nhân viên lễ tân'),
('Nhân viên bán tour');

-- ================= NHAN VIEN =================
INSERT INTO tbl_NhanVien
(TenNV, GioiTinh, NgaySinh, DiaChi, SDT, TenTK, MatKhau, Email, MaCV)
VALUES
('Nguyễn Văn A',1,'1995-05-10','An Giang','0911111111','nva','$2y$10$wG3uJX2Y6v1rYFQn8zM1F.1Z8j4z5QmYvZyQZrYw1cZk2p0JYpK9G','a@gmail.com',1),
('Trần Thị B',0,'1998-03-15','Cần Thơ','0922222222','ttb','Ttb@123','b@gmail.com',2),
('Lê Văn C',1,'1993-07-20','Đồng Tháp','0933333333','lvc','Lvc@123','c@gmail.com',3);


-- ================= LOAI PHONG =================
INSERT INTO tbl_LoaiPhong (TenLoai) VALUES
('Phòng đơn'),
('Phòng đôi'),
('Phòng gia đình');

-- ================= PHONG =================
INSERT INTO tbl_Phong VALUES

-- PHÒNG ĐƠN (1)
('P101','Phòng đơn NT1',500000,2,'p101.jpg','Không gian nhà tranh yên tĩnh, gần gũi thiên nhiên, thích hợp nghỉ dưỡng cá nhân.',1),
('P102','Phòng đơn NT2',500000,2,'p102.jpg','Không gian mộc mạc, thoáng mát, tạo cảm giác thư giãn nhẹ nhàng.',1),
('P103','Phòng đơn NT3',500000,2,'p103.jpg','Thiết kế đơn giản, hài hòa với thiên nhiên xung quanh.',1),
('P104','Phòng đơn NT4',500000,2,'p104.jpg','Phù hợp khách thích sự riêng tư và yên tĩnh.',1),
('P105','Phòng đơn NT5',500000,2,'p105.jpg','Nội thất cơ bản, sạch sẽ và tiện nghi.',1),
('P106','Phòng đơn NT6',500000,2,'p106.jpg','Không gian thoáng đãng, nhiều cây xanh.',1),
('P107','Phòng đơn NT7',500000,2,'p107.jpg','Phòng nghỉ tiết kiệm nhưng đầy đủ tiện ích.',1),

('P108','Phòng đơn View1',800000,2,'p108.jpg','View núi đẹp, cửa sổ lớn đón ánh sáng tự nhiên.',1),
('P109','Phòng đơn View2',800000,2,'p109.jpg','Không gian sang trọng, view toàn cảnh thiên nhiên.',1),
('P110','Phòng đơn View3',800000,2,'p110.jpg','Ban công rộng, nhìn ra cảnh đẹp Núi Cấm.',1),

-- PHÒNG ĐÔI (2)
('P201','Phòng đôi NT1',900000,4,'p201.jpg','Không gian nhà tranh rộng rãi, phù hợp gia đình nhỏ.',2),
('P202','Phòng đôi NT2',900000,4,'p202.jpg','Thiết kế ấm cúng, đầy đủ tiện nghi.',2),
('P203','Phòng đôi NT3',900000,4,'p203.jpg','Không gian thoáng mát, gần gũi thiên nhiên.',2),
('P204','Phòng đôi NT4',900000,4,'p204.jpg','Phù hợp nhóm bạn hoặc gia đình.',2),
('P205','Phòng đôi NT5',900000,4,'p205.jpg','Yên tĩnh, tiện nghi đầy đủ.',2),
('P206','Phòng đôi NT6',900000,4,'p206.jpg','Thiết kế đơn giản nhưng tiện ích.',2),
('P207','Phòng đôi NT7',900000,4,'p207.jpg','Không gian nghỉ dưỡng thoải mái.',2),

('P208','Phòng đôi View1',1300000,4,'p208.jpg','Phòng cao cấp với view núi tuyệt đẹp.',2),
('P209','Phòng đôi View2',1300000,4,'p209.jpg','Ban công rộng, không gian sang trọng.',2),
('P210','Phòng đôi View3',1300000,4,'p210.jpg','Thiết kế hiện đại, view cực đẹp.',2),

-- PHÒNG GIA ĐÌNH (3)
('P301','Phòng gia đình NT1',1200000,6,'p301.jpg','Phòng rộng rãi, phù hợp gia đình đông người, ấm cúng.',3),
('P302','Phòng gia đình NT2',1200000,6,'p302.jpg','Thiết kế nhà tranh gần gũi thiên nhiên.',3),
('P303','Phòng gia đình NT3',1200000,6,'p303.jpg','Không gian sinh hoạt thoải mái.',3),
('P304','Phòng gia đình NT4',1200000,6,'p304.jpg','Phù hợp nghỉ dưỡng dài ngày.',3),
('P305','Phòng gia đình NT5',1200000,6,'p305.jpg','Nội thất đầy đủ tiện nghi.',3),
('P306','Phòng gia đình NT6',1200000,6,'p306.jpg','Không gian rộng rãi, thoáng mát.',3),
('P307','Phòng gia đình NT7',1200000,6,'p307.jpg','Phòng lý tưởng cho gia đình.',3),

('P308','Phòng gia đình View1',1800000,6,'p308.jpg','Phòng cao cấp, view núi đẹp.',3),
('P309','Phòng gia đình View2',1800000,6,'p309.jpg','Không gian sang trọng, rộng rãi.',3),
('P310','Phòng gia đình View3',1800000,6,'p310.jpg','Thiết kế hiện đại, ban công lớn.',3);

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
