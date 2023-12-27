## SATU SEHAT - SHT v.1

-   Penanganan Use Case 1 (Encounter & Condition)
-   Pengiriman Bersifat Manual / Otomatis

### Pengiriman Manual

Pengiriman yang bersifat eksekusi yang dilakukan oleh User,Cth : bisa mengklik per patient atau Sekaligus kirim
\*\* untuk sekaligus kirim Max per klik adalah 100 data, dikarenakan API Satusehat DTO hanya menerima 150 Hit dalam 1 Menit

### Pengiriman Scheduler

App -> API DTO Kemenkes
Pengiriman yang bersifat Schedule , di kerjakan menggunakan Task Scheduler dengan pengaturan pada menu Jadwal

### Resource :

-   Organization (Manual)
-   Location (Manual)
-   Patient (Manual, Bulk (Max 100), Schedule)
-   Practitioner (Manual, Bulk (Max 100), Schedule)
-   Bundle (Schedule)

### Cara Alur Kerja Coding Patient , Practitioner, Bundle

-   API menggunakan guzzle
-   Data menggunakan Datatable Server Side
-   CRUD di kumpulkan di Interface/Repository
-   Pengiriman data Per 1 Record menggunakan Cara Manual
-   Semua Proses yang memakan waktu lama di Handle oleh Job/Queue
-   Job yang akan di otomatiskan akan menembak langsung Controller yang menjalankan Job/Queue
-   Task Scheduler Memanggil Controller
-   Jadwal Task Scheduler menggunakan Cron
