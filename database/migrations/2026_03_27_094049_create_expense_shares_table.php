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
        Schema::create('expense_shares', function (Blueprint $table) {
            $table->id();
            $table->decimal("amount", 10, 2);

            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("group_id");
            $table->unsignedBigInteger("expense_id");

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("group_id")->references("id")->on("groups")->onDelete("cascade");
            $table->foreign("expense_id")->references("id")->on("expenses")->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_shares');
    }
};
