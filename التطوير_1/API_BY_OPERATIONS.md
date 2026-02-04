# 📋 API Endpoints مرتبة حسب العمليات

---

## 1️⃣ طلب بضاعة من المسوق

### 🔵 المسوق (Salesman)

#### إنشاء طلب جديد
```http
POST /api/marketer/requests
Authorization: Bearer {token}
Content-Type: application/json

{
  "items": [
    {
      "product_id": 1,
      "quantity": 50
    },
    {
      "product_id": 2,
      "quantity": 30
    }
  ]
}
```

#### عرض جميع طلباتي
```http
GET /api/marketer/requests
Authorization: Bearer {token}
```

#### عرض تفاصيل طلب محدد
```http
GET /api/marketer/requests/{id}
Authorization: Bearer {token}
```

#### إلغاء طلب
```http
PUT /api/marketer/requests/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

### 🟢 أمين المخزن (Warehouse Keeper)

#### عرض جميع طلبات المسوقين
```http
GET /api/warehouse/requests
Authorization: Bearer {token}
```

#### عرض تفاصيل طلب محدد
```http
GET /api/warehouse/requests/{id}
Authorization: Bearer {token}
```

#### الموافقة على طلب
```http
PUT /api/warehouse/requests/{id}/approve
Authorization: Bearer {token}
```

#### رفض طلب
```http
PUT /api/warehouse/requests/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الرفض"
}
```

#### توثيق استلام البضاعة
```http
POST /api/warehouse/requests/{id}/document
Authorization: Bearer {token}
Content-Type: multipart/form-data

stamped_image: [file]
```

#### إلغاء طلب (من المخزن)
```http
PUT /api/warehouse/requests/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

## 2️⃣ إرجاع بضاعة من المسوق

### 🔵 المسوق (Salesman)

#### إنشاء طلب إرجاع
```http
POST /api/marketer/returns
Authorization: Bearer {token}
Content-Type: application/json

{
  "items": [
    {
      "product_id": 1,
      "quantity": 10
    }
  ]
}
```

#### عرض جميع إرجاعاتي
```http
GET /api/marketer/returns
Authorization: Bearer {token}
```

#### عرض تفاصيل إرجاع محدد
```http
GET /api/marketer/returns/{id}
Authorization: Bearer {token}
```

#### إلغاء إرجاع
```http
PUT /api/marketer/returns/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

### 🟢 أمين المخزن (Warehouse Keeper)

#### عرض جميع إرجاعات المسوقين
```http
GET /api/warehouse/returns
Authorization: Bearer {token}
```

#### عرض تفاصيل إرجاع محدد
```http
GET /api/warehouse/returns/{id}
Authorization: Bearer {token}
```

#### الموافقة على إرجاع
```http
PUT /api/warehouse/returns/{id}/approve
Authorization: Bearer {token}
```

#### رفض إرجاع
```http
PUT /api/warehouse/returns/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الرفض"
}
```

#### توثيق استلام البضاعة المرجعة
```http
POST /api/warehouse/returns/{id}/document
Authorization: Bearer {token}
Content-Type: multipart/form-data

stamped_image: [file]
```

---

## 3️⃣ بيع بضاعة للمتجر

### 🔵 المسوق (Salesman)

#### إنشاء فاتورة بيع
```http
POST /api/marketer/sales
Authorization: Bearer {token}
Content-Type: application/json

{
  "store_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 20
    }
  ],
  "notes": "ملاحظات اختيارية"
}
```

#### عرض جميع فواتير البيع
```http
GET /api/marketer/sales
Authorization: Bearer {token}

# مع فلترة حسب الحالة
GET /api/marketer/sales?status=pending
```

#### عرض تفاصيل فاتورة
```http
GET /api/marketer/sales/{id}
Authorization: Bearer {token}
```

#### عرض معلومات رفض الفاتورة
```http
GET /api/marketer/sales/{id}/rejection
Authorization: Bearer {token}
```

#### إلغاء فاتورة
```http
PUT /api/marketer/sales/{id}/cancel
Authorization: Bearer {token}
```

---

### 🟢 أمين المخزن (Warehouse Keeper)

#### عرض جميع فواتير البيع
```http
GET /api/warehouse/sales
Authorization: Bearer {token}
```

#### عرض تفاصيل فاتورة
```http
GET /api/warehouse/sales/{id}
Authorization: Bearer {token}
```

#### عرض معلومات رفض الفاتورة
```http
GET /api/warehouse/sales/{id}/rejection
Authorization: Bearer {token}
```

#### الموافقة والتوثيق
```http
POST /api/warehouse/sales/{id}/approve
Authorization: Bearer {token}
Content-Type: multipart/form-data

stamped_invoice_image: [file]
```

#### رفض فاتورة
```http
PUT /api/warehouse/sales/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "reason": "سبب الرفض"
}
```

---

### 🟡 الإدارة (Admin)

#### عرض جميع فواتير البيع
```http
GET /api/admin/sales
Authorization: Bearer {token}
```

#### عرض تفاصيل فاتورة
```http
GET /api/admin/sales/{id}
Authorization: Bearer {token}
```

#### عرض معلومات رفض الفاتورة
```http
GET /api/admin/sales/{id}/rejection
Authorization: Bearer {token}
```

---

## 4️⃣ إيصال القبض (تسديد دين المتجر)

### 🔵 المسوق (Salesman)

#### إنشاء إيصال قبض
```http
POST /api/marketer/payments
Authorization: Bearer {token}
Content-Type: application/json

{
  "store_id": 1,
  "amount": 5000,
  "payment_method": "cash",
  "notes": "ملاحظات اختيارية"
}
```
**payment_method:** `cash` | `transfer` | `certified_check`

#### عرض جميع إيصالات القبض
```http
GET /api/marketer/payments
Authorization: Bearer {token}
```

#### عرض تفاصيل إيصال قبض
```http
GET /api/marketer/payments/{id}
Authorization: Bearer {token}
```

#### إلغاء إيصال قبض
```http
PUT /api/marketer/payments/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

### 🟢 أمين المخزن (Warehouse Keeper)

#### عرض جميع إيصالات القبض
```http
GET /api/warehouse/payments
Authorization: Bearer {token}
```

#### عرض تفاصيل إيصال قبض
```http
GET /api/warehouse/payments/{id}
Authorization: Bearer {token}
```

#### الموافقة والتوثيق
```http
POST /api/warehouse/payments/{id}/approve
Authorization: Bearer {token}
Content-Type: multipart/form-data

receipt_image: [file]
```

#### رفض إيصال قبض
```http
PUT /api/warehouse/payments/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الرفض"
}
```

---

## 5️⃣ سحب أرباح المسوق

### 🔵 المسوق (Salesman)

#### عرض الرصيد المتاح
```http
GET /api/marketer/withdrawals/balance
Authorization: Bearer {token}
```

#### إنشاء طلب سحب
```http
POST /api/marketer/withdrawals
Authorization: Bearer {token}
Content-Type: application/json

{
  "requested_amount": 1000
}
```

#### عرض جميع طلبات السحب
```http
GET /api/marketer/withdrawals
Authorization: Bearer {token}
```

#### عرض تفاصيل طلب سحب
```http
GET /api/marketer/withdrawals/{id}
Authorization: Bearer {token}
```

#### إلغاء طلب سحب
```http
PUT /api/marketer/withdrawals/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

### 🟡 الإدارة (Admin)

#### عرض جميع طلبات السحب
```http
GET /api/admin/withdrawals
Authorization: Bearer {token}
```

#### عرض تفاصيل طلب سحب
```http
GET /api/admin/withdrawals/{id}
Authorization: Bearer {token}
```

#### الموافقة والصرف
```http
POST /api/admin/withdrawals/{id}/approve
Authorization: Bearer {token}
Content-Type: multipart/form-data

signed_receipt_image: [file]
```

#### رفض طلب سحب
```http
POST /api/admin/withdrawals/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الرفض"
}
```

---

## 6️⃣ إرجاع بضاعة من المتجر

### 🔵 المسوق (Salesman)

#### إنشاء طلب إرجاع من متجر
```http
POST /api/marketer/store-returns
Authorization: Bearer {token}
Content-Type: application/json

{
  "sales_invoice_id": 1,
  "store_id": 1,
  "items": [
    {
      "sales_invoice_item_id": 1,
      "product_id": 1,
      "quantity": 5
    }
  ]
}
```

#### عرض جميع إرجاعات المتاجر
```http
GET /api/marketer/store-returns
Authorization: Bearer {token}
```

#### عرض تفاصيل إرجاع
```http
GET /api/marketer/store-returns/{id}
Authorization: Bearer {token}
```

#### إلغاء إرجاع
```http
PUT /api/marketer/store-returns/{id}/cancel
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الإلغاء"
}
```

---

### 🟢 أمين المخزن (Warehouse Keeper)

#### عرض جميع إرجاعات المتاجر
```http
GET /api/warehouse/store-returns
Authorization: Bearer {token}
```

#### عرض تفاصيل إرجاع
```http
GET /api/warehouse/store-returns/{id}
Authorization: Bearer {token}
```

#### الموافقة والتوثيق
```http
POST /api/warehouse/store-returns/{id}/approve
Authorization: Bearer {token}
Content-Type: multipart/form-data

stamped_image: [file]
```

#### رفض إرجاع
```http
PUT /api/warehouse/store-returns/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "سبب الرفض"
}
```

---

## 7️⃣ إدارة خصومات الفواتير

### 🟡 الإدارة (Admin)

#### عرض جميع الخصومات
```http
GET /api/admin/discounts
Authorization: Bearer {token}
```

#### إنشاء خصم جديد
```http
POST /api/admin/discounts
Authorization: Bearer {token}
Content-Type: application/json

{
  "min_amount": 1000,
  "discount_type": "percentage",
  "discount_percentage": 5,
  "start_date": "2024-01-01",
  "end_date": "2024-12-31"
}
```
**discount_type:** `percentage` | `fixed`

#### عرض تفاصيل خصم
```http
GET /api/admin/discounts/{id}
Authorization: Bearer {token}
```

#### تفعيل/تعطيل خصم
```http
PUT /api/admin/discounts/{id}/toggle
Authorization: Bearer {token}
```

#### حذف خصم (soft delete)
```http
DELETE /api/admin/discounts/{id}
Authorization: Bearer {token}
```

---

### 🔵 المسوق (Salesman) - قراءة فقط

#### عرض الخصومات النشطة
```http
GET /api/marketer/discounts/active
Authorization: Bearer {token}
```

---

### 🌐 الجميع (Authenticated)

#### عرض الخصومات النشطة
```http
GET /api/discounts/active
Authorization: Bearer {token}
```

---

## 8️⃣ إدارة العروض الترويجية

### 🟡 الإدارة (Admin)

#### عرض جميع العروض
```http
GET /api/admin/promotions
Authorization: Bearer {token}
```

#### إنشاء عرض جديد
```http
POST /api/admin/promotions
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "min_quantity": 10,
  "free_quantity": 2,
  "start_date": "2024-01-01",
  "end_date": "2024-12-31"
}
```

#### عرض تفاصيل عرض
```http
GET /api/admin/promotions/{id}
Authorization: Bearer {token}
```

#### تفعيل/تعطيل عرض
```http
PUT /api/admin/promotions/{id}/toggle
Authorization: Bearer {token}
```

#### حذف عرض (soft delete)
```http
DELETE /api/admin/promotions/{id}
Authorization: Bearer {token}
```

---

### 🔵 المسوق (Salesman) - قراءة فقط

#### عرض العروض النشطة
```http
GET /api/marketer/promotions/active
Authorization: Bearer {token}
```

---

## 📊 عمليات إضافية

### المخزون

#### عرض مخزون المسوق الفعلي
```http
GET /api/marketer/stock/actual
Authorization: Bearer {token}
```

#### عرض مخزون المسوق المحجوز
```http
GET /api/marketer/stock/reserved
Authorization: Bearer {token}
```

#### عرض المخزن الرئيسي
```http
GET /api/warehouse/main-stock
Authorization: Bearer {token}
```

---

### المنتجات

#### عرض جميع المنتجات
```http
GET /api/products
```

#### إضافة منتج (Admin)
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "منتج جديد",
  "price": 100,
  "description": "وصف المنتج",
  "barcode": "123456"
}
```

#### تحديث منتج (Admin)
```http
PUT /api/products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "اسم محدث",
  "price": 120
}
```

---

### المتاجر

#### عرض جميع المتاجر
```http
GET /api/stores
```

#### إضافة متجر
```http
POST /api/stores
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "متجر جديد",
  "owner_name": "اسم المالك",
  "phone": "0500000000",
  "location": "الموقع",
  "address": "العنوان"
}
```

#### عرض دين متجر
```http
GET /api/stores/{id}/debt
Authorization: Bearer {token}
```

#### عرض جميع المتاجر مع الديون
```http
GET /api/stores/debts
```

#### عرض تفاصيل متجر مع سجل الديون
```http
GET /api/stores/debts/{id}
```

---

### المستخدمين (Admin)

#### عرض جميع المستخدمين
```http
GET /api/users
Authorization: Bearer {token}
```

#### إضافة مستخدم
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "username": "user1",
  "full_name": "اسم المستخدم",
  "password": "password123",
  "role_id": 3,
  "commission_rate": 5
}
```

#### تحديث مستخدم
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "username": "user1",
  "full_name": "اسم محدث",
  "role_id": 3
}
```

#### تفعيل/تعطيل مستخدم
```http
PUT /api/users/{id}/toggle-active
Authorization: Bearer {token}
```

#### عرض الأدوار
```http
GET /api/roles
Authorization: Bearer {token}
```

---

### المسوقين (Admin)

#### عرض جميع المسوقين مع العمولات
```http
GET /api/admin/marketers
Authorization: Bearer {token}
```

#### تحديث نسبة عمولة مسوق
```http
PUT /api/admin/marketers/{id}/commission-rate
Authorization: Bearer {token}
Content-Type: application/json

{
  "commission_rate": 7.5
}
```

---

## 🔐 المصادقة

#### تسجيل الدخول
```http
POST /api/auth/login
Content-Type: application/json

{
  "username": "admin",
  "password": "admin123"
}
```

#### تسجيل الخروج
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### الحصول على بيانات المستخدم الحالي
```http
GET /api/auth/user
Authorization: Bearer {token}
```

---

## 📝 ملاحظات

### Response Format
```json
{
  "message": "رسالة توضيحية",
  "data": { ... }
}
```

### Error Response
```json
{
  "message": "رسالة الخطأ",
  "error": "تفاصيل الخطأ"
}
```

### Status Codes
- `200` - نجاح
- `201` - تم الإنشاء
- `400` - خطأ في البيانات
- `401` - غير مصرح
- `403` - ممنوع
- `404` - غير موجود
- `500` - خطأ في الخادم

---

**✅ جميع API Endpoints مرتبة حسب العمليات**
