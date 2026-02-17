# Endak Platform - Project Structure

## 📁 هيكل المشروع الكامل

```
endak1/
├── backend/              # Express.js Backend API
│   ├── config/          # إعدادات قاعدة البيانات
│   ├── controllers/      # Controllers
│   ├── middleware/       # Middleware (Auth, etc.)
│   ├── routes/           # API Routes
│   ├── utils/            # Utilities
│   ├── uploads/          # الملفات المرفوعة
│   ├── server.js         # نقطة البداية
│   └── package.json
│
├── frontend/            # React Frontend
│   ├── src/
│   │   ├── components/   # React Components
│   │   ├── pages/        # Pages
│   │   ├── store/        # State Management (Zustand)
│   │   ├── services/     # API Services
│   │   ├── App.jsx       # Main App
│   │   └── main.jsx     # Entry Point
│   ├── package.json
│   └── vite.config.js
│
└── [Laravel Files]      # Laravel Backend (موجود مسبقاً)
```

## 🚀 البدء السريع

### 1. Backend (Express.js)

```bash
cd backend
npm install
cp env.example .env
# عدّل ملف .env بإعدادات قاعدة البيانات
npm run dev
```

Backend سيعمل على: `http://localhost:3000`

### 2. Frontend (React)

```bash
cd frontend
npm install
cp env.example .env
npm run dev
```

Frontend سيعمل على: `http://localhost:5173`

## 📡 API Endpoints

### Authentication
- `POST /api/auth/login` - تسجيل الدخول
- `POST /api/auth/register` - التسجيل
- `GET /api/auth/me` - معلومات المستخدم
- `POST /api/auth/logout` - تسجيل الخروج

### Services
- `GET /api/services` - قائمة الخدمات
- `GET /api/services/:id` - تفاصيل خدمة
- `POST /api/services` - إنشاء خدمة
- `PUT /api/services/:id` - تحديث خدمة
- `DELETE /api/services/:id` - حذف خدمة

### Categories
- `GET /api/categories` - قائمة الأقسام
- `GET /api/categories/:id` - تفاصيل قسم
- `GET /api/categories/:id/cities` - مدن القسم

### Cities
- `GET /api/cities` - قائمة المدن
- `GET /api/cities/:id` - تفاصيل مدينة

### Messages
- `GET /api/messages/conversation/:userId` - محادثة
- `POST /api/messages` - إرسال رسالة
- `PUT /api/messages/:id/read` - تحديد كمقروء
- `DELETE /api/messages/:id` - حذف رسالة

### Notifications
- `GET /api/notifications` - قائمة الإشعارات
- `PUT /api/notifications/:id/read` - تحديد كمقروء
- `PUT /api/notifications/read-all` - تحديد الكل كمقروء
- `DELETE /api/notifications/:id` - حذف إشعار

## 🔐 Authentication

استخدم JWT Token أو API Token:

```
Authorization: Bearer <token>
```

أو:

```
X-API-Token: <api_token>
```

## 🛠️ Tech Stack

### Backend
- Node.js
- Express.js
- MySQL
- JWT Authentication
- Multer (File Upload)

### Frontend
- React 18
- React Router
- Zustand (State Management)
- React Query (Data Fetching)
- Axios
- React Hook Form
- React Hot Toast
- Vite

## 📝 ملاحظات مهمة

1. **قاعدة البيانات**: Backend Express.js يستخدم نفس قاعدة البيانات MySQL الخاصة بـ Laravel
2. **CORS**: تم إعداد CORS للسماح للـ frontend بالاتصال
3. **File Upload**: الملفات تُحفظ في `backend/uploads/`
4. **Environment Variables**: تأكد من إعداد ملفات `.env` بشكل صحيح

## 🔄 التكامل مع Laravel

- Backend Express.js يمكن أن يعمل بجانب Laravel API
- يمكن استخدام أي منهما حسب الحاجة
- قاعدة البيانات مشتركة بينهما

## 📚 Documentation

- `backend/README.md` - Backend Documentation
- `frontend/README.md` - Frontend Documentation
