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
        // Убираем уникальность с столбца email
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'phone']);  // Удаляем уникальный индекс
        });

        // Добавляем новый столбец 'task_id', который может быть пустым
        Schema::table('orders', function (Blueprint $table) {
            $table->string('task_id')->nullable(); // nullable() позволяет столбцу быть пустым
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down(): void
    {
        // Восстанавливаем уникальность на столбце ['user_id', 'phone']
        Schema::table('orders', function (Blueprint $table) {
            $table->unique(['user_id', 'phone']);
        });

        // Удаляем столбец task_id
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('task_id');
        });
    }
};
