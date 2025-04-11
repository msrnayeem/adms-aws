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
       
        // Create 'devices' table
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('no_sn')->unique();
            $table->string('lokasi')->nullable();
            $table->datetime('online')->nullable();
            // Opsi 1: Menggunakan CURRENT_TIMESTAMP saat insert
            $table->timestamp('created_at')->useCurrent();

            // Opsi 2: Menggunakan CURRENT_TIMESTAMP saat insert dan update
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Create 'device_log' table
        Schema::create('device_log', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->date('tgl')->nullable();
            $table->string('sn');
            $table->string('option')->nullable();
            $table->string('url')->nullable();
            // Opsi 1: Menggunakan CURRENT_TIMESTAMP saat insert
            $table->timestamp('created_at')->useCurrent();
            // Opsi 2: Menggunakan CURRENT_TIMESTAMP saat insert dan update
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('finger_log', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->text('url');
            // Opsi 1: Menggunakan CURRENT_TIMESTAMP saat insert
            $table->timestamp('created_at')->useCurrent();
    
            // Opsi 2: Menggunakan CURRENT_TIMESTAMP saat insert dan update
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            });

        // Create 'error_log' table
        Schema::create('error_log', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->timestamps();
        });

        // Create 'in_out_records' table
        Schema::create('in_out_records', function (Blueprint $table) {
            $table->id();
            $table->string('sn');
            $table->string('table');
            $table->string('stamp');
            $table->integer('employee_id');
            $table->dateTime('timestamp');
            $table->boolean('status1')->nullable();
            $table->boolean('status2')->nullable();
            $table->boolean('status3')->nullable();
            $table->boolean('status4')->nullable();
            $table->boolean('status5')->nullable();
            $table->timestamps();
        });

        // Create 'device_handshake_configs' table
        Schema::create('device_handshake_configs', function (Blueprint $table) {
            $table->id();
            $table->string('device_type')->default('default');
            $table->integer('stamp')->default(9999);
            $table->integer('error_delay')->default(60);
            $table->integer('delay')->default(30);
            $table->integer('res_log_day')->default(18250);
            $table->integer('res_log_del_count')->default(10000);
            $table->integer('res_log_count')->default(50000);
            $table->string('trans_times')->default('00:00;14:05');
            $table->integer('trans_interval')->default(1);
            $table->string('trans_flag', 10)->default('1111000000');
            $table->integer('time_zone')->default(7);
            $table->boolean('realtime')->default(true);
            $table->boolean('encrypt')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
        Schema::dropIfExists('device_log');
        Schema::dropIfExists('finger_log');
        Schema::dropIfExists('error_log');
        Schema::dropIfExists('in_out_records');
        Schema::dropIfExists('device_handshake_configs');
    }
};
