# 🔍 دليل الفلترة - Filtering Guide

## ✅ APIs التي تدعم الفلترة

---

## 1️⃣ طلبات المسوقين (Warehouse)

### عرض جميع طلبات المسوقين
```http
GET /api/warehouse/requests
Authorization: Bearer {token}
Role: warehouse_keeper
```

### الفلترة المتاحة:
```http
# حسب الحالة
GET /api/warehouse/requests?status=pending
GET /api/warehouse/requests?status=approved
GET /api/warehouse/requests?status=documented

# حسب المسوق
GET /api/warehouse/requests?marketer_id=3

# حسب التاريخ
GET /api/warehouse/requests?from_date=2024-01-01
GET /api/warehouse/requests?to_date=2024-12-31
GET /api/warehouse/requests?from_date=2024-01-01&to_date=2024-01-31

# دمج الفلاتر
GET /api/warehouse/requests?status=pending&marketer_id=3
GET /api/warehouse/requests?marketer_id=3&from_date=2024-01-01&to_date=2024-01-31
```

**الحالات المتاحة:**
- `pending` - قيد الانتظار
- `approved` - موافق عليه
- `documented` - موثق
- `rejected` - مرفوض
- `cancelled` - ملغي

---

## 2️⃣ فواتير البيع (Warehouse)

### عرض جميع فواتير البيع
```http
GET /api/warehouse/sales
Authorization: Bearer {token}
Role: warehouse_keeper
```

### الفلترة المتاحة:
```http
# حسب الحالة
GET /api/warehouse/sales?status=pending
GET /api/warehouse/sales?status=approved
GET /api/warehouse/sales?status=rejected

# حسب المسوق
GET /api/warehouse/sales?marketer_id=3

# حسب المتجر
GET /api/warehouse/sales?store_id=5

# حسب التاريخ
GET /api/warehouse/sales?from_date=2024-01-01
GET /api/warehouse/sales?to_date=2024-12-31

# دمج الفلاتر
GET /api/warehouse/sales?status=pending&marketer_id=3
GET /api/warehouse/sales?store_id=5&from_date=2024-01-01
GET /api/warehouse/sales?marketer_id=3&store_id=5&status=approved
```

**الحالات المتاحة:**
- `pending` - قيد الانتظار
- `approved` - موثق
- `rejected` - مرفوض
- `cancelled` - ملغي

---

## 3️⃣ إرجاعات المتاجر (Warehouse)

### عرض جميع إرجاعات المتاجر
```http
GET /api/warehouse/store-returns
Authorization: Bearer {token}
Role: warehouse_keeper
```

### الفلترة المتاحة:
```http
# حسب الحالة
GET /api/warehouse/store-returns?status=pending
GET /api/warehouse/store-returns?status=approved

# حسب المسوق
GET /api/warehouse/store-returns?marketer_id=3

# حسب المتجر
GET /api/warehouse/store-returns?store_id=5

# حسب التاريخ
GET /api/warehouse/store-returns?from_date=2024-01-01
GET /api/warehouse/store-returns?to_date=2024-12-31

# دمج الفلاتر
GET /api/warehouse/store-returns?status=pending&marketer_id=3
GET /api/warehouse/store-returns?store_id=5&status=approved
GET /api/warehouse/store-returns?marketer_id=3&from_date=2024-01-01&to_date=2024-01-31
```

**الحالات المتاحة:**
- `pending` - قيد الانتظار
- `approved` - موثق
- `rejected` - مرفوض
- `cancelled` - ملغي

---

## 4️⃣ فواتير البيع (Marketer)

### عرض فواتير المسوق
```http
GET /api/marketer/sales
Authorization: Bearer {token}
Role: salesman
```

### الفلترة المتاحة:
```http
# حسب الحالة
GET /api/marketer/sales?status=pending
GET /api/marketer/sales?status=approved
GET /api/marketer/sales?status=rejected
```

**ملاحظة:** المسوق يرى فواتيره فقط (تلقائياً)

---

## 📊 ملخص الفلاتر المتاحة

### حسب النوع:

| Filter | الوصف | مثال | APIs المدعومة |
|--------|-------|------|---------------|
| `status` | الحالة | `?status=pending` | جميع APIs |
| `marketer_id` | المسوق | `?marketer_id=3` | Warehouse APIs |
| `store_id` | المتجر | `?store_id=5` | Sales, Returns |
| `from_date` | من تاريخ | `?from_date=2024-01-01` | Warehouse APIs |
| `to_date` | إلى تاريخ | `?to_date=2024-12-31` | Warehouse APIs |

---

## 🎯 أمثلة عملية

### مثال 1: طلبات مسوق معين في حالة pending
```http
GET /api/warehouse/requests?marketer_id=3&status=pending
```

**الاستخدام:** أمين المخزن يريد رؤية الطلبات المعلقة لمسوق محدد

---

### مثال 2: فواتير متجر معين خلال شهر
```http
GET /api/warehouse/sales?store_id=5&from_date=2024-01-01&to_date=2024-01-31
```

**الاستخدام:** أمين المخزن يريد رؤية مبيعات متجر خلال شهر محدد

---

### مثال 3: إرجاعات مسوق معين الموثقة
```http
GET /api/warehouse/store-returns?marketer_id=3&status=approved
```

**الاستخدام:** أمين المخزن يريد رؤية الإرجاعات الموثقة لمسوق محدد

---

### مثال 4: فواتير مسوق لمتجر معين
```http
GET /api/warehouse/sales?marketer_id=3&store_id=5
```

**الاستخدام:** أمين المخزن يريد رؤية مبيعات مسوق محدد لمتجر محدد

---

### مثال 5: طلبات خلال فترة زمنية
```http
GET /api/warehouse/requests?from_date=2024-01-01&to_date=2024-01-31
```

**الاستخدام:** أمين المخزن يريد رؤية جميع الطلبات خلال شهر يناير

---

## 📝 ملاحظات مهمة

### 1. دمج الفلاتر
يمكن دمج أكثر من فلتر في نفس الطلب:
```http
GET /api/warehouse/sales?marketer_id=3&store_id=5&status=pending&from_date=2024-01-01
```

### 2. حساسية الحالة
القيم حساسة لحالة الأحرف:
- ✅ `status=pending`
- ❌ `status=Pending`
- ❌ `status=PENDING`

### 3. صيغة التاريخ
استخدم صيغة: `YYYY-MM-DD`
- ✅ `from_date=2024-01-01`
- ❌ `from_date=01-01-2024`
- ❌ `from_date=2024/01/01`

### 4. القيم غير الصحيحة
إذا كانت القيمة غير صحيحة، يتم تجاهل الفلتر:
```http
GET /api/warehouse/requests?status=invalid
# سيعرض جميع الطلبات (يتجاهل الفلتر)
```

### 5. الفلاتر الاختيارية
جميع الفلاتر اختيارية:
```http
GET /api/warehouse/requests
# بدون فلاتر = عرض الكل
```

---

## 🚀 APIs المخطط إضافة الفلترة لها

### قريباً:
- ⏳ `/api/warehouse/returns` - إرجاعات المسوقين
- ⏳ `/api/warehouse/payments` - إيصالات القبض
- ⏳ `/api/admin/sales` - فواتير البيع (Admin)
- ⏳ `/api/admin/withdrawals` - طلبات السحب

### الفلاتر المخططة:
- `product_id` - حسب المنتج
- `min_amount` - الحد الأدنى للمبلغ
- `max_amount` - الحد الأقصى للمبلغ
- `payment_method` - طريقة الدفع
- `sort_by` - الترتيب (date, amount)
- `order` - اتجاه الترتيب (asc, desc)

---

## 📊 Response Format

**مع الفلترة:**
```json
{
  "message": "قائمة طلبات المسوقين",
  "data": [
    {
      "id": 5,
      "status": "pending",
      "marketer_id": 3,
      "marketer_name": "محمد السالم"
    }
  ]
}
```

**بدون نتائج:**
```json
{
  "message": "قائمة طلبات المسوقين",
  "data": []
}
```

---

## ✅ APIs المحدثة

### تم إضافة الفلترة:
1. ✅ `GET /api/warehouse/requests` - طلبات المسوقين
2. ✅ `GET /api/warehouse/sales` - فواتير البيع
3. ✅ `GET /api/warehouse/store-returns` - إرجاعات المتاجر

### الفلاتر المضافة:
- ✅ `status` - الحالة
- ✅ `marketer_id` - المسوق
- ✅ `store_id` - المتجر (Sales & Returns)
- ✅ `from_date` - من تاريخ
- ✅ `to_date` - إلى تاريخ

---

**✅ النظام الآن يدعم الفلترة المتقدمة!**
