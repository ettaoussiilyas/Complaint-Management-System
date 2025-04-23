📄 Cahier de Charges – Symfony Mini Project: Complaint Management System

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

