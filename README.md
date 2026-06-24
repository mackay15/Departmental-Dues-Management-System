# HTU COMPSSA Student Finance Management System (SFMS)

A modern Laravel-based web application designed to manage departmental dues, student records, academic progression, invoices, payments, receipts, reporting, and user management for the Ho Technical University Computer Science Students Association (COMPSSA).

The system provides a secure, scalable, and user-friendly platform for administrators, Heads of Department, finance officers, auditors, and students to efficiently manage departmental financial operations throughout the students' academic journey.

---

# Table of Contents

- Overview
- Objectives
- Key Features
- System Modules
- User Roles
- Technology Stack
- System Architecture
- Student Academic Lifecycle
- Student Promotion Module
- Database Design
- Project Structure
- Installation
- Configuration
- Authentication
- Dashboards
- Reports
- Notifications
- Security
- Development Roadmap
- Future Improvements
- License

---

# Overview

Managing departmental dues manually often results in:

- Lost payment records
- Duplicate receipts
- Difficulty tracking outstanding balances
- Poor reporting
- Lack of transparency
- Time-consuming reconciliation
- Manual student promotion every academic year

The Student Finance Management System (SFMS) automates these processes by providing a centralized platform for managing students, departmental dues, academic sessions, invoices, receipts, payments, reports, and academic progression.

---

# Project Objectives

The project aims to:

- Digitize departmental dues management.
- Reduce manual paperwork.
- Improve accountability.
- Track student payments.
- Generate official receipts.
- Monitor outstanding balances.
- Promote students automatically into new academic years.
- Provide real-time reports.
- Maintain audit logs.
- Improve transparency within COMPSSA.

---

# Key Features

## Authentication

- Secure Login
- Password Hashing
- Forgot Password
- Email Verification
- Password Reset
- Session Timeout
- Remember Me
- Two-Factor Authentication (Future)

---

## Student Management

- Student Registration
- Student Profiles
- Student Search
- Student Status Management
- Student Photo
- Programme Assignment
- Academic Level Management
- Student History
- CSV Import
- Excel Import

---

## Academic Session Management

Manage:

- Academic Years
- Current Session
- Previous Sessions
- Semester

Example

2024/2025

↓

2025/2026

↓

2026/2027

Only one session can be active.

---

## Student Promotion (Academic Migration)

One of the major features of SFMS.

At the end of every academic year, the HOD can migrate students to the next academic level.

Example

Level 100

↓

Level 200

↓

Level 300

↓

Level 400

↓

Graduated

Promotion includes:

- Eligibility Check
- Outstanding Balance Check
- Payment Verification
- Promotion Preview
- Promotion Log
- Automatic Invoice Generation
- Academic History

Every promotion is permanently stored.

No academic records are deleted.

---

## Dues Management

Create:

- Departmental Dues
- Special Levies
- Project Fees
- SRC Contributions

Each dues category includes:

- Amount
- Academic Year
- Programme
- Due Date
- Description

---

## Invoice Management

Instead of recording payments directly, the system first generates invoices.

Each invoice contains:

Invoice Number

Academic Year

Student

Programme

Outstanding Balance

Payment Status

---

## Payment Management

Finance Officers can:

- Record Payments
- Partial Payments
- Full Payments
- Payment Reversal
- Payment Verification

Supported Methods

- Cash
- Mobile Money
- Bank Transfer

Future

- Online Payment Gateway

---

## Receipt Management

Generate professional PDF receipts.

Each receipt contains:

- University Logo
- COMPSSA Logo
- Receipt Number
- QR Code
- Verification Code
- Amount Paid
- Payment Method
- Cashier
- Date

Students can download receipts anytime.

---

## User Management

Administrators can manage:

- Users
- Roles
- Permissions
- Account Status
- Password Reset

---

## Reports & Analytics

Interactive dashboards include:

- Monthly Revenue
- Revenue Growth
- Outstanding Dues
- Payment Trends
- Students by Programme
- Students by Level
- Collection Rate
- Daily Collections

Export:

- PDF
- Excel
- CSV

---

## Notifications

Students receive notifications when:

- Invoice Generated
- Payment Recorded
- Receipt Generated
- Promotion Completed
- Outstanding Balance Reminder

Admins receive:

- Low Collection Alerts
- Failed Payments
- Promotion Logs
- User Activities

---

## Audit Logs

Every activity is recorded.

Examples

- User Login
- Student Created
- Payment Posted
- Invoice Generated
- Promotion Completed
- User Updated
- Student Deleted

Each log stores:

- User
- Action
- Timestamp
- Old Values
- New Values
- IP Address

---

# User Roles

## Head of Department (HOD) / Administrator

The HOD serves as the primary administrator of the system.

Permissions & Responsibilities:

- Full System Access & Configuration
- Manage Staff Users (Finance Officer, Auditor, HOD)
- Student Promotion & Academic progression
- Manage Academic Sessions & Dues
- View Reports & Audit Logs
- Student Verification

---

## Finance Officer

Responsible for

- Record Payments
- Print Receipts
- Manage Invoices
- Verify Payments

---

## Auditor

Read-only access.

Can view

- Payments
- Reports
- Audit Logs

Cannot modify data.

---

## Student

Students can:

- Login
- View Profile
- View Invoices
- View Payment History
- Download Receipts
- Receive Notifications
- View Outstanding Balance
- Make Online Payments (Simulated Mobile Money & Card checkouts)

---

# Technology Stack

Backend

- Laravel 12
- PHP 8.3+

Frontend

- Bootstrap 5
- Blade Templates
- JavaScript
- Chart.js

Database

- MySQL 8

Authentication

- Laravel Breeze

Permissions

- Spatie Laravel Permission

PDF

- Laravel DomPDF

Excel

- Laravel Excel

Image Upload

- Intervention Image

Icons

- Font Awesome

---

# System Architecture

Presentation Layer

↓

Controllers

↓

Services

↓

Repositories

↓

Eloquent Models

↓

MySQL Database

Laravel follows the MVC architecture for better scalability and maintainability.

---

# Student Academic Lifecycle

Admission

↓

Level 100

↓

Payment

↓

Promotion

↓

Level 200

↓

Payment

↓

Promotion

↓

Level 300

↓

Payment

↓

Promotion

↓

Level 400

↓

Graduation

↓

Alumni

Every stage is recorded permanently.

---

# Database Modules

Core Tables

users

roles

permissions

students

programmes

academic_sessions

academic_levels

student_academic_records

dues

invoices

invoice_items

payments

payment_methods

receipts

notifications

promotion_logs

audit_logs

system_settings

---

# Project Structure

app/

    Models/

    Services/

    Repositories/

    Policies/

    Notifications/

    Jobs/

    Mail/

    Http/

resources/

    views/

routes/

database/

storage/

public/

tests/

---

# Installation

Clone repository

git clone https://github.com/username/sfms.git

Install packages

composer install

Install Node packages

npm install

Create environment

cp .env.example .env

Generate key

php artisan key:generate

Configure database

Run migrations

php artisan migrate

Run seeders

php artisan db:seed

Start application

php artisan serve

---

# Default Accounts

HOD / Administrator

hod@compssa.edu.gh

password

Finance Officer

finance@compssa.edu.gh

password

Student

student@htu.edu.gh

password

---

# Security

Laravel provides:

- CSRF Protection
- SQL Injection Protection
- XSS Protection
- Authentication
- Authorization
- Password Hashing
- Email Verification
- Middleware
- Validation
- Rate Limiting

Additional features include:

- Audit Logging
- Soft Deletes
- Activity Monitoring
- Permission Policies

---

# Development Roadmap

Phase 1

✔ Authentication

✔ User Roles

✔ Student Module

✔ Programme Module

Phase 2

✔ Dues Management

✔ Invoice Module

✔ Payments

✔ Receipts

Phase 3

✔ Student Promotion

✔ Notifications

✔ Reports

✔ Dashboard

Phase 4

✔ Mobile Responsiveness

✔ Email Services

✔ API Development

✔ Performance Optimization

---

# Future Improvements

- Mobile Application
- Student Portal API
- Mobile Money Integration
- QR Code Student ID
- SMS Notifications
- Email Notifications
- Online Payments
- AI Financial Analytics
- Predictive Revenue Dashboard
- Attendance Integration
- Fingerprint Verification
- Multi-Faculty Support
- Multi-Tenant University Support

---

# License

This project was developed as an academic and enterprise software engineering project for the Ho Technical University Computer Science Students Association (COMPSSA).

---

# Authors

Developed by:

HTU COMPSSA Software Development Team

Laravel Version

12.x

PHP Version

8.3+

Database

MySQL 8+

Framework

Laravel

Status

🚧 Under Active Development
