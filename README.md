📄Symfony Mini Project: Complaint Management System

1. 🎯 Project Title
   
Complaint Management System (CMS)

3. 📝 Project Objective

Design and develop a small but complete Symfony web application to demonstrate back-end development skills by building a system where users can submit complaints and admins can view and manage them.

4. 🧩 Main Functionalities

Feature	Description
🏠 Homepage	Static page that introduces the app
🧑‍💻 Complaint Form	Allows users to submit a complaint with a title & description
🔒 Admin Dashboard	Admin can list all complaints, update their status
📦 Services	Analyze keywords in complaints
🔔 Event Listener	Trigger an event on complaint submission
🔁 Caching	Cache the list of complaints (admin side)
📝 Logging	Log complaint submissions & updates
🚫 Exception Handling	Catch errors & display custom error pages
🧠 Repository	Custom repository methods (e.g., get complaints by status)

4. 👤 User Roles

Role	Permissions
Guest	View homepage only
User	Submit a complaint
Admin	View all complaints, change status


5. 🛠️ Tech Stack
   
Symfony 6.x (latest)

Twig

Doctrine ORM (SQLite or MySQL)

Symfony Event Dispatcher

Symfony Caching

Symfony Logging (Monolog)

PHP 8+

HTML/CSS (for Twig views)

6. 📂 Entities
   
Complaint

- id: int
- title: string
- description: text
- status: string (e.g., “Pending”, “In Progress”, “Resolved”)
- createdAt: datetime
  
Users

- id: int
- name: string
- lastName: string
- password: string
- created_at: date
- updated_t: date

📌 Use Cases – Complaint Management System
✅ Use Case 1: Submit Complaint
Actor: User (Anonymous)

Description: A visitor can submit a complaint through a form.

Preconditions: Complaint form is accessible via a route.

Flow:

User accesses the complaint form.

Fills in the title and description.

Submits the form.

System validates and saves the data.

Event is dispatched and action logged.

Postconditions: Complaint is saved with status set to Pending.

✅ Use Case 2: List Complaints (Admin)
Actor: Admin

Description: Admin can view all submitted complaints.

Preconditions: Admin has access to the dashboard.

Flow:

Admin visits /admin/complaints.

System fetches all complaints (uses cache if available).

Complaints are displayed in a list.

Postconditions: Admin sees up-to-date list of complaints.

✅ Use Case 3: Update Complaint Status
Actor: Admin

Description: Admin updates the status of a complaint (e.g., In Progress, Resolved).

Preconditions: Admin is authenticated and on complaint page.

Flow:

Admin selects a complaint.

Chooses a new status.

System updates the database, logs the change, and clears cache.

Postconditions: Complaint status is updated and logged.

✅ Use Case 4: Keyword Analysis
Actor: System

Description: Detect urgency or specific tags in submitted complaints.

Preconditions: Complaint contains text.

Flow:

Complaint is submitted.

System scans for keywords like urgent, refund, etc.

Complaint is flagged internally if keywords found.

Postconditions: Complaint is tagged for review or priority handling.

✅ Use Case 5: Log Events
Actor: System

Description: Logs major actions such as submissions, updates, and errors.

Flow:

System triggers event listeners.

Logger captures actions and stores logs (e.g., in var/log).

Postconditions: Logs available for debugging and monitoring.

✅ Use Case 6: Display Custom Error Pages
Actor: User

Description: Show user-friendly error pages (404 and 500).

Flow:

User triggers an error (invalid route or internal error).

Custom Twig error page is rendered.

Postconditions: User sees helpful message instead of generic error.

✅ Use Case 7: Retrieve Complaints by Status
Actor: Admin

Description: Filter and display complaints by status via repository method.

Flow:

Admin selects status filter.

Repository returns results (e.g., findByStatus("Resolved")).

Postconditions: Admin views only filtered complaints.



