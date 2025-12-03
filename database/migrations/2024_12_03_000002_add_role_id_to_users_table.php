<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('nim')->nullable()->unique()->after('email'); // Nomor Induk Mahasiswa
            $table->string('nip')->nullable()->unique()->after('nim'); // Nomor Induk Pegawai (Dosen)
            $table->string('phone')->nullable()->after('nip');
            $table->text('address')->nullable()->after('phone');
            $table->enum('gender', ['L', 'P'])->nullable()->after('address'); // L = Laki-laki, P = Perempuan
            $table->date('birth_date')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'nim', 'nip', 'phone', 'address', 'gender', 'birth_date']);
        });
    }
};
