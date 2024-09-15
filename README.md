
<p align="center"><a href="https://laravel.com" target="_blank"><img src="public/images/logo.png" width="200" alt="SponBot Logo"></a></p>

<p align="center">
<b>SponBot</b> - Connect with Sponsor and Sponsees
</p>

## About SponBot

**SponBot** is a platform designed to help people connect with sponsors and sponsees to overcome addiction. Through real-time meetings, sponsor requests, and chat features, SponBot provides the support necessary for recovery in a flexible and accessible way.

### Key Features:

- Find and join local and global meetings synced to your time zone.
- Connect with sponsors and request sponsorship.
- Real-time chatting with Reverb for instant communication.

## Installation and Setup

To run **SponBot** locally, follow the steps below:

### Prerequisites:

- [PHP 8.1+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/) & npm (v16.x or later)
- [MySQL](https://www.mysql.com/) or any database supported by Laravel

### Installation Steps:

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/EladlyDev/SponBot.git
   cd sponbot
   ```
2. **Install Dependencies:**

   Install PHP and Node.js dependencies:

   ```bash
   composer install
   npm install
   ```
3. **Environment Configuration:**

   Copy the `.env.example` file to `.env` and configure your environment settings:

   ```bash
   cp .env.example .env
   ```

   Generate an application key:

   ```bash
   php artisan key:generate
   ```
4. **Database Setup:**

   Set up your database credentials in the `.env` file:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sponbot_db
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
   ```

   Run the migrations:

   ```bash
   php artisan migrate
   ```
5. **Running the Application:**

   Start the Laravel development server:

   ```bash
   php artisan serve
   ```
6. **Running Vite for Frontend:**

   In a separate terminal window, run the Vite development server to compile the frontend assets:

   ```bash
   npm run dev
   ```

## Real-time Chatting with Reverb

SponBot utilizes **Laravel Reverb** to enable real-time chat and event broadcasting. To enable this feature, make sure you have set up the broadcasting configuration properly.

### Starting Reverb for Real-Time Chat:

Run the following command to start the Reverb server for real-time chatting and notifications:

```bash
php artisan reverb:start
```

This will enable real-time communication for chat, notifications, and other live updates across the platform.

## Key Technologies Used

- **Laravel 10** - PHP Framework
- **Livewire** - For building dynamic interfaces without leaving the page
- **Tailwind CSS** - Utility-first CSS framework
- **Reverb** - Real-time event broadcasting and chat
- **MySQL** - Relational database

## Contributing

Thank you for considering contributing to **SponBot**! Feel free to open issues and submit pull requests. Please follow the [Laravel contribution guide](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within **SponBot**, please send an e-mail to eladlydev@gmail.com All security vulnerabilities will be promptly addressed.
