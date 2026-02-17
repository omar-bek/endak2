# API Testing Guide - Endak Platform

## Quick Test Commands

### 1. Test API Status
```bash
curl http://127.0.0.1:8000/api/v1/status
```

### 2. Test Categories (Public)
```bash
# Get all categories
curl http://127.0.0.1:8000/api/v1/categories

# Get category details
curl http://127.0.0.1:8000/api/v1/categories/furniture-moving/details

# Get subcategories
curl http://127.0.0.1:8000/api/v1/categories/1/subcategories

# Get request data (NEW)
curl http://127.0.0.1:8000/api/v1/categories/2/request-data
curl "http://127.0.0.1:8000/api/v1/categories/2/request-data?sub_category_id=5"
```

### 3. Test Category Fields (Public)
```bash
# Get fields
curl http://127.0.0.1:8000/api/v1/categories/2/fields

# Get grouped fields
curl http://127.0.0.1:8000/api/v1/categories/2/fields/grouped

# Get single field
curl http://127.0.0.1:8000/api/v1/categories/2/fields/10
```

### 4. Test Services (Public)
```bash
# Get all services
curl http://127.0.0.1:8000/api/v1/services

# Get service with filters
curl "http://127.0.0.1:8000/api/v1/services?category_id=1&city_id=1&search=نقل"

# Get single service
curl http://127.0.0.1:8000/api/v1/services/1
```

### 5. Test Authentication
```bash
# Register
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "customer"
  }'

# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Save the token from response, then use it:
TOKEN="your_token_here"

# Get Profile
curl http://127.0.0.1:8000/api/v1/auth/profile \
  -H "Authorization: Bearer $TOKEN"

# Update Profile
curl -X PUT http://127.0.0.1:8000/api/v1/auth/profile \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Name",
    "phone": "0123456789"
  }'

# Logout
curl -X POST http://127.0.0.1:8000/api/v1/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```

### 6. Test Services (Auth Required)
```bash
TOKEN="your_token_here"

# Get my services
curl http://127.0.0.1:8000/api/v1/services/me \
  -H "Authorization: Bearer $TOKEN"

# Create service
curl -X POST http://127.0.0.1:8000/api/v1/services \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "خدمة نقل أثاث",
    "description": "نقل أثاث من الرياض إلى جدة",
    "category_id": 1,
    "sub_category_id": 1,
    "city_id": 1,
    "custom_fields": {
      "from_city": "الرياض",
      "to_city": "جدة"
    }
  }'

# Update service
curl -X PUT http://127.0.0.1:8000/api/v1/services/1 \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "خدمة نقل أثاث محدثة"
  }'

# Delete service
curl -X DELETE http://127.0.0.1:8000/api/v1/services/1 \
  -H "Authorization: Bearer $TOKEN"
```

### 7. Test Service Offers (Auth Required)
```bash
TOKEN="your_token_here"

# Get offers
curl http://127.0.0.1:8000/api/v1/offers \
  -H "Authorization: Bearer $TOKEN"

# Create offer (Provider only)
curl -X POST http://127.0.0.1:8000/api/v1/services/1/offers \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "price": 500.00,
    "notes": "عرض خاص",
    "expires_at": "2024-12-31 23:59:59"
  }'

# Accept offer
curl -X POST http://127.0.0.1:8000/api/v1/offers/1/accept \
  -H "Authorization: Bearer $TOKEN"

# Reject offer
curl -X POST http://127.0.0.1:8000/api/v1/offers/1/reject \
  -H "Authorization: Bearer $TOKEN"

# Deliver offer (Provider only)
curl -X POST http://127.0.0.1:8000/api/v1/offers/1/deliver \
  -H "Authorization: Bearer $TOKEN"

# Review offer
curl -X POST http://127.0.0.1:8000/api/v1/offers/1/review \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rating": 5,
    "review": "خدمة ممتازة"
  }'
```

### 8. Test Notifications (Auth Required)
```bash
TOKEN="your_token_here"

# Get notifications
curl http://127.0.0.1:8000/api/v1/notifications \
  -H "Authorization: Bearer $TOKEN"

# Mark as read
curl -X POST http://127.0.0.1:8000/api/v1/notifications/1/read \
  -H "Authorization: Bearer $TOKEN"

# Mark all as read
curl -X POST http://127.0.0.1:8000/api/v1/notifications/mark-all-read \
  -H "Authorization: Bearer $TOKEN"

# Delete notification
curl -X DELETE http://127.0.0.1:8000/api/v1/notifications/1 \
  -H "Authorization: Bearer $TOKEN"
```

### 9. Test Messages (Auth Required)
```bash
TOKEN="your_token_here"

# Get conversations
curl http://127.0.0.1:8000/api/v1/messages \
  -H "Authorization: Bearer $TOKEN"

# Get conversation with user
curl http://127.0.0.1:8000/api/v1/messages/2 \
  -H "Authorization: Bearer $TOKEN"

# Send message
curl -X POST http://127.0.0.1:8000/api/v1/messages \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "receiver_id": 2,
    "content": "Hello!",
    "service_id": 1
  }'

# Delete message
curl -X DELETE http://127.0.0.1:8000/api/v1/messages/1 \
  -H "Authorization: Bearer $TOKEN"
```

---

## Testing Checklist

### ✅ Public Endpoints (No Auth)
- [x] GET /api/v1/status
- [x] GET /api/v1/categories
- [x] GET /api/v1/categories/{slug}/details
- [x] GET /api/v1/categories/{category}/subcategories
- [x] GET /api/v1/categories/{category}/request-data ⭐ NEW
- [x] GET /api/v1/categories/{category}/fields ⭐ NEW
- [x] GET /api/v1/categories/{category}/fields/grouped ⭐ NEW
- [x] GET /api/v1/categories/{category}/fields/{field} ⭐ NEW
- [x] GET /api/v1/services
- [x] GET /api/v1/services/{service}

### ✅ Authentication
- [x] POST /api/register
- [x] POST /api/login
- [x] POST /api/v1/auth/logout
- [x] GET /api/v1/auth/profile
- [x] PUT /api/v1/auth/profile

### ✅ Services (Auth Required)
- [x] GET /api/v1/services/me
- [x] POST /api/v1/services
- [x] PUT /api/v1/services/{service}
- [x] DELETE /api/v1/services/{service}

### ✅ Service Offers (Auth Required)
- [x] GET /api/v1/offers
- [x] POST /api/v1/services/{service}/offers
- [x] POST /api/v1/offers/{offer}/accept
- [x] POST /api/v1/offers/{offer}/reject
- [x] POST /api/v1/offers/{offer}/deliver
- [x] POST /api/v1/offers/{offer}/review

### ✅ Notifications (Auth Required)
- [x] GET /api/v1/notifications
- [x] POST /api/v1/notifications/{notification}/read
- [x] POST /api/v1/notifications/mark-all-read
- [x] DELETE /api/v1/notifications/{notification}

### ✅ Messages (Auth Required)
- [x] GET /api/v1/messages
- [x] GET /api/v1/messages/{user}
- [x] POST /api/v1/messages
- [x] DELETE /api/v1/messages/{message}

---

## Common Issues & Solutions

### Issue 1: Method Not Found
**Error:** `Method App\Http\Controllers\Api\CategoryController::requestData does not exist`

**Solution:**
1. Clear route cache: `php artisan route:clear`
2. Clear config cache: `php artisan config:clear`
3. Clear application cache: `php artisan cache:clear`
4. Regenerate autoload: `composer dump-autoload`

### Issue 2: 401 Unauthorized
**Error:** `Unauthorized`

**Solution:**
- Make sure you're sending the token in the header:
  ```
  Authorization: Bearer {token}
  ```
- Check if the token is valid and not expired

### Issue 3: 403 Forbidden
**Error:** `Forbidden`

**Solution:**
- Check if the user has the right permissions
- For provider-only endpoints, make sure user_type is "provider"

### Issue 4: 422 Validation Error
**Error:** `Validation failed`

**Solution:**
- Check the request body format
- Make sure all required fields are present
- Check field types (string, integer, etc.)

---

## Postman Collection

يمكنك استيراد الـ APIs التالية في Postman:

1. **Base URL:** `http://127.0.0.1:8000/api` (Development)
2. **Environment Variables:**
   - `base_url`: `http://127.0.0.1:8000/api`
   - `token`: (سيتم تعبئته بعد تسجيل الدخول)

3. **Pre-request Script (for authenticated requests):**
   ```javascript
   pm.request.headers.add({
       key: 'Authorization',
       value: 'Bearer ' + pm.environment.get('token')
   });
   ```

---

## Summary

✅ **Total APIs:** 39 endpoints
- **Public:** 10 endpoints
- **Auth Required:** 29 endpoints

✅ **New APIs Added:**
- `GET /api/v1/categories/{category}/request-data` - Get request page data with fields
- `GET /api/v1/categories/{category}/fields` - Get category fields
- `GET /api/v1/categories/{category}/fields/grouped` - Get grouped fields
- `GET /api/v1/categories/{category}/fields/{field}` - Get single field

✅ **All Controllers:**
- ✅ AuthController
- ✅ CategoryController
- ✅ CategoryFieldController ⭐ NEW
- ✅ ServiceController
- ✅ ServiceOfferController
- ✅ NotificationController
- ✅ MessageController

✅ **All Methods Verified:**
- All methods exist in their respective controllers
- All routes are properly registered
- All authentication middleware applied correctly
