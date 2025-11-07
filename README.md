# Vanilla Blog (PHP + MySQL + HTML/CSS/JS)

A minimal blog fulfilling the assignment requirements — **no frameworks**.

## Features
- Register, login, logout
- Only authenticated users can create/update/delete
- Users cannot edit/delete others' posts (server-side checks)
- Home (list), single view, editor (create & update)
- Markdown support with live preview
- Minimal black & gold UI
- `.env` config, comments, and error handling

## Local setup (XAMPP)
1. Start **Apache** and **MySQL**.
2. Copy this folder to your web root (Windows XAMPP): `C:\xampp\htdocs\blog-vanilla-php`
3. Open `http://localhost/phpmyadmin`, select DB `blog_db` (create it if needed), and run `database.sql`.
4. Edit `.env` if needed (DB creds, `APP_URL`). Defaults work for local XAMPP.
5. Visit `http://localhost/blog-vanilla-php`

## Hosting (InfinityFree / 000WebHost)
- Upload all files to your public web root.
- Create a MySQL database, import `database.sql`.
- Update `.env` with hosting DB credentials and your public `APP_URL`.
- (Optional) Promote an admin:
  ```sql
  UPDATE user SET role='admin' WHERE email='you@example.com';
