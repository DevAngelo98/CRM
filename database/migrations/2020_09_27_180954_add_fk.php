<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // FK Customer -> Orders 1:N
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('customer_id', 'customer_orders')->references('id')->on('customers');
        });

        // FK Customer -> Orders N:N tabella pivot Contratto
        Schema::table('customer_order', function (Blueprint $table) {

            $table->bigInteger('customer_id')
                ->unsigned()
                ->index();

            $table->foreign('customer_id', 'customer_order')
                ->references('id')
                ->on('customers');

            $table->bigInteger('order_id')
                ->unsigned()
                ->index();

            $table->foreign('order_id', 'order_customer')
                ->references('id')
                ->on('orders');
        });

        // FK Order -> Tag N:N
        Schema::table('order_tag', function (Blueprint $table) {

            $table->bigInteger('order_id')
                ->unsigned()
                ->index();

            $table->foreign('order_id', 'order_tag')
                ->references('id')
                ->on('orders');

            $table->bigInteger('tag_id')
                ->unsigned()
                ->index();

            $table->foreign('tag_id', 'tag_order')
                ->references('id')
                ->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('customer_orders');
            $table->dropColumn('customer_id');
        });

        Schema::table('customer_order', function (Blueprint $table) {
            $table->dropForeign('customer_order');
            $table->dropColumn('customer_id');

            $table->dropForeign('order_customer');
            $table->dropColumn('order_id');
        });

        Schema::table('order_tag', function (Blueprint $table) {

            $table->dropForeign('order_tag');
            $table->dropColumn('order_id');

            $table->dropForeign('tag_order');
            $table->dropColumn('tag_id');
        });
    }
}
