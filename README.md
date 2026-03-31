<h2 align="center">Mojowarno Outdoor Gear</h2>

<p align="center">
Aplikasi penyewaan alat outdoor berbasis web yang membantu pengguna dalam mencari, memesan, dan mengelola perlengkapan camping seperti tenda, carrier, dan peralatan lainnya secara mudah, cepat, dan efisien.
</p>

---

## Deskripsi Sistem

**Mojowarno Outdoor Gear** merupakan sistem informasi penyewaan alat camping yang dirancang untuk:

-   Mempermudah pelanggan dalam melakukan booking alat secara online
-   Membantu admin dalam mengelola produk, kategori, dan transaksi
-   Mengoptimalkan proses validasi menggunakan QR Code
-   Menyediakan monitoring bisnis melalui dashboard

---

## Fitur Utama

-   Autentikasi (Login & Register)
-   Keranjang (Cart)
-   Manajemen Produk & Kategori
-   Dashboard Admin
-   Scan QR Code (Validasi Transaksi)
-   Pencarian & Filter Produk
-   Riwayat Transaksi

---

## User Stories

| Kode  | Aktor  | User Story                                                                                                                                                                     |
| ----- | ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| US-01 | Admin  | Sebagai Admin saya ingin sistem menampilkan dashboard ringkasan data sewa dan stok sehingga saya dapat memantau performa bisnis.                                               |
| US-02 | Admin  | Sebagai Admin saya ingin sistem menyediakan fitur pengelolaan slider banner (CRUD) sehingga saya dapat memberikan informasi produk terbaru kepada pelanggan.                   |
| US-03 | Admin  | Sebagai Admin saya ingin sistem menyediakan fitur pengelolaan kategori dan data produk (CRUD) sehingga saya dapat memperbarui inventaris alat camping dengan akurat.           |
| US-04 | Admin  | Sebagai Admin saya ingin sistem menyediakan fitur validasi transaksi via Scan QR Code sehingga proses pengambilan dan pengembalian barang menjadi instan tanpa ketik manual.   |
| US-05 | Admin  | Sebagai Admin saya ingin sistem menampilkan riwayat transaksi dan status denda sehingga saya dapat memantau keterlambatan pengembalian dengan lebih tertib.                    |
| US-06 | Member | Sebagai Member saya ingin sistem menyediakan fitur registrasi dan login sehingga saya dapat memiliki akun pribadi untuk melakukan pemesanan secara aman.                       |
| US-07 | Member | Sebagai Member saya ingin sistem menyediakan fitur katalog dengan pencarian dan filter sehingga saya dapat menemukan alat camping yang sesuai kebutuhan dengan mudah.          |
| US-08 | Member | Sebagai Member saya ingin sistem menampilkan detail spesifikasi produk sehingga saya mendapatkan informasi lengkap mengenai alat sebelum memutuskan untuk menyewa.             |
| US-09 | Member | Sebagai Member saya ingin sistem menyediakan fitur keranjang (cart) sehingga saya dapat menyewa berbagai jenis alat camping dalam satu kali transaksi.                         |
| US-10 | Member | Sebagai Member saya ingin sistem menghasilkan tiket QR Code sebagai bukti booking sehingga saya memiliki bukti digital yang praktis untuk ditunjukkan saat pengambilan barang. |
| US-11 | Member | Sebagai Member saya ingin sistem menampilkan riwayat sewa dan status pengembalian sehingga saya dapat melacak status penyewaan yang sedang atau pernah saya lakukan.           |

---

## Teknologi yang Digunakan

-   **Backend** : Laravel
-   **Frontend** : Blade + Bootstrap CSS
-   **Database** : MySQL
-   **QR Code** : Laravel QR Generator

---

## Instalasi

```bash
git clone https://github.com/RAFIALDI-SE/Mojowarno-Outdoor-Gear.git
cd mojowarno-outdoor-gear
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
