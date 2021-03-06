<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('m_customers');
            $table->foreignId('branch_user_id')->nullable()->constrained('m_branch_users');
            $table->foreignId('status_id')->constrained('m_statuses');
            $table->foreignId('product_id')->constrained('m_products');
            $table->string('code');
            $table->double('quantity')->default(0);
            $table->double('total_price')->default(0);
            $table->datetime('schedule')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_transactions');
    }
}
