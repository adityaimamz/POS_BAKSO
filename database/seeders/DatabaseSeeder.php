<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\bahan_setengah_jadi;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use App\Models\User_detail;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Produk;
use App\Models\Outlet_detail;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create([
            'name' => 'SuperAdmin',
        ]);
        Role::create([
            'name' => 'Admin',
        ]);
        Role::create([
            'name' => 'Kasir',
        ]);
        Role::create([
            'name' => 'Outlet',
        ]);
        Role::create([
            'name' => 'Waiters',
        ]);

        Location::create([
            'locations' => 'Purbalingga',
        ]);
        Location::create([
            'locations' => 'Purwokerto',
        ]);

        Outlet::create([
            'name' => 'Utama',
            'location_id' => '1',
        ]);

        Outlet::create([
            'name' => 'Tenant',
            'location_id' => '1',
        ]);

        Outlet_detail::create([
            'name' => 'Depan',
            'location_id' => '1',
            'outlet_id' => '1'
        ]);
        Outlet_detail::create([
            'name' => 'Belakang',
            'location_id' => '1',
            'outlet_id' => '1'
        ]);
        Outlet_detail::create([
            'name' => 'Belakang',
            'location_id' => '1',
            'outlet_id' => '2'
        ]);

        User::create([
            'name' => 'Admin Pusat',
            'email' => 'superadmin@gmail.com',
            'role_id' => '1',
            'password' => Hash::make('superadmin'),
            'location_id' => '1',
            'outlet_id' => '1'
        ]);
        User::create([
            'name' => 'Admin Purwokerto',
            'email' => 'adminpwt@gmail.com',
            'role_id' => '2',
            'password' => Hash::make('admin123'),
            'location_id' => '2',
            'outlet_id' => '1'
        ]);
        User::create([
            'name' => 'Admin Purbalingga',
            'email' => 'adminpbg@gmail.com',
            'role_id' => '2',
            'password' => Hash::make('admin123'),
            'location_id' => '1',
            'outlet_id' => '1'
        ]);
        // User::create([
        //     'name' => 'Kasir Pusat',
        //     'email' => 'kasir1@gmail.com',
        //     'role_id' => '3',
        //     'password' => Hash::make('kasir123'),
        //     'location_id' => '1',
        //     'outlet_id' => '1'
        // ]);
        // User::create([
        //     'name' => 'Nabila',
        //     'email' => 'nabila@gmail.com',
        //     'role_id' => '5',
        //     'password' => Hash::make('nabila123'),
        //     'location_id' => '1',
        //     'outlet_id' => '1'
        // ]);
        // User::create([
        //     'name' => 'Outlet Pusat',
        //     'email' => 'outlet1@gmail.com',
        //     'role_id' => '4',
        //     'password' => Hash::make('outlet123'),
        //     'location_id' => '1',
        //     'outlet_id' => '1'
        // ]);

        // User_detail::create([
        //     'user_id' => '4',
        //     'outlet_detail_id' => '1',
        // ]);
        // User_detail::create([
        //     'user_id' => '5',
        //     'outlet_detail_id' => '1',
        // ]);
        // User_detail::create([
        //     'user_id' => '6',
        //     'outlet_detail_id' => '1',
        // ]);

        for ($i = 1; $i <= 30; $i++) {
            Table::create([
                'number' => $i,
                'outlet_detail_id' => '1',
            ]);
        }

        Payment::create([
            'name' => 'Cash',
        ]);
        Payment::create([
            'name' => 'QRIS',
        ]);
        Payment::create([
            'name' => 'Transfer Bank',
        ]);

        Produk::create([
            'name' => 'Bakso Polos Isi 5',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 0,
            'qty_bakso_daging' => 0,
        ]);

        Produk::create([
            'name' => 'Bakso Polos Isi 10',
            'price' => 14000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 10,
            'qty_bakso_urat' => 0,
            'qty_bakso_daging' => 0,
        ]);
        // Produk::create([
        //     'name' => 'Bakso Polos Isi 5 + Basreng',
        //     'price' => 14000,
        //     'image' => 'gambar_stock/bakso.jpg',
        //     'status_stock' => 'Tersedia',
        //     'outlet_id' => 1,
        //     'location_id' => 1,
        // ]);

        Produk::create([
            'name' => 'Bakso Polos Isi 10 + Tetelan',
            'price' => 19000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 10,
            'qty_bakso_urat' => 0,
            'qty_bakso_daging' => 0,
        ]);

        // Bakso Urat 1
        Produk::create([
            'name' => 'Bakso Urat 1, Polos 5',
            'price' => 17000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 1,
            'qty_bakso_daging' => 0,
        ]);

        // Produk::create([
        //     'name' => 'Bakso Urat 1, Polos 4 + Basreng',
        //     'price' => 17000,
        //     'image' => 'gambar_stock/bakso_urat_1_polos_basreng.jpg',
        //     'status_stock' => 'Tersedia',
        //     'outlet_id' => 1,
        //     'location_id' => 1,
        // ]);

        Produk::create([
            'name' => 'Bakso Urat 1, Polos 5 + Tetelan',
            'price' => 22000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        Produk::create([
            'name' => 'Bakso Daging 1, Polos 5',
            'price' => 17000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 0,
            'qty_bakso_daging' => 1,
        ]);
        // Produk::create([
        //     'name' => 'Bakso Daging 1, Polos 4 + Basreng',
        //     'price' => 17000,
        //     'image' => 'gambar_stock/bakso_urat_1_polos_5_tetelan.jpg',
        //     'status_stock' => 'Tersedia',
        //     'outlet_id' => 1,
        //     'location_id' => 1,
        // ]);
        Produk::create([
            'name' => 'Bakso Daging 1, Polos 5 + Tetelan',
            'price' => 22000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 0,
            'qty_bakso_daging' => 1,
        ]);
        Produk::create([
            'name' => 'Bakso Campur Urat 1, Daging 1 Polos 5',
            'price' => 22000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 1,
            'qty_bakso_daging' => 1,
        ]);
        // Produk::create([
        //     'name' => 'Bakso Daging 1, Urat 1, Polos 4',
        //     'price' => 22000,
        //     'image' => 'gambar_stock/bakso_urat_1_polos_5_tetelan.jpg',
        //     'status_stock' => 'Tersedia',
        //     'outlet_id' => 1,
        //     'location_id' => 1,
        // ]);
        Produk::create([
            'name' => 'Bakso Daging 1, Urat 1, Polos 5 + Tetelan',
            'price' => 27000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
            'qty_bakso_polos' => 5,
            'qty_bakso_urat' => 1,
            'qty_bakso_daging' => 1,
        ]);
        Produk::create([
            'name' => 'Basreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Mendoan',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Scalop Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Nugget Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Cireng Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Sosis Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Kentang Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Pisang Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Kaki Naga Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Mie Goreng',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Mie Nyemek',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Otak Otak',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Dumpling',
            'price' => 12500,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);


        // Juice Nanas
        Produk::create([
            'name' => 'Juice Nanas',
            'price' => 7000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Tomat',
            'price' => 7000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);


        // Es Jeruk
        Produk::create([
            'name' => 'Juice Jeruk',
            'price' => 7000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Jambu',
            'price' => 7000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Melon',
            'price' => 8000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Mangga',
            'price' => 8000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Strawberry',
            'price' => 8000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Alpukat',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Durian',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Juice Naga',
            'price' => 10000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        Produk::create([
            'name' => 'Es Teh Manis',
            'price' => 3000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Teh Anget Manis',
            'price' => 4000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Es Teh Tawar',
            'price' => 2000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Teh anget Tawar',
            'price' => 2000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Es Jeruk',
            'price' => 5000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Air Es',
            'price' => 1000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        Produk::create([
            'name' => 'Es Kopi/Kopi',
            'price' => 5000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        // Es Milo Dalgona
        Produk::create([
            'name' => 'Es Milo Dalgona',
            'price' => 8000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        // Chocolatos
        Produk::create([
            'name' => 'Chocolatos',
            'price' => 5000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        // Telur
        Produk::create([
            'name' => 'Telur',
            'price' => 3000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);

        // Sosis
        Produk::create([
            'name' => 'Sosis',
            'price' => 3000,
            'status_stock' => 'Tersedia',
            'outlet_id' => 1,
            'location_id' => 1,
        ]);
        Produk::create([
            'name' => 'Ketupat',
            'price' => 2000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Krupuk',
            'price' => 2000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Pangsit',
            'price' => 7000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Siomay Goreng',
            'price' => 7000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Klanting',
            'price' => 2000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Rambak',
            'price' => 3000,
            'status_stock' => 'Tersedia',
        ]);
        Produk::create([
            'name' => 'Kacang',
            'price' => 2000,
            'status_stock' => 'Tersedia',
        ]);

        bahan_setengah_jadi::create([
            'name' => 'Bakso Polos'
        ]);
        bahan_setengah_jadi::create([
            'name' => 'Bakso Urat'
        ]);
        bahan_setengah_jadi::create([
            'name' => 'Bakso Daging'
        ]);
    }
}