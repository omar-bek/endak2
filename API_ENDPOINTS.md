# API Endpoints Documentation

## Base URL
```
/api/v1
```

## Authentication Endpoints

### Register (Public)
- **Method**: `POST`
- **URL**: `/api/v1/auth/register`
- **Headers**: `Content-Type: application/json`
- **Body**:
```json
{
  "name": "string (required)",
  "email": "string (required, unique)",
  "password": "string (required, min:8)",
  "password_confirmation": "string (required)",
  "phone": "string (optional, unique)",
  "user_type": "customer|provider (optional, default: customer)"
}
```
- **Response**: `201 Created`
```json
{
  "success": true,
  "message": "تم إنشاء الحساب بنجاح",
  "data": {
    "token": "api_token_here",
    "user": { ... }
  }
}
```

### Login (Public)
- **Method**: `POST`
- **URL**: `/api/v1/auth/login`
- **Headers**: `Content-Type: application/json`
- **Body**:
```json
{
  "email": "string (required)",
  "password": "string (required)"
}
```
- **Response**: `200 OK`
```json
{
  "success": true,
  "message": "تم تسجيل الدخول بنجاح",
  "data": {
    "token": "api_token_here",
    "user": { ... }
  }
}
```

### Logout (Authenticated)
- **Method**: `POST`
- **URL**: `/api/v1/auth/logout`
- **Headers**: 
  - `Authorization: Bearer {token}`
  - `Content-Type: application/json`

### Get Profile (Authenticated)
- **Method**: `GET`
- **URL**: `/api/v1/auth/profile`
- **Headers**: `Authorization: Bearer {token}`

### Update Profile (Authenticated)
- **Method**: `PUT`
- **URL**: `/api/v1/auth/profile`
- **Headers**: 
  - `Authorization: Bearer {token}`
  - `Content-Type: application/json`
- **Body**:
```json
{
  "name": "string (optional)",
  "phone": "string (optional)",
  "bio": "string (optional)",
  "user_type": "customer|provider (optional)"
}
```

## Important Notes

1. **API Token**: After login/register, you'll receive a token. Use it in the `Authorization` header as `Bearer {token}` for authenticated endpoints.

2. **Base URL**: Make sure your mobile app uses the correct base URL:
   - Development: `http://localhost/api/v1` or `http://your-domain.test/api/v1`
   - Production: `https://your-domain.com/api/v1`

3. **Content-Type**: Always send `Content-Type: application/json` header for POST/PUT requests.

4. **Error Responses**: All errors follow this format:
```json
{
  "success": false,
  "message": "Error message here"
}
```

## Testing the API

You can test the API using:
- Postman
- cURL
- Your mobile app

Example cURL for login:
```bash
curl -X POST http://your-domain.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

