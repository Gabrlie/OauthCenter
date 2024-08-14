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
        Schema::create('check_oauth_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index()->comment('用户ID');
            $table->string('name')->comment('名称');
            $table->text('redirect')->comment('回调地址');
            $table->text('notes')->comment('备注')->nullable();
            $table->integer('checked')->comment('是否审核通过')->default(0);  // 0 未审核 1 通过 2 未通过
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_oauth_clients');
    }
};
