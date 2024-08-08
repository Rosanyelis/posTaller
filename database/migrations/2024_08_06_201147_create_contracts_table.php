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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['annual', 'two years']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('due', 10, 2);
            $table->text('note');
            $table->string('file')->nullable();
            $table->enum('status', ['Por Facturar', 'Activo', 'Vencido'])->default('Por Facturar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
