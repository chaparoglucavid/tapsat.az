Tapşırığı yerinə yetirmək üçün aşağıdakı addımları icra edəcəyəm:

## 1. Verilənlər Bazası Dəyişiklikləri (Migrations)
*   **Users cədvəli:** `store_owner` (boolean, default: false) sütunu əlavə ediləcək.
*   **Stores cədvəli:** Yeni `stores` cədvəli yaradılacaq.
    *   Sütunlar: `uuid`, `store_name`, `category_id`, `user_id`, `phone_number`, `email`, `logo`, `banner_image`, `address`, `working_days` (JSON), `working_hours`, `status` (enum: pending, confirmed, rejected), `rejection_reason` (text, nullable).

## 2. Modellər və Əlaqələr
*   **Store Modeli:** `Store.php` yaradılacaq. `User` və `Category` ilə əlaqələr (`belongsTo`) qurulacaq.
*   **User Modeli:** `User.php` faylında `store` əlaqəsi (`hasOne`) və `store_owner` sahəsi `fillable` siyahısına əlavə ediləcək.

## 3. Admin Controller (StoreController)
*   **`index` metodu:** Mağazaların siyahısı, statusa görə filtr və təsdiq/imtina düymələri.
*   **`create` metodu:** Yeni mağaza yaratma formu.
*   **`store` metodu:**
    *   Tranzaksiya (DB Transaction) istifadə ediləcək.
    *   Əgər yeni istifadəçi seçilibsə -> `User` yaradılacaq.
    *   Mövcud istifadəçi seçilibsə -> `User` tapılacaq.
    *   Şəkillər (logo, banner) yüklənəcək.
    *   `Store` yaradılacaq (default status: `pending`).
*   **`updateStatus` metodu:** `confirm` (istifadəçini `store_owner=true` edir) və ya `reject` (səbəb tələb edir) statusunu dəyişmək üçün.

## 4. Görünüşlər (Views)
*   `resources/views/admin-dashboard/stores/` qovluğu yaradılacaq.
*   **`create.blade.php`:** 
    *   Mağaza məlumatları formu.
    *   İstifadəçi seçimi: "Mövcud istifadəçi" (Select2 dropdown) və ya "Yeni istifadəçi" (Ad, Email, Şifrə inputları) keçidi (Toggle).
*   **`index.blade.php`:** Cədvəl görünüşü, status rəngləri və təsdiq/imtina modalları.

## 5. Routinq (Routes)
*   `routes/web.php` faylında `stores` resurs routeları və status dəyişimi üçün xüsusi route əlavə ediləcək.

Bu planı təsdiqləsəniz, kodlaşdırmağa başlayacağam.