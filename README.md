# SuperMailer

SuperMailer - Laravel Web Application

## Author

**Mathew Kings**

🌐 Portfolio: [mkportfolio.crestdigico.com](https://mkportfolio.crestdigico.com)  
💼 LinkedIn: [linkedin.com/in/mathew-kings](https://linkedin.com/in/mathew-kings)  
🐦 Twitter: [@mathewkings9](https://twitter.com/mathewkings9)  
📧 Email: [mk@crestdigico.com](mailto:mk@crestdigico.com)

A comprehensive web application for generating and managing email campaigns with user authentication, advanced customization options, and batch processing capabilities.

## Features

- **User Authentication**: Secure login and registration system.
- **Campaign Management**: Create, schedule, and monitor email campaigns.
- **Contact Groups**: Organize contacts into groups for targeted mailing.
- **Email Templates**: Design and reuse customizable email templates.
- **Invoice Templates**: Manage invoice templates for billing purposes.
- **Message Templates**: Predefined message templates for quick communication.
- **Email History**: Track sent emails with detailed history and analytics.
- **Batch Processing**: Efficiently process large volumes of emails using background jobs.
- **Dashboard**: Overview of campaigns, activities, and statistics.
- **Email Services Configuration**: Integrate with various email providers (SMTP, etc.).

## Technologies Used

- **Laravel**: PHP framework for robust web application development.
- **PHP**: Server-side scripting language.
- **MySQL**: Database management system.
- **Tailwind CSS**: Utility-first CSS framework for styling.
- **Vite**: Fast build tool for modern web projects.
- **JavaScript**: For interactive frontend components.
- **Composer**: Dependency management for PHP.
- **NPM**: Package management for JavaScript.

## How to Launch

1. **Clone the repository**:

    ```bash
    git clone <repository-url>
    cd mass_mail
    ```

2. **Install PHP dependencies**:

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**:

    ```bash
    npm install
    ```

4. **Set up environment**:
    - Copy `.env.example` to `.env` and configure your database and other settings.
    - Generate application key:
        ```bash
        php artisan key:generate
        ```

5. **Run database migrations**:

    ```bash
    php artisan migrate
    ```

6. **Build assets**:

    ```bash
    npm run build
    ```

7. **Start the development server**:

    ```bash
    php artisan serve
    ```

8. **Access the application**:
   Open your browser and go to `http://localhost:8000`.

For production deployment, ensure proper web server configuration (e.g., Apache/Nginx) and run `npm run prod` for optimized assets.

## Hosted Version

Access the live application at: [https://cestdigico.com/supermailer](https://cestdigico.com/supermailer)
