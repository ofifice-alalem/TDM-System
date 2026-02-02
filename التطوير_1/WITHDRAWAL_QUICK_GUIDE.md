# عملية سحب أرباح المسوق - دليل سريع

## 📋 نظرة عامة

عملية سحب المسوق لأرباحه (العمولات المتراكمة) تمر بمرحلتين:
1. **المسوق** ينشئ طلب سحب
2. **الإدارة** توافق أو ترفض الطلب

## 🔌 API Endpoints

### للمسوق (Marketer)

```bash
# عرض قائمة طلبات السحب
GET /api/marketer/withdrawals

# عرض رصيد العمولات المتاح
GET /api/marketer/withdrawals/balance

# إنشاء طلب سحب جديد
POST /api/marketer/withdrawals
{
  "requested_amount": 1000
}

# عرض تفاصيل طلب
GET /api/marketer/withdrawals/{id}

# إلغاء طلب (pending فقط)
PUT /api/marketer/withdrawals/{id}/cancel
{
  "notes": "سبب الإلغاء"
}
```

### للإدارة (Admin)

```bash
# عرض جميع الطلبات
GET /api/admin/withdrawals?status=pending&marketer_id=1

# عرض تفاصيل طلب
GET /api/admin/withdrawals/{id}

# الموافقة على طلب
POST /api/admin/withdrawals/{id}/approve
Content-Type: multipart/form-data
signed_receipt_image: [file]

# رفض طلب
POST /api/admin/withdrawals/{id}/reject
{
  "notes": "سبب الرفض"
}
```

## 📊 حساب الرصيد المتاح

```
الرصيد المتاح = مجموع العمولات - مجموع السحوبات المعتمدة
```

## 🔄 دورة الحياة

```
pending → approved   (الإدارة توافق)
pending → rejected   (الإدارة ترفض)
pending → cancelled  (المسوق يلغي)
```

## ✅ القواعد

- ✅ لا يمكن السحب بمبلغ أكبر من الرصيد المتاح
- ✅ يجب رفع صورة إيصال موقع عند الموافقة
- ✅ يمكن للمسوق إلغاء الطلب في حالة pending فقط
- ✅ لا يمكن إلغاء طلب السحب بعد الموافقة عليه

## 📁 الملفات

- Model: `app/Models/MarketerWithdrawalRequest.php`
- Resource: `app/Http/Resources/MarketerWithdrawalResource.php`
- Controllers:
  - `app/Http/Controllers/Api/Marketer/MarketerWithdrawalController.php`
  - `app/Http/Controllers/Api/Admin/AdminWithdrawalController.php`
  - `app/Http/Controllers/Web/Marketer/WithdrawalController.php`
  - `app/Http/Controllers/Web/Admin/WithdrawalController.php`

## 🧪 اختبار سريع

```bash
# 1. المسوق يعرض رصيده
curl -X GET http://localhost:8000/api/marketer/withdrawals/balance \
  -H "Authorization: Bearer {token}"

# 2. المسوق يطلب سحب
curl -X POST http://localhost:8000/api/marketer/withdrawals \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"requested_amount": 500}'

# 3. الإدارة توافق
curl -X POST http://localhost:8000/api/admin/withdrawals/1/approve \
  -H "Authorization: Bearer {admin_token}" \
  -F "signed_receipt_image=@receipt.jpg"
```

## 🎯 الحالة

✅ **مكتمل 100%** - جاهز للاستخدام
