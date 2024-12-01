<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_stoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ProdukId');
            $table->integer('JumlahProduk');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        DB::unprepared('
        create trigger log_stok
        after update on produks
        for each row
        begin
            insert into log_stoks
            (ProdukId, JumlahProduk, user_id, created_at)
        values
        (
            new.id,
            new.stok - old.stok,
            new.user_id,
            now()
        );
        end;
');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_stoks');
        DB::unprepared('drop trigger IF EXISTS log_stok');
    }
};
