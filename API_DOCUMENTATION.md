# User API Documentation

API untuk manajemen user dengan role-based access control (RBAC).

## Base URL
```
http://localhost:8001/api
```

## Authentication
API menggunakan Laravel Sanctum untuk autentikasi. Setelah login, gunakan token yang diterima di header:
```
Authorization: Bearer {token}
```

## Roles
- **mahasiswa**: Akses terbatas, hanya bisa melihat dan update profil sendiri
- **dosen**: Akses menengah, bisa melihat daftar user dan detail user
- **admin**: Akses penuh, bisa melakukan semua operasi CRUD

## Endpoints

### Authentication

#### Register
```http
POST /auth/register
Content-Type: application/json

{
  "role_id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "nim": "2021001002",  // Required for mahasiswa
  "nip": "198501012010011002",  // Required for dosen
  "phone": "081234567890",
  "address": "Jl. Example No. 123",
  "gender": "L",  // L or P
  "birth_date": "2003-05-15"
}
```

#### Login
```http
POST /auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "1|...",
    "token_type": "Bearer"
  }
}
```

#### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

#### Get Current User
```http
GET /auth/me
Authorization: Bearer {token}
```

### Roles

#### List All Roles
```http
GET /roles
Authorization: Bearer {token}
```

### Users

#### List Users (Admin/Dosen only)
```http
GET /users?role=mahasiswa&search=john&per_page=10
Authorization: Bearer {token}
```

**Query Parameters:**
- `role`: Filter by role name (mahasiswa, dosen, admin)
- `search`: Search by name, email, nim, or nip
- `per_page`: Items per page (default: 15)

#### Create User (Admin only)
```http
POST /users
Authorization: Bearer {token}
Content-Type: application/json

{
  "role_id": 1,
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "nim": "2021001003"
}
```

#### Get User Details
```http
GET /users/{id}
Authorization: Bearer {token}
```

**Access:** Admin, Dosen, atau owner user tersebut

#### Update User
```http
PUT /users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name",
  "phone": "081234567899"
}
```

**Access:** Admin atau owner user tersebut
**Note:** Hanya admin yang bisa mengubah `role_id`

#### Delete User (Admin only)
```http
DELETE /users/{id}
Authorization: Bearer {token}
```

## Default Users
Database sudah di-seed dengan user berikut (password: `password`):

| Email | Role | NIM/NIP |
|-------|------|---------|
| admin@example.com | Admin | - |
| dosen@example.com | Dosen | 198501012010011001 |
| mahasiswa@example.com | Mahasiswa | 2021001001 |

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized. You do not have permission to access this resource."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "User not found"
}
```

### 422 Validation Error
```json
{
  "message": "The email field is required.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```
