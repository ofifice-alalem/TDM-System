# 📊 APIs جلب القوائم الكاملة (List All)

---

## 🔐 1. المصادقة

### الحصول على بيانات المستخدم الحالي
```http
GET /api/auth/user
Authorization: Bearer {token}
```

---

## 📦 2. المنتجات

### عرض جميع المنتجات
```http
GET /api/products
Authorization: Bearer {token}
```
**الصلاحية:** جميع المستخدمين

---

## 🏪 3. المتاجر

### عرض جميع المتاجر
```http
GET /api/stores
Authorization: Bearer {token}
```
**الصلاحية:** جميع المستخدمين

### عرض جميع المتاجر مع الديون
```http
GET /api/stores/debts
Authorization: Bearer {token}
```
**الصلاحية:** جميع المستخدمين

---

## 👥 4. المستخدمين

### عرض جميع المستخدمين
```http
GET /api/users
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin فقط

### عرض الأدوار
```http
GET /api/roles
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin فقط

---

## 📊 5. المخزون

### عرض المخزن الرئيسي
```http
GET /api/warehouse/main-stock
Authorization: Bearer {token}
```
**الصلاحية:** جميع المستخدمين

### عرض مخزون المسوق الفعلي
```http
GET /api/marketer/stock/actual
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق فقط (مخزونه الخاص)

### عرض مخزون المسوق المحجوز
```http
GET /api/marketer/stock/reserved
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق فقط (مخزونه الخاص)

---

## 📝 6. طلبات البضاعة من المسوق

### المسوق - عرض جميع طلباتي
```http
GET /api/marketer/requests
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (طلباته فقط)

### أمين المخزن - عرض جميع طلبات المسوقين
```http
GET /api/warehouse/requests
Authorization: Bearer {token}
Role: warehouse_keeper
```
**الصلاحية:** أمين المخزن (جميع الطلبات)

---

## 🔄 7. إرجاع بضاعة من المسوق

### المسوق - عرض جميع إرجاعاتي
```http
GET /api/marketer/returns
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (إرجاعاته فقط)

### أمين المخزن - عرض جميع إرجاعات المسوقين
```http
GET /api/warehouse/returns
Authorization: Bearer {token}
Role: warehouse_keeper
```
**الصلاحية:** أمين المخزن (جميع الإرجاعات)

---

## 💰 8. فواتير البيع

### المسوق - عرض جميع فواتيري
```http
GET /api/marketer/sales
Authorization: Bearer {token}
Role: salesman

# مع فلترة
GET /api/marketer/sales?status=pending
```
**الصلاحية:** المسوق (فواتيره فقط)

### أمين المخزن - عرض جميع فواتير البيع
```http
GET /api/warehouse/sales
Authorization: Bearer {token}
Role: warehouse_keeper
```
**الصلاحية:** أمين المخزن (جميع الفواتير)

### الإدارة - عرض جميع فواتير البيع
```http
GET /api/admin/sales
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin (جميع الفواتير)

---

## 💵 9. إيصالات القبض (التسديدات)

### المسوق - عرض جميع إيصالاتي
```http
GET /api/marketer/payments
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (إيصالاته فقط)

### أمين المخزن - عرض جميع إيصالات القبض
```http
GET /api/warehouse/payments
Authorization: Bearer {token}
Role: warehouse_keeper
```
**الصلاحية:** أمين المخزن (جميع الإيصالات)

---

## 🔙 10. إرجاع بضاعة من المتجر

### المسوق - عرض جميع إرجاعات المتاجر
```http
GET /api/marketer/store-returns
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (إرجاعاته فقط)

### أمين المخزن - عرض جميع إرجاعات المتاجر
```http
GET /api/warehouse/store-returns
Authorization: Bearer {token}
Role: warehouse_keeper
```
**الصلاحية:** أمين المخزن (جميع الإرجاعات)

---

## 💸 11. سحب الأرباح

### المسوق - عرض جميع طلبات السحب
```http
GET /api/marketer/withdrawals
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (طلباته فقط)

### الإدارة - عرض جميع طلبات السحب
```http
GET /api/admin/withdrawals
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin (جميع الطلبات)

---

## 👨‍💼 12. المسوقين (Admin)

### عرض جميع المسوقين مع العمولات
```http
GET /api/admin/marketers
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin فقط

---

## 🎁 13. العروض الترويجية

### الإدارة - عرض جميع العروض
```http
GET /api/admin/promotions
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin (جميع العروض)

### المسوق - عرض العروض النشطة فقط
```http
GET /api/marketer/promotions/active
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (العروض النشطة فقط)

---

## 💳 14. خصومات الفواتير

### الإدارة - عرض جميع الخصومات
```http
GET /api/admin/discounts
Authorization: Bearer {token}
Role: admin
```
**الصلاحية:** Admin (جميع الخصومات)

### المسوق - عرض الخصومات النشطة فقط
```http
GET /api/marketer/discounts/active
Authorization: Bearer {token}
Role: salesman
```
**الصلاحية:** المسوق (الخصومات النشطة فقط)

### الجميع - عرض الخصومات النشطة
```http
GET /api/discounts/active
Authorization: Bearer {token}
```
**الصلاحية:** جميع المستخدمين

---

## 📊 ملخص APIs حسب الدور

### 🔵 المسوق (Salesman) - 10 APIs
1. `GET /api/marketer/requests` - طلباتي
2. `GET /api/marketer/returns` - إرجاعاتي
3. `GET /api/marketer/sales` - فواتيري
4. `GET /api/marketer/payments` - إيصالاتي
5. `GET /api/marketer/store-returns` - إرجاعات المتاجر
6. `GET /api/marketer/withdrawals` - طلبات السحب
7. `GET /api/marketer/stock/actual` - مخزوني الفعلي
8. `GET /api/marketer/stock/reserved` - مخزوني المحجوز
9. `GET /api/marketer/promotions/active` - العروض النشطة
10. `GET /api/marketer/discounts/active` - الخصومات النشطة

---

### 🟢 أمين المخزن (Warehouse Keeper) - 5 APIs
1. `GET /api/warehouse/requests` - جميع طلبات المسوقين
2. `GET /api/warehouse/returns` - جميع إرجاعات المسوقين
3. `GET /api/warehouse/sales` - جميع فواتير البيع
4. `GET /api/warehouse/payments` - جميع إيصالات القبض
5. `GET /api/warehouse/store-returns` - جميع إرجاعات المتاجر

---

### 🟡 المدير (Admin) - 7 APIs
1. `GET /api/admin/sales` - جميع فواتير البيع
2. `GET /api/admin/withdrawals` - جميع طلبات السحب
3. `GET /api/admin/marketers` - جميع المسوقين
4. `GET /api/admin/promotions` - جميع العروض
5. `GET /api/admin/discounts` - جميع الخصومات
6. `GET /api/users` - جميع المستخدمين
7. `GET /api/roles` - الأدوار

---

### 🌐 الجميع (All Authenticated) - 5 APIs
1. `GET /api/products` - جميع المنتجات
2. `GET /api/stores` - جميع المتاجر
3. `GET /api/stores/debts` - المتاجر مع الديون
4. `GET /api/warehouse/main-stock` - المخزن الرئيسي
5. `GET /api/discounts/active` - الخصومات النشطة

---

## 📊 الإحصائيات

**إجمالي APIs القوائم: 27 API**

### حسب النوع:
- **المخزون:** 3 APIs
- **الطلبات:** 2 APIs
- **الإرجاعات:** 4 APIs
- **المبيعات:** 3 APIs
- **التسديدات:** 2 APIs
- **السحوبات:** 2 APIs
- **المنتجات/المتاجر:** 3 APIs
- **المستخدمين:** 3 APIs
- **العروض/الخصومات:** 5 APIs

### حسب الصلاحية:
- **المسوق فقط:** 10 APIs
- **أمين المخزن فقط:** 5 APIs
- **Admin فقط:** 7 APIs
- **الجميع:** 5 APIs

---

## ⚠️ ملاحظات مهمة

### 1. الفلترة
بعض APIs تدعم Query Parameters:
```http
GET /api/marketer/sales?status=pending
GET /api/marketer/sales?status=approved
```

### 2. Pagination
**حالياً:** لا يوجد pagination - يتم إرجاع جميع السجلات

**مستقبلاً:** يُنصح بإضافة pagination للقوائم الكبيرة:
```http
GET /api/marketer/sales?page=1&per_page=20
```

### 3. الترتيب
معظم القوائم مرتبة حسب:
```sql
ORDER BY created_at DESC
```
(الأحدث أولاً)

### 4. الأداء
القوائم الكبيرة قد تؤثر على الأداء:
- ✅ استخدم Indexing على الجداول
- ✅ أضف Pagination
- ✅ استخدم Caching للبيانات الثابتة

---

**✅ جميع APIs القوائم الكاملة موثقة**
