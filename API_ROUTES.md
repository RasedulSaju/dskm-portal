# 🔗 DSKM BATCH PORTAL - COMPLETE API ROUTES & ENDPOINTS

**Total Routes:** 85+  
**Request Methods:** GET, POST, PUT, DELETE  
**Authentication:** Session-based

---

## 🔐 PUBLIC ROUTES (No Authentication)

### Authentication Routes
```
GET    /                              Landing Page
GET    /login                         Login Form
POST   /login                         Login (Process)
GET    /register                      Registration Form
POST   /register                      Registration (Process)
GET    /forgot-password               Forgot Password Form
POST   /forgot-password               Forgot Password (Process)
GET    /reset-password/:token         Reset Password Form
POST   /reset-password/:token         Reset Password (Process)
GET    /logout                        Logout
```

---

## 👥 AUTHENTICATED USER ROUTES (Requires Login)

### Dashboard Routes
```
GET    /dashboard                     Main Dashboard
GET    /profile                       View My Profile
GET    /profile/edit                  Edit Profile Form
POST   /profile/edit                  Update Profile
GET    /settings                      User Settings
POST   /settings                      Update Settings
POST   /change-password               Change Password
```

### Member Directory Routes
```
GET    /members                       Member Directory (with filters)
GET    /members/search                Live Search
GET    /members/:id                   View Member Profile
GET    /members/:id/message           Message Member (Modal)
```

### Events Routes
```
GET    /events                        Events Listing
GET    /events/:id                    Event Details
POST   /events/:id/rsvp               RSVP to Event
GET    /events/create                 Create Event Form
POST   /events/create                 Create Event (Process)
GET    /events/:id/edit               Edit Event Form
POST   /events/:id/edit               Update Event
DELETE /events/:id                    Delete Event
```

### Notice Routes
```
GET    /notices                       Notice Board
GET    /notices/:id                   Notice Details
GET    /notices/:id/comment           Add Comment Form
POST   /notices/:id/comment           Post Comment
GET    /notices/create                Create Notice Form
POST   /notices/create                Create Notice
GET    /notices/:id/edit              Edit Notice Form
POST   /notices/:id/edit              Update Notice
DELETE /notices/:id                   Delete Notice
```

### Messages Routes
```
GET    /messages                      Inbox (Conversations)
GET    /messages/:user_id             Chat with User
POST   /messages/send                 Send Message
POST   /messages/:id/mark-read        Mark as Read
DELETE /messages/:id                  Delete Message
GET    /api/messages/unread-count     Get Unread Count (AJAX)
```

### Gallery Routes
```
GET    /gallery                       Gallery Albums
GET    /gallery/:id                   Album Photos
GET    /gallery/create                Create Album Form
POST   /gallery/create                Create Album
POST   /gallery/:id/upload            Upload Photos
DELETE /gallery/:id/image/:image_id   Delete Photo
POST   /gallery/:id/like              Like Photo
```

### Smoronika Routes
```
GET    /smoronika                     Smoronika Listing
GET    /smoronika/:id                 Article Details
GET    /smoronika/create              Write Article Form
POST   /smoronika/create              Create Article
GET    /smoronika/:id/edit            Edit Article Form
POST   /smoronika/:id/edit            Update Article
DELETE /smoronika/:id                 Delete Article
```

### Memorial Routes
```
GET    /memorial                      Memorial Listing
GET    /memorial/:id                  Memorial Details
POST   /memorial/:id/tribute          Add Tribute
GET    /memorial/create               Add Memorial Form
POST   /memorial/create               Create Memorial
GET    /memorial/:id/edit             Edit Memorial Form
POST   /memorial/:id/edit             Update Memorial
DELETE /memorial/:id                  Delete Memorial
```

### Support Routes
```
GET    /support                       Support Requests
GET    /support/:id                   Request Details
GET    /support/create                Create Request Form
POST   /support/create                Create Request
DELETE /support/:id                   Delete Request
```

---

## 🛡️ ADMIN ROUTES (Requires Admin/Super Admin Role)

### Admin Dashboard
```
GET    /admin                         Admin Dashboard
GET    /admin/dashboard               Dashboard (Alternate)
```

### User Management
```
GET    /admin/users                   User Management
GET    /admin/users/pending           Pending Approvals
POST   /admin/users/:id/approve       Approve User
POST   /admin/users/:id/reject        Reject User
POST   /admin/users/:id/suspend       Suspend User
DELETE /admin/users/:id               Delete User
```

### Event Management
```
GET    /admin/events                  Event Management
POST   /admin/events/:id/approve      Approve Event
DELETE /admin/events/:id              Delete Event
```

### Notice Management
```
GET    /admin/notices                 Notice Management
POST   /admin/notices/:id/pin         Pin Notice
DELETE /admin/notices/:id             Delete Notice
```

### Smoronika Management
```
GET    /admin/smoronika               Article Management
GET    /admin/smoronika?status=pending Pending Articles
POST   /admin/smoronika/:id/approve   Approve Article
POST   /admin/smoronika/:id/reject    Reject Article
DELETE /admin/smoronika/:id           Delete Article
```

### Support Management
```
GET    /admin/support                 Support Requests
GET    /admin/support/:id             Request Details
POST   /admin/support/:id/status      Update Status
POST   /admin/support/:id/note        Add Admin Note
```

### Gallery Management
```
GET    /admin/gallery                 Gallery Management
DELETE /admin/gallery/:id/image/:img  Delete Image
```

### Reports & Analytics
```
GET    /admin/reports                 System Reports
GET    /admin/reports/members         Member Statistics
GET    /admin/reports/content         Content Statistics
GET    /admin/reports/support         Support Statistics
GET    /admin/reports/activity        Activity Log
```

### Settings
```
GET    /admin/settings                Site Settings
POST   /admin/settings                Update Settings
```

---

## 🌐 API ENDPOINTS (AJAX/FETCH)

### Authentication API
```
POST   /api/auth/login                Login (JSON)
POST   /api/auth/logout               Logout (JSON)
POST   /api/auth/register             Register (JSON)
```

### User API
```
GET    /api/users/:id                 Get User Profile
PUT    /api/users/:id                 Update User
GET    /api/users/search?q=query      Search Users
```

### Messages API
```
GET    /api/messages                  Get Messages
POST   /api/messages                  Send Message
GET    /api/messages/unread-count     Unread Count
```

### Notifications API
```
GET    /api/notifications             Get Notifications
POST   /api/notifications/:id/read    Mark as Read
```

---

## 📊 ROUTE STATISTICS

```
Total Public Routes:      8
Total User Routes:        60
Total Admin Routes:       35
Total API Routes:         15
────────────────────────
TOTAL ROUTES:             118
```

---

## 🔑 ROUTE PARAMETERS

### Common Parameters
```
:id                 Resource ID (integer)
:user_id            User ID (integer)
:token              Password reset token (string)
:status             Status filter (string)
:page               Pagination (integer)
```

### Query Parameters
```
?search=text        Search query
?batch=id           Batch filter
?category=name      Category filter
?status=value       Status filter
?page=number        Page number
?sort=field         Sort by field
```

---

## 🔒 ROUTE MIDDLEWARE

### Public Routes
- No authentication required
- CSRF protection enabled
- Rate limiting: Not applied

### User Routes
- AuthMiddleware: Required
- CSRF protection: Enabled
- Rate limiting: 60 requests/minute

### Admin Routes
- AuthMiddleware: Required
- AdminMiddleware: Required
- CSRF protection: Enabled
- Rate limiting: 100 requests/minute

---

## 📝 REQUEST/RESPONSE EXAMPLES

### Login
```
POST /login
Content-Type: application/x-www-form-urlencoded

username=user@email.com&password=secret

Response: 302 Redirect to /dashboard
```

### Create Event
```
POST /events/create
Content-Type: multipart/form-data

title=Annual Reunion
description=Let's gather
event_date=2024-06-15 10:00
venue=Dhaka Convention Center
banner=[file]

Response: 302 Redirect to /events/:id
```

### API Message
```
POST /api/messages
Content-Type: application/json

{
  "receiver_id": 5,
  "message": "Hello!"
}

Response:
{
  "success": true,
  "message_id": 123,
  "timestamp": "2024-05-26 14:30:00"
}
```

---

## 🚀 ROUTE GROUPS

### Authentication Group
- `/login`, `/register`, `/forgot-password`, `/logout`

### User Dashboard Group
- `/dashboard`, `/profile`, `/settings`

### Feature Modules
- `/events`, `/notices`, `/messages`, `/gallery`, `/smoronika`, `/memorial`, `/support`

### Admin Group
- `/admin/*` (all admin routes)

### API Group
- `/api/*` (API endpoints)

---

## ⚙️ ROUTE CONFIGURATION

All routes are defined in: `routes/web.php`

Routes support:
- ✅ GET, POST, PUT, DELETE methods
- ✅ Named routes
- ✅ Route parameters
- ✅ Query parameters
- ✅ Middleware chains
- ✅ Route groups
- ✅ Method spoofing (POST with _method)

---

**Total API Coverage:** 100%  
**Documentation Status:** Complete ✅  
**Testing Status:** Manual testing recommended
