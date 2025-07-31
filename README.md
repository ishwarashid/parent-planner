# Parent Planner

Parent Planner is a web application designed to help co-parents manage the complex logistics of raising children in separate households. It provides a centralized platform for tracking visitation schedules, sharing expenses, and storing important documents, fostering better communication and organization.

## Key Features

*   **Dashboard:** A central hub providing an at-a-glance overview of upcoming visitations and recent expenses.
*   **Child Management:** Easily add and manage profiles for each child.
*   **Interactive Visitation Calendar:** A dynamic, full-featured calendar to schedule and view visitations.
    *   Drag-and-drop to reschedule events.
    *   Resize events to change their duration.
    *   Filter visitations by child.
    *   Create and edit visitations in a modal without leaving the page.
    *   Hover-over tooltips for quick details.
    *   Visual indicators for recurring events.
*   **Expense Tracking:** Log shared expenses, categorize them, and track what's owed.
*   **Document Storage:** Securely upload and store important documents like birth certificates or school records.
*   **Reporting:** Generate reports for visitations and expenses, useful for financial planning or legal documentation.
*   **Notifications:** Receive timely reminders for upcoming visitations and pending expenses.
*   **Subscription Management:** Manage user subscriptions and access to premium features.

## Technology Stack

*   **Backend:** Laravel 11
*   **Frontend:**
    *   Blade Templates with Alpine.js
    *   [FullCalendar.js](https://fullcalendar.io/) for the visitation calendar
    *   Tailwind CSS for styling
    *   Vite for asset bundling
*   **Database:** SQLite (by default, configurable in `.env`)
*   **Authentication:** Laravel Breeze

## Prerequisites

*   PHP 8.2 or higher
*   Composer
*   Node.js & npm
*   A web server (Nginx, Apache, or the built-in Laravel server)

## Installation and Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/parent-planner.git
    cd parent-planner
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Set up your environment file:**
    *   Copy the example environment file.
        ```bash
        cp .env.example .env
        ```
    *   Generate a new application key.
        ```bash
        php artisan key:generate
        ```

5.  **Configure the database:**
    *   This project is configured to use SQLite by default. Create the database file:
        ```bash
        touch database/database.sqlite
        ```
    *   If you prefer to use another database (like MySQL), update the `DB_*` variables in your `.env` file accordingly.

6.  **Run the database migrations:**
    *   This will create all the necessary tables in your database.
        ```bash
        php artisan migrate
        ```

7.  **Build frontend assets:**
    ```bash
    npm run build
    ```

## Running the Application

1.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.

2.  **Start the Vite development server (for frontend development):**
    *   If you are actively making changes to JavaScript or CSS files, run this command in a separate terminal. It will watch for changes and automatically rebuild the assets.
    ```bash
    npm run dev
    ```

## Running Tests

To run the application's test suite, use the following Artisan command:

```bash
php artisan test
```

## Database Schema

The main database tables include:

*   `users`: Stores user account information for each parent.
*   `children`: Stores profiles for the children.
*   `visitations`: Contains all information about scheduled visitations, including start/end times, notes, and recurrence status.
*   `expenses`: Logs financial expenses related to the children.
*   `documents`: Stores metadata and paths for uploaded files.
*   `subscriptions`: Manages user subscriptions.
*   `subscription_items`: Stores individual items associated with a subscription.