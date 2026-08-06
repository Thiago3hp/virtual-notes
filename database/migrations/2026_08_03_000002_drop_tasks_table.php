<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * The app's domain moved from a generic "Task" to "Chamado" (IT support
 * ticket), matching exactly what T.I-Sesa-bot writes to MySQL. The old
 * tasks table/model/controllers are no longer part of the app.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tasks');
    }

    public function down(): void
    {
        // Intentionally not recreated: superseded by chamados.
    }
};
