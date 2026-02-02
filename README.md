# Hardware Store - Laravel GraphQL E-commerce Platform

A comprehensive e-commerce platform built with Laravel and GraphQL, featuring multi-vendor support, advanced product management, and state-based tax calculation.

## 🚀 Features

### Core Features
- **Multi-vendor Support** - Multiple stores can sell on the platform
- **GraphQL API** - Modern API with GraphQL and REST endpoints
- **Product Management** - Advanced product catalog with variations, attributes, and accessories
- **Order Management** - Complete order lifecycle management
- **User Management** - Customers, vendors, and admin roles
- **Payment Integration** - Multiple payment gateways (Stripe, PayPal, Razorpay, etc.)
- **Shipping Management** - Flexible shipping rules and zones
- **Coupon System** - Discount coupons with various rules
- **Wallet & Points** - Customer wallet and loyalty points system
- **Review System** - Product reviews and ratings
- **Q&A System** - Product questions and answers
- **Blog System** - Content management for blogs
- **Refund Management** - Handle refunds and returns

### Advanced Features
- **State-Based Tax Calculation** - Automatic tax calculation based on US state (all 50 states)
- **TQL Integration** - Shipping quote integration
- **Real-time Chat** - AI-powered customer support chatbot
- **Multi-language Support** - English, French, Arabic
- **Multi-currency Support** - Multiple currency support
- **License Key Management** - Digital product license keys
- **Wholesale Pricing** - Bulk pricing support
- **Zone-based Restrictions** - Product availability by zones
- **Media Library** - Advanced media management with Spatie Media Library

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js >= 16.x
- NPM or Yarn

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd graphql
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 5. Run Migrations and Seeders
```bash
# Run migrations
php artisan migrate

# Seed US states with tax rates
php artisan db:seed --class=UsStateTaxSeeder

# (Optional) Seed demo data
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7. Build Assets
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🎯 State-Based Tax System

This platform includes an advanced state-based tax calculation system for all 50 US states.

### How It Works
- Tax is automatically calculated based on the customer's billing/shipping address
- Supports both logged-in users and guest checkout
- Falls back to product-specific tax if no state is provided
- Zero-tax states: Delaware, Montana, New Hampshire, Oregon

### Tax Rates by State
See [STATE_TAX_IMPLEMENTATION.md](STATE_TAX_IMPLEMENTATION.md) for complete tax rate table and implementation details.

### Setup
```bash
# Run migration to add tax_rate column
php artisan migrate

# Seed US states with tax rates
php artisan db:seed --class=UsStateTaxSeeder
```

## 📚 API Documentation

### GraphQL Playground
Access GraphQL playground at: `http://localhost:8000/graphiql`

### REST API
API endpoints are available at: `http://localhost:8000/api/v1/`

### Authentication
The API uses Laravel Sanctum for authentication.

## 🔧 Configuration

### Payment Gateways
Configure payment gateways in `.env`:
```env
# Stripe
STRIPE_API_KEY=your_stripe_key
STRIPE_SECRET_KEY=your_stripe_secret

# PayPal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_secret

# Razorpay
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
```

## 🗂️ Project Structure

```
graphql/
├── app/
│   ├── Enums/              # Enum classes
│   ├── Events/             # Event classes
│   ├── Exports/            # Export classes
│   ├── GraphQL/            # GraphQL mutations and queries
│   ├── Helpers/            # Helper classes
│   ├── Http/
│   │   ├── Controllers/    # HTTP controllers
│   │   ├── Requests/       # Form requests
│   │   └── Traits/         # Reusable traits
│   ├── Imports/            # Import classes
│   ├── Models/             # Eloquent models
│   ├── Payments/           # Payment gateway integrations
│   └── Repositories/       # Repository pattern
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── graphql/               # GraphQL schema files
├── public/                # Public assets
├── resources/
│   ├── lang/              # Language files
│   └── views/             # Blade templates
├── routes/
│   ├── api.php            # API routes
│   ├── admin.php          # Admin routes
│   └── web.php            # Web routes
└── storage/               # Storage (excluded from git)
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=TestName
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Set proper file permissions
7. Configure your web server (Nginx/Apache)

### Optimization
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 📖 Documentation

- [State Tax Implementation](STATE_TAX_IMPLEMENTATION.md) - Detailed documentation on state-based tax system
- [Quick Setup Guide](QUICK_SETUP_GUIDE.md) - Quick reference for setup

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is proprietary software. All rights reserved.

## 🆘 Support

For support and questions:
- Check the documentation files
- Review Laravel logs: `storage/logs/laravel.log`
- Check migration status: `php artisan migrate:status`

## 🔐 Security

If you discover any security-related issues, please email the development team instead of using the issue tracker.

## 📊 Features Overview

### For Customers
- Browse products with advanced filtering
- Add products to cart with accessories
- Apply coupons and discounts
- Multiple payment options
- Track orders in real-time
- Wallet and loyalty points
- Product reviews and Q&A
- Wishlist and compare products

### For Vendors
- Manage products and inventory
- Track sales and orders
- Manage withdrawals
- Commission tracking
- Store analytics

### For Admins
- Complete platform management
- User and vendor management
- Order management
- Product approval workflow
- Analytics and reports
- Settings configuration
- Tax management
- Shipping zone management

## 🌟 Recent Updates

### State-Based Tax System (Latest)
- Added tax_rate column to states table
- Implemented automatic tax calculation based on customer address
- Seeded all 50 US states with tax rates
- Supports both logged-in and guest checkout
- Falls back to product tax when no state is provided

## 🔄 Version History

- **v1.0.0** - Initial release with state-based tax system

---

Built with ❤️ using Laravel and GraphQL
