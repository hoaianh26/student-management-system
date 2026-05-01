# BÁO CÁO DỰ ÁN: HỆ THỐNG QUẢN LÝ SINH VIÊN (SYMFONY)

## THÔNG TIN CHUNG

- **Framework:** Symfony 6.4 (PHP 8.1+)
- **Định hướng thiết kế:** Giao diện Dark Mode (Chủ đạo: Đen & Tím).
- **Mục tiêu:** Đạt mức Distinction (Xuất sắc).

---

# CHƯƠNG 1: XÁC ĐỊNH YÊU CẦU

## 1.1. Giới thiệu tổng quan về hệ thống

Hệ thống quản lý sinh viên là một ứng dụng web tập trung vào việc số hóa quy trình quản lý hồ sơ đào tạo, cung cấp môi trường làm việc an toàn và có tính tương tác cao.

## 1.2. Các tác nhân chính (Actors)

- **Quản trị viên (Admin/Staff):** Quản trị dữ liệu, thiết lập cấu hình.
- **Hệ thống (System):** Xử lý mã xác thực, điều phối API và phản hồi tự động.

## 1.3. Danh sách User Stories theo cấp độ đánh giá

### 1.3.1. Nhóm tính năng Quản lý dữ liệu cốt lõi (Mức Đạt - Pass)

- **US01:** Quản lý thông tin sinh viên (CRUD, Validation, Pagination).
- **US02:** Quản lý danh sách khóa học (Tên, Mã, Tín chỉ).
- **US03:** Quản lý sơ đồ tổ chức (Khoa/Department).
- **US04:** Quản lý đăng ký học tập (Enrollment).

### 1.3.2. Nhóm tính năng Bảo mật & Xác thực (Mức Khá - Merit)

- **US05:** Xác thực quyền truy cập (Login với mật khẩu đã băm).
- **US06:** Cơ chế xác thực đa nhân tố (2FA) qua Email.

### 1.3.3. Nhóm tính năng Nâng cao - API & JavaScript (Mức Xuất sắc - Distinction)

- **US07:** Giao tiếp dữ liệu qua API (JSON Endpoints).
- **US08:** Tối ưu hóa tương tác giao diện (Sử dụng JavaScript/Fetch API để cập nhật dữ liệu không cần tải lại trang).
- **US09:** Xử lý dữ liệu phức hợp hàng loạt (Bulk Enrollment).

## 1.4. Yêu cầu phi chức năng (Non-functional Requirements)

- **Bảo mật:** Hashing mật khẩu, 2FA giới hạn thời gian.
- **UI/UX:** Tông màu trầm (Đen - Tím), hiện đại, chuyên nghiệp, giảm mỏi mắt.
- **Hiệu năng:** Tối ưu hóa API Response (<200ms) và tính toàn vẹn dữ liệu.

---

# CHƯƠNG 2: THIẾT KẾ HỆ THỐNG

## 2.1. Sơ đồ trang web (Site Map)

- **Public:** Home, Login, 2FA Verification.
- **Admin Dashboard:** Student Management (Search/List/Add/Edit), Course Management, Department Management, Enrollment (Single & Bulk).
- **API Endpoints:** `/api/students`, `/api/courses`.

## 2.2. Sơ đồ thực thể quan hệ (ERD)

*(Thực hiện chèn ảnh ERD thực tế)*

### Chú thích các thực thể:

1. **Student:** Thông tin cá nhân, quan hệ Many-to-One với Department.
2. **Course:** Thông tin học phần, quan hệ One-to-Many với Enrollment.
3. **Department:** Quản lý khoa, thực thể cha của Student.
4. **Enrollment:** Thực thể trung gian kết nối Student và Course.
5. **User:** Tài khoản quản trị, tích hợp Symfony Security và 2FA.

---

# CHƯƠNG 3: HIỆN THỰC HÓA VÀ KẾT QUẢ ỨNG DỤNG

## 3.1. Triển khai kiến trúc hệ thống

- Tuân thủ MVC: 5 Entities, 6 Controllers, >12 Views.
- Công nghệ: Doctrine ORM, Twig, Fetch API.

## 3.2. Minh chứng mức Pass (CRUD)

- Trích dẫn code từ `StudentController.php`.
- Hình ảnh: Danh sách sinh viên có phân trang, Form thêm mới.

## 3.3. Minh chứng mức Merit (2FA)

- Cấu hình trong `security.yaml` và `scheb_2fa.yaml`.
- Hình ảnh: Trang Login, Giao diện nhập mã OTP từ Email.

## 3.4. Minh chứng mức Distinction (API & JS)

- **Backend:** Code API Controller trả về `JsonResponse`.
- **Frontend:** Code JavaScript (Fetch API) thực hiện Instant Search.
- Hình ảnh: Dữ liệu JSON chuẩn và giao diện cập nhật thời gian thực.

## 3.5. Minh chứng GitHub

- URL Repository và lịch sử Commits minh chứng quá trình đóng góp cá nhân.

---

# CHƯƠNG 4: KẾT LUẬN VÀ ĐÁNH GIÁ (DỰ THẢO)

*(Sẽ bổ sung chi tiết sau: Ưu điểm về bảo mật/tốc độ, Khuyết điểm về độ phức tạp, Bài học về quản lý State với JS trong Symfony)*
