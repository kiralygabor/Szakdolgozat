# Project Presentation Development Plan

**Project Name:** Minijobz (Kicsimelo)
**Type:** Web-based Task Marketplace Platform

This document outlines the content for your end-of-year project presentation. You can use this as a script or copy the sections directly into your slides.

---

## 1. Title Slide
*   **Project Title:** Minijobz / Kicsimelo
*   **Subtitle:** A Modern Platform for Connecting Taskers and Employers
*   **Team Members:** [Member 1 Name], [Member 2 Name], [Member 3 Name]

---

## 2. Project Overview
*   **Description:** Minijobz is a dynamic web application designed to connect people who need tasks done ("Employers") with skilled individuals ready to work ("Taskers"). It facilitates the entire gig lifecycle from posting a job to completion and review.
*   **Motivation:** We wanted to create a localized, user-friendly gig economy platform that simplifies finding help for everyday tasks while providing a secure environment for transaction and communication.
*   **Core Goals:**
    *   Simplify the process of outsourcing small jobs.
    *   Provide real-time communication between parties.
    *   Build trust through a review and verification system.

---

## 3. Technologies and Tools
We utilized a modern, full-stack web development approach:

*   **Backend:**
    *   **Laravel 12 (PHP 8.2+):** Chosen for its robust MVC architecture, security features, and Eloquent ORM.
    *   **Node.js & Socket.io:** For handling real-time features like messaging.
    *   **MySQL:** For structured data storage (users, tasks, messages).

*   **Frontend:**
    *   **Blade Templates:** For server-side rendering of views.
    *   **Tailwind CSS (v4.0):** For a responsive, modern, and highly customizable UI.
    *   **JavaScript (Axios):** For asynchronous requests and dynamic interactions.
    *   **Vite:** For high-performance asset bundling.

*   **Tools:**
    *   **Git & GitHub:** For version control and collaboration.
    *   **XAMPP:** Local development environment.
    *   **Composer & NPM:** Dependency management.

---

## 4. Supported Platforms
*   **Web-First Design:** The application is built as a responsive web app.
*   **Mobile Compatible:** Optimized layout ensures full functionality on smartphones and tablets.
*   **Desktop:** Full-feature experience on larger screens.

---

## 5. Visuals & Key Features
*(Add screenshots for the following screens in your slides)*

*   **Homepage:** Showcasing the "Post a Task" CTA and "How it works" section.
*   **Task Feed:** The browsing interface where Taskers can find jobs.
*   **Task Details:** Detailed view of a specific job with the "Make an Offer" option.
*   **Dashboard:** User's managed tasks and offers.
*   **Real-time Chat:** The messaging interface between two users.

---

## 6. Sample Code
**Feature:** Posting a New Task
**File:** `app/Http/Controllers/AdvertisementController.php`

This snippet demonstrates how we handle task creation, including server-side validation and file uploads for task photos.

```php
public function store(StoreTaskRequest $request)
{
    // 1. Validation (handled by Request class)
    $validated = $request->validated();

    // 2. Logic: Handle specific task types
    if (($validated['task_type'] ?? null) === 'online') {
        $validated['location'] = 'Online';
    }

    // 3. File Upload handling
    $photos = [];
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('task-photos', 'public');
            $photos[] = $path;
        }
    }

    // 4. Create and Save the Task
    $advert = new Advertisment();
    $advert->fill($validated);
    $advert->photos = $photos ?: null;
    $advert->employer_id = Auth::id(); // Link to current user
    $advert->expiration_date = now()->addDays(30);
    $advert->status = 'open';
    $advert->save();

    return redirect()->route('my-tasks')
        ->with('success', 'Your task has been posted successfully!');
}
```

*Key Takeaway:* This controller action ensures data integrity, handles media securely, and provides immediate feedback to the user.

---

## 7. Challenges and Solutions

*   **Challenge 1: Real-time Communication**
    *   *Issue:* Users needed to discuss task details without refreshing the page constantly.
    *   *Solution:* We integrated **Socket.io** with a Node.js service to push messages instantly to the frontend, creating a seamless chat experience.

*   **Challenge 2: Complex State Management**
    *   *Issue:* Tasks have multiple states (Open, Assigned, Completed, Cancelled).
    *   *Solution:* implemented a robust status workflow in the database and guarded state transitions in the Controllers to prevent invalid actions (e.g., editing a completed task).

*   **Challenge 3: Secure Photo Uploads**
    *   *Issue:* Handling multiple file uploads securely.
    *   *Solution:* Used Laravel's Storage facade to handle file naming and storage locations automatically, preventing file overwrites and directory traversal attacks.

---

## 8. Team Roles & Collaboration

*(Adjust according to your actual workflow)*

*   **[Member 1 Name] - Backend Lead:**
    *   Designed the database schema.
    *   Implemented API endpoints and Controllers.
    *   Handled Authentication logic.

*   **[Member 2 Name] - Frontend Lead:**
    *   Designed the UI/UX using Tailwind CSS.
    *   Created Blade templates and responsive layouts.
    *   Integrated JavaScript for dynamic components.

*   **Collaboration:** 
    *   We used **GitHub** for code sharing and merge request reviews.
    *   Regular stand-ups to discuss blockers and progress.

---

## 9. Future Improvements
*   **Mobile App:** Wrapping the web app into a native wrapper (React Native or Flutter) for push notifications.
*   **Payment Integration:** Integrating Stripe or PayPal for secure payments directly on the platform.
*   **Advanced Search:** Adding geolocation-based search (Radius search) for finding tasks nearby.
*   **Verification:** ID verification for Taskers to increase trust.

---

## 10. Live Demo
*(Prepare to show the following flow during the demo)*
1.  **Register/Login** a new user.
2.  **Post a Task:** Upload a photo, set a price.
3.  **Search:** Find the task from another account (incognito window).
4.  **Chat:** Send a message between the two accounts.
