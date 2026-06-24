# InfoMotive - Comprehensive Automotive Digital Platform

InfoMotive is a modern web application designed to bring transparency to automotive spare part pricing, provide in-depth vehicle maintenance education, and offer a directory of verified workshops across Indonesia. Built with a focus on seamless UX, robust database structure, and AI-powered conversational assistance.

## 📌 Project Objective
This project serves as a comprehensive showcase of full-stack web development capabilities, demonstrating clean architectural patterns, database integration, RESTful API consumption, and prompt engineering using generative AI (Retrieval-Augmented Generation).

## 🚀 Highlighted Features
- **Smart Catalog & Price Transparency**: Interactive product search with real-time modal details, automated view tracking, and min-max price comparison.
- **AI Conversational Assistant (BotMotif)**: Integration with Google Gemini AI utilizing advanced RAG intent detection and local fallback logic to answer automotive questions while politely rejecting out-of-scope topics.
- **Interactive Workshop Directory**: Visual map integration (via Leaflet & OpenStreetMap) allowing users to locate nearby trusted workshops and navigate directly via Google Maps.
- **Educational Knowledge Base**: Curated automotive tips, maintenance guides, and safety articles with category filtering.
- **Secure Admin Dashboard**: Content management system for administrative users to manage products, view analytics, and monitor system metrics.

## 🛠️ Technology Stack
- **Frontend**: HTML5, Vanilla CSS (Custom Design System with Glassmorphism aesthetics), Vanilla JavaScript, FontAwesome 6, Leaflet.js.
- **Backend**: PHP 8.x (Native procedural & light-MVC approach), cURL for external API requests.
- **Database**: MySQL / MariaDB with PDO (PHP Data Objects) prepared statements for SQL injection prevention.
- **AI Integration**: Google GenAI API (Gemini 1.5 Flash / 2.5 Flash) with customized multi-layer model fallback mechanism.

## 📁 Repository Structure
```text
InfoMotive/
├── admin/                  # Secure backend CMS dashboard
├── api/                    # RESTful endpoints & AI chat handler
├── assets/                 # Custom CSS, JS scripts, and static media
├── auth/                   # Authentication modules (Login, Session handling)
├── config/                 # Environment configs & database connection (PDO)
├── database/               # Database migrations and seeding utilities
├── includes/               # Reusable UI components (Chatbot modal, Modals)
└── index.php               # Scrollable premium landing page
```

## ⚙️ Installation & Local Setup

### 1. Prerequisites
- PHP 8.0 or higher
- MySQL / MariaDB (XAMPP/MAMP/LAMP stack recommended)
- Git

### 2. Clone the Repository
```bash
git clone https://github.com/yourusername/infomotive.git
cd infomotive
```

### 3. Environment Configuration
Copy the configuration example file and add your custom settings:
```bash
cp config/.env.example config/.env
```
Open `config/.env` and insert your database credentials and Gemini API Key:
```ini
DB_HOST=localhost
DB_NAME=bengkel_db
DB_USER=root
DB_PASS=
AI_PROVIDER=gemini
AI_API_KEY=your_actual_gemini_api_key_here
```

### 4. Database Setup
Create a MySQL database named `bengkel_db`. The application features an automated migration and seeding mechanism. Upon accessing the application for the first time, `config/database.php` will automatically establish tables and seed initial sample data.

### 5. Run the Application
You can use PHP's built-in development server:
```bash
php -S localhost:8000
```
Access the application in your browser at `http://localhost:8000`.

## 📸 Application Preview
- **Landing Page & Hero Section**: `assets/images/screenshots/landing.png`
- **Smart Catalog Modal**: `assets/images/screenshots/catalog.png`
- **AI Chatbot Modal**: `assets/images/screenshots/chatbot.png`
- **Workshop Map**: `assets/images/screenshots/map.png`

## 🛣️ Development Roadmap
- [x] Migrate legacy UI to premium Glassmorphism design system.
- [x] Integrate robust fallback model chain for Google Gemini AI.
- [ ] Refactor procedural structure into PSR-4 autoloaded object-oriented architecture.
- [ ] Implement unit testing using PHPUnit.

## 📄 License
This project is licensed under the MIT License - see the LICENSE file for details.
