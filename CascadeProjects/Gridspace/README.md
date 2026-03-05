# Gridspace - Workspace Directory Platform

A complete Laravel-based web directory for coworking and workspace services, built as an MVP with comprehensive analytics and user management.

## 🌟 Features

### Core Functionality
- **Workspace Directory**: Complete listing system for coworking spaces, meeting rooms, virtual offices, event spaces, and more
- **User Management**: Role-based authentication (Admin/Host/Guest)
- **Advanced Search**: Live search with filtering by category, city, capacity, and price range
- **Analytics Dashboard**: Comprehensive tracking for monetization and insights
- **Guest Inquiries**: Lead capture system without requiring registration

### User Roles
- **Admin**: Full CRUD on listings, toggle featured status, view/export analytics
- **Host**: Register, create/edit listings, upload images, track performance
- **Guest**: Search, view listings, submit inquiries, click-to-call tracking

### Key Features
- 🏢 **6 Core Categories**: Coworking Spaces, Meeting Rooms, Virtual Offices, Event Spaces, Corporate Solutions, Startup Services
- 📊 **Analytics Tracking**: Views, unique visitors, phone clicks, WhatsApp clicks, inquiries
- 🎯 **Featured Listings**: Priority placement and visibility
- 📱 **Mobile-First Design**: Responsive across all devices
- 🔍 **Live Search**: Real-time search with dropdown suggestions
- 📈 **CSV Export**: Download analytics data for external analysis

## 🚀 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **File Storage**: Local storage with image optimization
- **Icons**: Font Awesome 6

## 📋 Requirements

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM
- Laravel 11

## 🛠️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/gridspace.git
   cd gridspace
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## 📊 Analytics Features

The platform tracks comprehensive analytics for each listing:
- **Total Views**: All page visits
- **Unique Views**: Different visitors by IP address
- **Phone Clicks**: Click-to-call interactions
- **WhatsApp Clicks**: WhatsApp button interactions
- **Inquiries**: Contact form submissions
- **Time-based Data**: Last 7 days and 30 days metrics

## 🔐 Security

- CSRF protection
- Rate limiting on forms
- Secure admin routes
- Input validation and sanitization
- Password hashing

## 🎯 MVP Success Criteria

✅ **Technical Requirements**
- Stable CRUD operations
- Reliable analytics tracking
- Inquiry storage working

✅ **Business Requirements**
- Ready for 50+ listings
- Measurable traffic tracking
- Analytics usable for host upsell

## 📱 Live Demo

[Coming Soon - Deploy your live version here]

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is open-sourced software licensed under the MIT license.

---

**Gridspace** - Find Your Perfect Workspace 🏢
