# 🗳️ Online Voting System

A secure and user-friendly web-based application that allows users to cast their votes online. Designed to streamline the election process digitally while maintaining transparency, accuracy, and integrity.

---

## 📌 Features

- 🧑‍💼 Admin dashboard to manage elections, positions, and candidates  
- 👥 Voter registration and login system  
- 🗳️ One vote per position per voter  
- 📊 Real-time results and vote tallying  
- 🖼️ Candidate profiles with photo and platform  
- 🔒 Secure login and vote validation

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP  
- **Database:** MySQL  
- **Server:** Apache (XAMPP/LAMP/WAMP)

---

## 🚀 Getting Started

### Prerequisites

- XAMPP/WAMP/LAMP installed  
- PHP 7.x or later  
- MySQL  

## 🔐 Roles

- **Admin:** Can manage elections, candidates, and view results  
- **Voter:** Can log in and vote once per election

---

## 📂 Folder Structure

```
VOTINGSYSTEM/
├── admin/                   # Admin dashboard and settings
├── bower_components/        # Frontend libraries
├── db/                      # Database scripts or connection files
├── dist/                    # Distribution files (e.g., compiled assets)
├── images/                  # Image assets (candidate photos, logos)
├── includes/                # Reusable PHP includes/config
├── plugins/                 # External plugins
├── vendor/                  # Composer dependencies
├── composer.json            # Composer configuration
├── composer.lock            # Composer lock file
├── fetch_ballot.php         # Fetches ballot dynamically
├── home.php                 # User home/dashboard
├── index.php                # Entry point
├── login.php                # Voter login
├── logout.php               # Voter logout
├── otp_verification.php     # OTP-based voter verification
├── preview.php              # Preview ballot before final submission
├── profile.php              # Voter profile page
├── register.php             # Voter registration
├── results.php              # Display election results
├── submit_ballot.php        # Handles ballot submission
├── submit_vote.php          # Processes each vote
├── update_profile.php       # Voter profile update
├── vote.php                 # Voting interface

```

---



