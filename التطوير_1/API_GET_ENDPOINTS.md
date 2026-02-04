# 📡 API GET Endpoints - جلب البيانات

## 🔐 Authentication Endpoints

### 1. الحصول على بيانات المستخدم الحالي
```
GET /api/auth/user
```
**Headers:** `Authorization: Bearer {token}`  
**Response:** بيانات المستخدم المسجل دخوله

---

## 👥 User Management (Admin Only)

### 2. عرض جميع المستخدمين
```
GET /api/users
```
**Role:** admin  
**Response:** قائمة المستخدمين مع الأدوار ونسب العمولات

### 3. عرض الأدوار المتاحة
```
GET /api/roles
```
**Role:** admin  
**Response:** قائمة الأدوار (admin, warehouse_keeper, salesman)

---

## 📦 Products Management

### 4. عرض جميع المنتجات
```
GET /api/products
```
**Response:** قائمة المنتجات مع كمية المخزن الرئيسي

---

## 🏪 Stores Management

### 5. عرض جميع المتاجر
```
GET /api/stores
```
**Response:** قائمة المتاجر النشطة

### 6. عرض دين متجر محدد
```
GET /api/stores/{id}/debt
```
**Response:** إجمالي الدين للمتجر

### 7. عرض جميع المتاجر مع الديون
```
GET /api/stores/debts
```
**Response:** قائمة المتاجر مع تفاصيل الديون (مبيعات، تسديدات، إرجاعات، الدين المتبقي)

### 8. عرض تفاصيل متجر مع سجل الديون
```
GET /api/stores/debts/{id}
```
**Response:** تفاصيل المتجر + ملخص الديون + سجل الحركات (store_debt_ledger)

---

## 📊 Stock Management

### 9. عرض المخزن الرئيسي
```
GET /api/warehouse/main-stock
```
**Response:** كميات المنتجات في المخزن الرئيسي

### 10. عرض مخزون المسوق الفعلي
```
GET /api/marketer/stock/actual
```
**Role:** salesman  
**Response:** مخزون المسوق الفعلي (marketer_actual_stock)

### 11. عرض مخزون المسوق المحجوز
```
GET /api/marketer/stock/reserved
```
**Role:** salesman  
**Response:** مخزون المسوق المحجوز (marketer_reserved_stock)

---

## 📝 Marketer Requests (طلبات البضاعة)

### 12. عرض طلبات المسوق
```
GET /api/marketer/requests
```
**Role:** salesman  
**Response:** قائمة طلبات المسوق من المخزن

### 13. عرض تفاصيل طلب محدد (للمسوق)
```
GET /api/marketer/requests/{id}
```
**Role:** salesman  
**Response:** تفاصيل الطلب + بنود الطلب + أسماء الموافقين

### 14. عرض جميع طلبات المسوقين (للمخزن)
```
GET /api/warehouse/requests
```
**Role:** warehouse_keeper  
**Response:** قائمة جميع طلبات المسوقين

### 15. عرض تفاصيل طلب محدد (للمخزن)
```
GET /api/warehouse/requests/{id}
```
**Role:** warehouse_keeper  
**Response:** تفاصيل الطلب + بنود الطلب + أسماء المسوق والموافقين

---

## 🔄 Marketer Returns (إرجاع بضاعة من المسوق)

### 16. عرض إرجاعات المسوق
```
GET /api/marketer/returns
```
**Role:** salesman  
**Response:** قائمة إرجاعات المسوق للمخزن

### 17. عرض تفاصيل إرجاع محدد (للمسوق)
```
GET /api/marketer/returns/{id}
```
**Role:** salesman  
**Response:** تفاصيل الإرجاع + بنود الإرجاع

### 18. عرض جميع إرجاعات المسوقين (للمخزن)
```
GET /api/warehouse/returns
```
**Role:** warehouse_keeper  
**Response:** قائمة جميع إرجاعات المسوقين

### 19. عرض تفاصيل إرجاع محدد (للمخزن)
```
GET /api/warehouse/returns/{id}
```
**Role:** warehouse_keeper  
**Response:** تفاصيل الإرجاع + بنود الإرجاع

---

## 💰 Sales Invoices (فواتير البيع)

### 20. عرض فواتير بيع المسوق
```
GET /api/marketer/sales
```
**Role:** salesman  
**Query Params:** `?status=pending|approved|cancelled|rejected`  
**Response:** قائمة فواتير البيع للمسوق

### 21. عرض تفاصيل فاتورة بيع (للمسوق)
```
GET /api/marketer/sales/{id}
```
**Role:** salesman  
**Response:** تفاصيل الفاتورة + بنود الفاتورة + اسم المتجر

### 22. عرض معلومات رفض فاتورة (للمسوق)
```
GET /api/marketer/sales/{id}/rejection
```
**Role:** salesman  
**Response:** سبب الرفض + اسم الرافض + تاريخ الرفض

### 23. عرض جميع فواتير البيع (للمخزن)
```
GET /api/warehouse/sales
```
**Role:** warehouse_keeper  
**Response:** قائمة جميع فواتير البيع

### 24. عرض تفاصيل فاتورة بيع (للمخزن)
```
GET /api/warehouse/sales/{id}
```
**Role:** warehouse_keeper  
**Response:** تفاصيل الفاتورة + بنود الفاتورة

### 25. عرض معلومات رفض فاتورة (للمخزن)
```
GET /api/warehouse/sales/{id}/rejection
```
**Role:** warehouse_keeper  
**Response:** سبب الرفض + اسم الرافض

### 26. عرض جميع فواتير البيع (للإدارة)
```
GET /api/admin/sales
```
**Role:** admin  
**Response:** قائمة جميع فواتير البيع مع أسماء المسوقين والمتاجر

### 27. عرض تفاصيل فاتورة بيع (للإدارة)
```
GET /api/admin/sales/{id}
```
**Role:** admin  
**Response:** تفاصيل الفاتورة + بنود الفاتورة

### 28. عرض معلومات رفض فاتورة (للإدارة)
```
GET /api/admin/sales/{id}/rejection
```
**Role:** admin  
**Response:** سبب الرفض + اسم الرافض

---

## 💵 Store Payments (إيصالات القبض)

### 29. عرض إيصالات قبض المسوق
```
GET /api/marketer/payments
```
**Role:** salesman  
**Response:** قائمة إيصالات القبض للمسوق

### 30. عرض تفاصيل إيصال قبض (للمسوق)
```
GET /api/marketer/payments/{id}
```
**Role:** salesman  
**Response:** تفاصيل إيصال القبض + اسم المتجر

### 31. عرض جميع إيصالات القبض (للمخزن)
```
GET /api/warehouse/payments
```
**Role:** warehouse_keeper  
**Response:** قائمة جميع إيصالات القبض

### 32. عرض تفاصيل إيصال قبض (للمخزن)
```
GET /api/warehouse/payments/{id}
```
**Role:** warehouse_keeper  
**Response:** تفاصيل إيصال القبض

---

## 🔙 Store Returns (إرجاع بضاعة من المتجر)

### 33. عرض إرجاعات المتاجر (للمسوق)
```
GET /api/marketer/store-returns
```
**Role:** salesman  
**Response:** قائمة إرجاعات المتاجر للمسوق

### 34. عرض تفاصيل إرجاع من متجر (للمسوق)
```
GET /api/marketer/store-returns/{id}
```
**Role:** salesman  
**Response:** تفاصيل الإرجاع + بنود الإرجاع

### 35. عرض جميع إرجاعات المتاجر (للمخزن)
```
GET /api/warehouse/store-returns
```
**Role:** warehouse_keeper  
**Response:** قائمة جميع إرجاعات المتاجر

### 36. عرض تفاصيل إرجاع من متجر (للمخزن)
```
GET /api/warehouse/store-returns/{id}
```
**Role:** warehouse_keeper  
**Response:** تفاصيل الإرجاع + بنود الإرجاع

---

## 💸 Marketer Withdrawals (سحب الأرباح)

### 37. عرض طلبات سحب المسوق
```
GET /api/marketer/withdrawals
```
**Role:** salesman  
**Response:** قائمة طلبات سحب الأرباح للمسوق

### 38. عرض رصيد المسوق المتاح
```
GET /api/marketer/withdrawals/balance
```
**Role:** salesman  
**Response:** الرصيد المتاح = (مجموع العمولات - مجموع السحوبات)

### 39. عرض تفاصيل طلب سحب (للمسوق)
```
GET /api/marketer/withdrawals/{id}
```
**Role:** salesman  
**Response:** تفاصيل طلب السحب

### 40. عرض جميع طلبات السحب (للإدارة)
```
GET /api/admin/withdrawals
```
**Role:** admin  
**Response:** قائمة جميع طلبات سحب الأرباح

### 41. عرض تفاصيل طلب سحب (للإدارة)
```
GET /api/admin/withdrawals/{id}
```
**Role:** admin  
**Response:** تفاصيل طلب السحب + اسم المسوق

---

## 👨‍💼 Marketers Management (Admin)

### 42. عرض جميع المسوقين مع العمولات
```
GET /api/admin/marketers
```
**Role:** admin  
**Response:** قائمة المسوقين + إجمالي العمولات + إجمالي السحوبات + الرصيد المتاح

---

## 🎁 Product Promotions (العروض الترويجية)

### 43. عرض جميع العروض الترويجية (للإدارة)
```
GET /api/admin/promotions
```
**Role:** admin  
**Response:** قائمة جميع العروض الترويجية

### 44. عرض تفاصيل عرض ترويجي (للإدارة)
```
GET /api/admin/promotions/{id}
```
**Role:** admin  
**Response:** تفاصيل العرض + اسم المنتج + اسم المنشئ

### 45. عرض العروض الترويجية النشطة (للمسوق)
```
GET /api/marketer/promotions/active
```
**Role:** salesman  
**Response:** قائمة العروض النشطة حالياً

---

## 💳 Invoice Discounts (خصومات الفواتير)

### 46. عرض جميع خصومات الفواتير (للإدارة)
```
GET /api/admin/discounts
```
**Role:** admin  
**Response:** قائمة جميع خصومات الفواتير

### 47. عرض تفاصيل خصم فاتورة (للإدارة)
```
GET /api/admin/discounts/{id}
```
**Role:** admin  
**Response:** تفاصيل الخصم + اسم المنشئ

### 48. عرض خصومات الفواتير النشطة (للجميع)
```
GET /api/discounts/active
```
**Response:** قائمة الخصومات النشطة حالياً

### 49. عرض خصومات الفواتير النشطة (للمسوق)
```
GET /api/marketer/discounts/active
```
**Role:** salesman  
**Response:** قائمة الخصومات النشطة حالياً

---

## 📊 ملخص الإحصائيات

**إجمالي GET Endpoints: 49 endpoint**

### التصنيف حسب الوظيفة:
- **Authentication:** 1 endpoint
- **Users:** 2 endpoints
- **Products:** 1 endpoint
- **Stores:** 4 endpoints
- **Stock:** 3 endpoints
- **Marketer Requests:** 4 endpoints
- **Marketer Returns:** 4 endpoints
- **Sales Invoices:** 9 endpoints
- **Store Payments:** 4 endpoints
- **Store Returns:** 4 endpoints
- **Withdrawals:** 5 endpoints
- **Marketers Management:** 1 endpoint
- **Promotions:** 3 endpoints
- **Discounts:** 4 endpoints

### التصنيف حسب الدور:
- **Admin:** 10 endpoints
- **Warehouse Keeper:** 10 endpoints
- **Salesman (Marketer):** 16 endpoints
- **Shared/Public:** 13 endpoints

---

## 🔍 ملاحظات مهمة

### Authentication
جميع الـ Endpoints تتطلب:
```
Authorization: Bearer {token}
Accept: application/json
```

### Response Format
```json
{
  "message": "رسالة توضيحية",
  "data": { ... }
}
```

### Filtering
بعض الـ Endpoints تدعم Query Parameters للفلترة:
- `?status=pending` - حسب الحالة
- `?search=keyword` - البحث

### Pagination
حالياً لا يوجد pagination، يتم إرجاع جميع السجلات

---

## 📝 الجداول المستخدمة في GET Endpoints

1. `users` - المستخدمون
2. `roles` - الأدوار
3. `products` - المنتجات
4. `stores` - المتاجر
5. `main_stock` - المخزن الرئيسي
6. `marketer_actual_stock` - مخزون المسوق الفعلي
7. `marketer_reserved_stock` - مخزون المسوق المحجوز
8. `marketer_requests` - طلبات المسوق
9. `marketer_request_items` - بنود طلبات المسوق
10. `marketer_return_requests` - إرجاعات المسوق
11. `marketer_return_items` - بنود إرجاعات المسوق
12. `sales_invoices` - فواتير البيع
13. `sales_invoice_items` - بنود فواتير البيع
14. `sales_invoice_rejections` - رفض فواتير البيع
15. `store_payments` - إيصالات القبض
16. `store_debt_ledger` - دفتر ديون المتاجر
17. `sales_returns` - إرجاعات المتاجر
18. `sales_return_items` - بنود إرجاعات المتاجر
19. `marketer_commissions` - عمولات المسوقين
20. `marketer_withdrawal_requests` - طلبات سحب الأرباح
21. `product_promotions` - العروض الترويجية
22. `invoice_discount_tiers` - خصومات الفواتير

---

**✅ جميع GET Endpoints موثقة ومطبقة بشكل صحيح**
