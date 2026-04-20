<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Kost;
use App\Models\KostImage;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Report;
use App\Models\ReportResponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        // ─── 1. Users ────────────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin SigmaKost',
            'email' => 'admin@sigmakost.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta Pusat',
        ]);

        $users = [];
        $userData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081234567891', 'address' => 'Jl. Merdeka No. 10, Jakarta Selatan'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'phone' => '081234567892', 'address' => 'Jl. Kenanga No. 15, Depok'],
            ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'phone' => '081234567893', 'address' => 'Jl. Mawar No. 3, Bogor'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'phone' => '081234567894', 'address' => 'Jl. Cempaka No. 22, Tangerang'],
            ['name' => 'Rudi Hermawan', 'email' => 'rudi@example.com', 'phone' => '081234567895', 'address' => 'Jl. Anggrek No. 8, Bekasi'],
            ['name' => 'Putri Amelia', 'email' => 'putri@example.com', 'phone' => '081234567896', 'address' => 'Jl. Dahlia No. 5, Bandung'],
        ];

        foreach ($userData as $u) {
            $users[] = User::create(array_merge($u, [
                'password' => bcrypt('password'),
                'role' => 'user',
            ]));
        }

        $this->command->info('✅ Created 1 admin + 6 users');

        // ─── 2. Categories ───────────────────────────────────────────
        $this->call(CategorySeeder::class);
        $categories = Category::all();

        $this->command->info('✅ Created ' . $categories->count() . ' categories');

        // ─── 3. Kosts ────────────────────────────────────────────────
        $kostsData = [
            [
                'name' => 'Kost Melati Indah',
                'description' => 'Kost nyaman dan bersih di pusat kota Jakarta. Dekat dengan stasiun MRT dan pusat perbelanjaan. Lingkungan aman dan tenang, cocok untuk pekerja dan mahasiswa.',
                'address' => 'Jl. Merpati No. 12, Kebayoran Baru, Jakarta Selatan',
                'contact_number' => '081234500001',
                'latitude' => -6.2441,
                'longitude' => 106.8001,
                'price_per_month' => 1500000,
                'room_count' => 10,
                'available_rooms' => 5,
                'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Meja Belajar']),
                'rules' => json_encode(['Tidak boleh membawa hewan peliharaan', 'Jam malam pukul 23:00', 'Tamu harus lapor']),
                'status' => 'active',
                'categories' => ['kost-putra'],
            ],
            [
                'name' => 'Kost Harmoni Residence',
                'description' => 'Kost putri eksklusif dengan keamanan 24 jam. Tersedia dapur bersama, ruang tamu, dan area jemur. Strategis dekat kampus UI dan stasiun Depok Baru.',
                'address' => 'Jl. Kebangsaan No. 5, Beji, Depok',
                'contact_number' => '081234500002',
                'latitude' => -6.3720,
                'longitude' => 106.8312,
                'price_per_month' => 2000000,
                'room_count' => 8,
                'available_rooms' => 3,
                'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam', 'Water Heater', 'Dapur Bersama', 'CCTV', 'Parkir Motor']),
                'rules' => json_encode(['Khusus putri', 'Jam malam pukul 22:00', 'Tidak boleh merokok']),
                'status' => 'active',
                'categories' => ['kost-putri'],
            ],
            [
                'name' => 'Kost Ceria 88',
                'description' => 'Kost campur dengan harga terjangkau. Akses mudah ke jalan tol dan angkutan umum. Cocok untuk mahasiswa dan pekerja. Tersedia kantin di lantai dasar.',
                'address' => 'Jl. Flamboyan No. 88, Tanah Sareal, Bogor',
                'contact_number' => '081234500003',
                'latitude' => -6.5900,
                'longitude' => 106.7800,
                'price_per_month' => 1200000,
                'room_count' => 15,
                'available_rooms' => 8,
                'facilities' => json_encode(['WiFi', 'Kipas Angin', 'Kamar Mandi Luar', 'Lemari', 'Kasur']),
                'rules' => json_encode(['Bayar tepat waktu', 'Jaga kebersihan', 'Tamu lapor ke pengelola']),
                'status' => 'active',
                'categories' => ['kost-campur'],
            ],
            [
                'name' => 'Kost Mawar Putih',
                'description' => 'Kost putra premium di kawasan BSD. Kamar luas dengan fasilitas lengkap. Area parkir luas untuk mobil dan motor. Dekat mall dan rumah sakit.',
                'address' => 'Jl. Anggrek No. 3, BSD City, Tangerang Selatan',
                'contact_number' => '081234500004',
                'latitude' => -6.3020,
                'longitude' => 106.6520,
                'price_per_month' => 1800000,
                'room_count' => 12,
                'available_rooms' => 2,
                'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam', 'Water Heater', 'TV', 'Lemari', 'Kasur', 'Meja Belajar', 'Parkir Mobil']),
                'rules' => json_encode(['Khusus putra', 'Tidak boleh membawa hewan', 'Tamu maksimal pukul 21:00']),
                'status' => 'active',
                'categories' => ['kost-putra', 'studio-room'],
            ],
            [
                'name' => 'Kost Sejahtera',
                'description' => 'Kost sederhana dan nyaman di Bekasi. Harga sangat terjangkau dengan fasilitas dasar yang memadai. Dekat pasar tradisional dan minimarket.',
                'address' => 'Jl. Dahlia No. 9, Pondok Gede, Bekasi',
                'contact_number' => '081234500005',
                'latitude' => -6.2860,
                'longitude' => 106.9120,
                'price_per_month' => 900000,
                'room_count' => 20,
                'available_rooms' => 12,
                'facilities' => json_encode(['WiFi', 'Kipas Angin', 'Kasur', 'Lemari']),
                'rules' => json_encode(['Bayar tepat waktu', 'Jaga ketenangan']),
                'status' => 'active',
                'categories' => ['kost-campur', 'sharing-room'],
            ],
            [
                'name' => 'Kost Griya Asri',
                'description' => 'Kost putri dengan suasana asri dan hijau. Taman yang terawat dan lingkungan yang tenang. Ideal untuk mahasiswa yang butuh ketenangan belajar.',
                'address' => 'Jl. Teratai No. 7, Dago, Bandung',
                'contact_number' => '081234500006',
                'latitude' => -6.8800,
                'longitude' => 107.6140,
                'price_per_month' => 1100000,
                'room_count' => 6,
                'available_rooms' => 4,
                'facilities' => json_encode(['WiFi', 'Kipas Angin', 'Kamar Mandi Dalam', 'Lemari', 'Meja Belajar', 'Taman']),
                'rules' => json_encode(['Khusus putri', 'Jam malam pukul 22:00', 'Tidak boleh merokok', 'Jaga kebersihan']),
                'status' => 'active',
                'categories' => ['kost-putri'],
            ],
            [
                'name' => 'Kost Studio Menteng',
                'description' => 'Studio room premium di kawasan elite Menteng. Fully furnished dengan desain modern. Cocok untuk profesional muda.',
                'address' => 'Jl. Sutan Syahrir No. 15, Menteng, Jakarta Pusat',
                'contact_number' => '081234500007',
                'latitude' => -6.1960,
                'longitude' => 106.8400,
                'price_per_month' => 3500000,
                'room_count' => 5,
                'available_rooms' => 1,
                'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam', 'Water Heater', 'TV', 'Kulkas Mini', 'Microwave', 'Lemari', 'Meja Kerja', 'CCTV']),
                'rules' => json_encode(['Tidak boleh membawa hewan', 'Tamu maksimal pukul 22:00']),
                'status' => 'active',
                'categories' => ['kost-campur', 'studio-room'],
            ],
            [
                'name' => 'Kost Wisma Mutiara',
                'description' => 'Kost yang sedang dalam renovasi. Akan segera dibuka kembali dengan fasilitas yang lebih baik.',
                'address' => 'Jl. Mutiara No. 20, Cibubur, Jakarta Timur',
                'contact_number' => '081234500008',
                'latitude' => -6.3700,
                'longitude' => 106.8800,
                'price_per_month' => 1300000,
                'room_count' => 10,
                'available_rooms' => 0,
                'facilities' => json_encode(['WiFi', 'AC', 'Kamar Mandi Dalam']),
                'rules' => json_encode(['Dalam renovasi']),
                'status' => 'inactive',
                'categories' => ['kost-putra'],
            ],
        ];

        $kosts = [];
        foreach ($kostsData as $kd) {
            $categorySlugs = $kd['categories'];
            unset($kd['categories']);

            $kost = Kost::create(array_merge($kd, [
                'created_by' => $admin->id,
            ]));

            // Attach categories
            $catIds = Category::whereIn('slug', $categorySlugs)->pluck('id');
            $kost->categories()->attach($catIds);

            $kosts[] = $kost;
        }

        $this->command->info('✅ Created ' . count($kosts) . ' kosts with categories');

        // ─── 4. Kost Images (placeholder paths) ─────────────────────
        foreach ($kosts as $i => $kost) {
            // Primary image
            KostImage::create([
                'kost_id' => $kost->id,
                'image_path' => 'kost-images/kost-' . ($i + 1) . '-main.jpg',
                'is_primary' => true,
                'order' => 0,
            ]);
            // Secondary images
            for ($j = 1; $j <= 2; $j++) {
                KostImage::create([
                    'kost_id' => $kost->id,
                    'image_path' => 'kost-images/kost-' . ($i + 1) . '-' . $j . '.jpg',
                    'is_primary' => false,
                    'order' => $j,
                ]);
            }
        }

        $this->command->info('✅ Created kost images');

        // ─── 5. Rentals ──────────────────────────────────────────────
        $now = Carbon::now();

        // Budi rents Kost Melati (approved)
        $rental1 = Rental::create([
            'kost_id' => $kosts[0]->id, // Melati Indah
            'user_id' => $users[0]->id, // Budi
            'room_number' => 'A1',
            'start_date' => $now->copy()->subMonths(2),
            'end_date' => $now->copy()->addMonths(1),
            'duration_months' => 3,
            'total_price' => 4500000,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => $now->copy()->subMonths(2)->addDay(),
            'notes' => 'Mahasiswa semester 6',
        ]);

        // Siti rents Kost Harmoni (approved)
        $rental2 = Rental::create([
            'kost_id' => $kosts[1]->id, // Harmoni
            'user_id' => $users[1]->id, // Siti
            'room_number' => 'B3',
            'start_date' => $now->copy()->subMonths(1),
            'end_date' => $now->copy()->addMonths(5),
            'duration_months' => 6,
            'total_price' => 12000000,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => $now->copy()->subMonths(1)->addDay(),
            'notes' => 'Karyawan swasta',
        ]);

        // Andi wants Kost Ceria (pending)
        $rental3 = Rental::create([
            'kost_id' => $kosts[2]->id, // Ceria 88
            'user_id' => $users[2]->id, // Andi
            'room_number' => null,
            'start_date' => $now->copy()->addWeek(),
            'end_date' => $now->copy()->addMonths(1)->addWeek(),
            'duration_months' => 1,
            'total_price' => 1200000,
            'status' => 'pending',
            'notes' => 'Ingin kamar lantai 2 jika ada',
        ]);

        // Rudi rents Kost Mawar (approved)
        $rental4 = Rental::create([
            'kost_id' => $kosts[3]->id, // Mawar Putih
            'user_id'          => $users[4]->id, // Rudi
            'room_number' => 'C2',
            'start_date' => $now->copy()->subMonths(3),
            'end_date' => $now->copy()->addMonths(3),
            'duration_months' => 6,
            'total_price' => 10800000,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => $now->copy()->subMonths(3)->addDays(2),
        ]);

        // Dewi wants Harmoni (rejected)
        $rental5 = Rental::create([
            'kost_id' => $kosts[1]->id, // Harmoni
            'user_id' => $users[3]->id, // Dewi
            'start_date' => $now->copy()->subWeeks(2),
            'end_date' => $now->copy()->addMonths(1)->subWeeks(2),
            'duration_months' => 1,
            'total_price' => 2000000,
            'status' => 'rejected',
            'approved_by' => $admin->id,
            'approved_at' => $now->copy()->subWeeks(2)->addDay(),
            'rejection_reason' => 'Kamar yang diminta tidak tersedia saat ini.',
        ]);

        // Putri rents Griya Asri (approved)
        $rental6 = Rental::create([
            'kost_id' => $kosts[5]->id, // Griya Asri
            'user_id' => $users[5]->id, // Putri
            'room_number' => 'D1',
            'start_date' => $now->copy()->subWeeks(3),
            'end_date' => $now->copy()->addMonths(3)->subWeeks(3),
            'duration_months' => 3,
            'total_price' => 3300000,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => $now->copy()->subWeeks(3)->addDay(),
        ]);

        // Andi also pending at Studio Menteng
        $rental7 = Rental::create([
            'kost_id' => $kosts[6]->id, // Studio Menteng
            'user_id' => $users[2]->id, // Andi
            'start_date' => $now->copy()->addDays(3),
            'end_date' => $now->copy()->addMonths(1)->addDays(3),
            'duration_months' => 1,
            'total_price' => 3500000,
            'status' => 'pending',
            'notes' => 'Prefer kamar dengan view',
        ]);

        $this->command->info('✅ Created 7 rentals (4 approved, 2 pending, 1 rejected)');

        // ─── 6. Payments ─────────────────────────────────────────────
        // Rental 1 (Budi, 3 months): month 1 verified, month 2 paid pending, month 3 unpaid
        Payment::create([
            'rental_id' => $rental1->id,
            'user_id' => $users[0]->id,
            'amount' => 1500000,
            'due_date' => $now->copy()->subMonths(2)->addDays(7),
            'paid_date' => $now->copy()->subMonths(2)->addDays(5),
            'payment_method' => 'transfer_bank',
            'payment_proof' => 'payment-proofs/budi-month1.jpg',
            'status' => 'verified',
            'period_month' => $now->copy()->subMonths(2)->month,
            'period_year' => $now->copy()->subMonths(2)->year,
            'verified_by' => $admin->id,
            'verified_at' => $now->copy()->subMonths(2)->addDays(6),
        ]);

        Payment::create([
            'rental_id' => $rental1->id,
            'user_id' => $users[0]->id,
            'amount' => 1500000,
            'due_date' => $now->copy()->subMonth()->addDays(7),
            'paid_date' => $now->copy()->subMonth()->addDays(10),
            'payment_method' => 'transfer_bank',
            'payment_proof' => 'payment-proofs/budi-month2.jpg',
            'status' => 'paid',
            'period_month' => $now->copy()->subMonth()->month,
            'period_year' => $now->copy()->subMonth()->year,
        ]);

        Payment::create([
            'rental_id' => $rental1->id,
            'user_id' => $users[0]->id,
            'amount' => 1500000,
            'due_date' => $now->copy()->addDays(7),
            'status' => 'unpaid',
            'period_month' => $now->month,
            'period_year' => $now->year,
        ]);

        // Rental 2 (Siti, 6 months): month 1 verified
        Payment::create([
            'rental_id' => $rental2->id,
            'user_id' => $users[1]->id,
            'amount' => 2000000,
            'due_date' => $now->copy()->subMonth()->addDays(7),
            'paid_date' => $now->copy()->subMonth()->addDays(3),
            'payment_method' => 'e_wallet',
            'payment_proof' => 'payment-proofs/siti-month1.jpg',
            'status' => 'verified',
            'period_month' => $now->copy()->subMonth()->month,
            'period_year' => $now->copy()->subMonth()->year,
            'verified_by' => $admin->id,
            'verified_at' => $now->copy()->subMonth()->addDays(4),
        ]);

        // Siti month 2 — unpaid
        Payment::create([
            'rental_id' => $rental2->id,
            'user_id' => $users[1]->id,
            'amount' => 2000000,
            'due_date' => $now->copy()->addDays(7),
            'status' => 'unpaid',
            'period_month' => $now->month,
            'period_year' => $now->year,
        ]);

        // Rental 4 (Rudi, 6 months): 3 verified, 1 paid, 1 unpaid, 1 overdue
        for ($m = 0; $m < 6; $m++) {
            $dueDate = $now->copy()->subMonths(3)->addMonths($m)->addDays(7);
            $pMonth = $now->copy()->subMonths(3)->addMonths($m);

            if ($m < 3) {
                // Verified
                Payment::create([
                    'rental_id' => $rental4->id,
                    'user_id' => $users[4]->id,
                    'amount' => 1800000,
                    'due_date' => $dueDate,
                    'paid_date' => $dueDate->copy()->subDays(2),
                    'payment_method' => 'transfer_bank',
                    'payment_proof' => 'payment-proofs/rudi-month' . ($m + 1) . '.jpg',
                    'status' => 'verified',
                    'period_month' => $pMonth->month,
                    'period_year' => $pMonth->year,
                    'verified_by' => $admin->id,
                    'verified_at' => $dueDate->copy()->subDay(),
                ]);
            } elseif ($m == 3) {
                // Paid, pending verification
                Payment::create([
                    'rental_id' => $rental4->id,
                    'user_id' => $users[4]->id,
                    'amount' => 1800000,
                    'due_date' => $dueDate,
                    'paid_date' => $dueDate->copy()->addDay(),
                    'payment_method' => 'e_wallet',
                    'payment_proof' => 'payment-proofs/rudi-month4.jpg',
                    'status' => 'paid',
                    'period_month' => $pMonth->month,
                    'period_year' => $pMonth->year,
                ]);
            } else {
                // Unpaid
                $status = $dueDate->lt($now) ? 'overdue' : 'unpaid';
                Payment::create([
                    'rental_id' => $rental4->id,
                    'user_id' => $users[4]->id,
                    'amount' => 1800000,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'period_month' => $pMonth->month,
                    'period_year' => $pMonth->year,
                    'overdue_notification_sent' => $status === 'overdue',
                ]);
            }
        }

        // Rental 6 (Putri): month 1 verified
        Payment::create([
            'rental_id' => $rental6->id,
            'user_id' => $users[5]->id,
            'amount' => 1100000,
            'due_date' => $now->copy()->subWeeks(3)->addDays(7),
            'paid_date' => $now->copy()->subWeeks(3)->addDays(5),
            'payment_method' => 'transfer_bank',
            'payment_proof' => 'payment-proofs/putri-month1.jpg',
            'status' => 'verified',
            'period_month' => $now->copy()->subWeeks(3)->month,
            'period_year' => $now->copy()->subWeeks(3)->year,
            'verified_by' => $admin->id,
            'verified_at' => $now->copy()->subWeeks(3)->addDays(6),
        ]);

        $this->command->info('✅ Created payments (verified, paid, unpaid, overdue)');

        // ─── 7. Reviews ──────────────────────────────────────────────
        Review::create([
            'user_id' => $users[0]->id, // Budi
            'kost_id' => $kosts[0]->id, // Melati
            'rental_id' => $rental1->id,
            'rating' => 5,
            'comment' => 'Kost sangat nyaman dan bersih! Pemilik ramah dan helpful. WiFi cepat. Sangat recommended!',
        ]);

        Review::create([
            'user_id' => $users[1]->id, // Siti
            'kost_id' => $kosts[1]->id, // Harmoni
            'rental_id' => $rental2->id,
            'rating' => 4,
            'comment' => 'Keamanan bagus, lingkungan tenang. AC kadang agak bermasalah tapi overall oke.',
        ]);

        Review::create([
            'user_id' => $users[4]->id, // Rudi
            'kost_id' => $kosts[3]->id, // Mawar Putih
            'rental_id' => $rental4->id,
            'rating' => 5,
            'comment' => 'Premium banget! Fasilitas lengkap, parkir luas, dekat mall. Worth the price.',
        ]);

        Review::create([
            'user_id' => $users[5]->id, // Putri
            'kost_id' => $kosts[5]->id, // Griya Asri
            'rental_id' => $rental6->id,
            'rating' => 4,
            'comment' => 'Suasana tenang, cocok untuk belajar. Taman indah dan terawat.',
        ]);

        $this->command->info('✅ Created 4 reviews');

        // ─── 8. Reports ──────────────────────────────────────────────
        $report1 = Report::create([
            'rental_id' => $rental1->id,
            'user_id' => $users[0]->id, // Budi
            'kost_id' => $kosts[0]->id,
            'title' => 'AC Kamar Tidak Dingin',
            'description' => 'AC di kamar A1 sudah tidak dingin sejak 3 hari yang lalu. Sudah coba bersihkan filter tapi tetap tidak dingin. Mohon diperbaiki segera.',
            'category' => 'maintenance',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        $report2 = Report::create([
            'rental_id' => $rental2->id,
            'user_id' => $users[1]->id, // Siti
            'kost_id' => $kosts[1]->id,
            'title' => 'Kran Air Bocor',
            'description' => 'Kran air di kamar mandi bocor kecil. Belum parah tapi sebaiknya segera diperbaiki sebelum lebih rusak.',
            'category' => 'maintenance',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        $report3 = Report::create([
            'rental_id' => $rental4->id,
            'user_id' => $users[4]->id, // Rudi
            'kost_id' => $kosts[3]->id,
            'title' => 'WiFi Sering Terputus',
            'description' => 'WiFi sering putus-putus terutama di malam hari. Kadang tidak bisa connect sama sekali.',
            'category' => 'facility',
            'priority' => 'medium',
            'status' => 'resolved',
        ]);

        $this->command->info('✅ Created 3 reports');

        // ─── 9. Report Responses ─────────────────────────────────────
        ReportResponse::create([
            'report_id' => $report1->id,
            'admin_id' => $admin->id,
            'message' => 'Terima kasih atas laporannya. Teknisi AC akan datang besok pagi jam 10:00.',
            'is_internal_note' => false,
        ]);

        ReportResponse::create([
            'report_id' => $report1->id,
            'admin_id' => $admin->id,
            'message' => 'Hubungi vendor AC: Pak Joko 081234509999',
            'is_internal_note' => true,
        ]);

        ReportResponse::create([
            'report_id' => $report3->id,
            'admin_id' => $admin->id,
            'message' => 'Router WiFi sudah diganti dengan yang baru. Seharusnya sudah stabil. Mohon coba kembali dan kabari jika masih bermasalah.',
            'is_internal_note' => false,
        ]);

        $this->command->info('✅ Created report responses');
        $this->command->info('');
        $this->command->info('🎉 Seeding complete! Demo accounts:');
        $this->command->info('   Admin: admin@sigmakost.com / password');
        $this->command->info('   User:  budi@example.com / password');
        $this->command->info('   User:  siti@example.com / password');
    }
}
