Hotel-Tour
Hotel Management Website with AI support, built using PHP base on laravel framework. A Laravel-based web application for managing bookings of rooms, tours and add-on services. Supports multi-service invoices, admin dashboard, and payment webhook integration.
Some feature:
- Multi-service booking: Rooms, tours, and services in a single invoice.
- Inventory checks: Date-conflict checks for rooms and seat tracking for tours.
- Payments: SePay webhook for bank-transfer confirmations.
- Authentication: Session-based auth for staff and customers; OTP flow for customers.
- Admin CRUD: Manage tours, schedules, rooms, room types, services, customers, invoices, and staff.

Installation
# Clone repository: 
git clone https://github.com/HoangLoc2704/Hotel-Tour/.
# Install dependencies: 
composer install.

# Copy env and set secrets: 
cp .env.example .env
remember to edit .env: DB_*, MAIL_*, SEPAY_*, etc.

# Generate key and run migrations:
php artisan key:generate
php artisan migrate --seed

# Database & Migrations:
php artisan db:seed
# To run this project in your local computer, you need to use Laragon to create a visual host that can enable you to access the website. Then use: php artisan serve, and access to https://127.0.0.1
