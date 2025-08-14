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
*   **User Roles & Access Control:** A role-based system to manage permissions for different user types.

## User Roles & Access Control

Parent Planner supports the following user roles with specific access levels:

*   **Parent (Primary Account Holder):**
    *   This is the default role for users who register directly.
    *   Full access to all features, including creating and managing children, visitations, expenses, and documents.
    *   Responsible for the subscription.
    *   Can invite other users to the account.

*   **Co-parent:**
    *   Assigned via invitation.
    *   Full access to all features, same as the Parent.
    *   Does not manage the subscription.

*   **Nanny, Grandparent, and Guardian:**
    *   Assigned via invitation.
    *   **Limited Access:** These roles have "Calendar view only" access.
    *   They can view the visitation calendar but cannot create, edit, or delete any information (visitations, expenses, documents, or children).

### Invitation Flow & Status:

*   **Sending Invitations:** A `Parent` user can invite others via email to join their family unit with a specific role.
*   **Accepting/Rejecting Invitations:** Invited users receive an email with a unique link. Clicking this link takes them to a page where they can choose to **Accept** or **Reject** the invitation.
    *   If **Accepted** by a guest user, they are redirected to the registration page with their email pre-filled and the invitation token included. Upon successful registration, their account is linked to the inviter's family unit with the assigned role.
    *   If **Accepted** by an already logged-in user, they are redirected to their dashboard with a message indicating the invitation was accepted (future enhancements may allow linking to an existing account).
    *   If **Rejected**, the invitation status is updated accordingly.
*   **Invitation Statuses:** The sender's "Invitations" page will display the status of each sent invitation:
    *   `pending`: The invitation has been sent but not yet acted upon.
    *   `accepted`: The invitation has been accepted by the recipient.
    *   `rejected`: The invitation has been explicitly rejected by the recipient.
    *   `registered`: The recipient has accepted the invitation and successfully registered an account.

### Key Access Rules:

*   **Subscription:** Only the `Parent` (the primary account holder) is required to have an active subscription.
*   **User Accounts:** All users, whether direct registrants or invited, must have their own login.

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

## Professionals Module & Admin Panel

Parent Planner includes a feature to connect parents with family-service professionals. This module allows professionals to advertise their services on the platform, and provides an administrative interface to manage them.

### For Professionals

*   **Separate Registration:** Professionals have a dedicated registration form to create an account with the `professional` role.
*   **Profile Management:** After logging in, professionals are directed to their own dashboard where they can create and manage their public profile, including business name, services offered, contact information, and social media links.
*   **Subscription Requirement:** To have their profile publicly listed, professionals must subscribe to a specific "Professional" plan. Their dashboard guides them through the subscription process.

### For Parents & Co-Parents

*   **Public Listing:** A "Professionals" page is available in the main navigation, allowing parents and co-parents to browse a directory of approved and subscribed professionals.
*   **Search & Filter:** The directory can be searched to easily find professionals by their name, services, or location.

### Admin Panel

An admin panel is included to manage the professional approval workflow.

*   **Admin Access:** To grant a user admin privileges, run the following Artisan command:
    ```bash
    php artisan app:grant-admin your-email@example.com
    ```
*   **Approval Workflow:** The admin panel, accessible at `/admin`, lists all professionals who have registered and are pending approval. The administrator can view each professional's profile and choose to either `approve` or `reject` their application. Professionals are only able to subscribe and be publicly listed after they have been approved.
