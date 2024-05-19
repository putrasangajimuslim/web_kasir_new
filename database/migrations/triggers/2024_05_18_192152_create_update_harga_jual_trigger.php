<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUpdateHargaJualTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_harga_jual
            AFTER UPDATE ON barang
            FOR EACH ROW
            BEGIN
                UPDATE detail_transaksi
                SET harga_jual = NEW.harga_jual,
                    subtotal_item = NEW.harga_jual * jumlah,
                    keuntungan = (NEW.harga_jual - (SELECT harga_beli FROM barang WHERE id = NEW.id)) * jumlah
                WHERE barang_id = NEW.id;
            END
        ');

        DB::unprepared('
            CREATE TRIGGER after_insert_detail_transaksi
            AFTER INSERT ON detail_transaksi
            FOR EACH ROW
            BEGIN
                UPDATE transaksi
                SET subtotal = (SELECT SUM(subtotal_item) FROM detail_transaksi WHERE transaksi_id = NEW.transaksi_id)
                WHERE id = NEW.transaksi_id;
            END
        ');

        // Trigger after update
        DB::unprepared('
            CREATE TRIGGER after_update_detail_transaksi
            AFTER UPDATE ON detail_transaksi
            FOR EACH ROW
            BEGIN
                UPDATE transaksi
                SET subtotal = (SELECT SUM(subtotal_item) FROM detail_transaksi WHERE transaksi_id = NEW.transaksi_id)
                WHERE id = NEW.transaksi_id;
            END
        ');

        // Trigger after delete
        DB::unprepared('
            CREATE TRIGGER after_delete_detail_transaksi
            AFTER DELETE ON detail_transaksi
            FOR EACH ROW
            BEGIN
                UPDATE transaksi
                SET subtotal = (SELECT SUM(subtotal_item) FROM detail_transaksi WHERE transaksi_id = OLD.transaksi_id)
                WHERE id = OLD.transaksi_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_harga_jual');
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_detail_transaksi');
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_detail_transaksi');
        DB::unprepared('DROP TRIGGER IF EXISTS after_delete_detail_transaksi');
    }
}
