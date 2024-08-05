<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('password');
            $table->date('date_of_birth')->nullable()->after('profile_picture');
            $table->string('phone')->nullable()->after('date_of_birth');
            $table->enum('role', ['user', 'organization'])->default('user')->after('phone');
            $table->enum('status', ['verified', 'unverified'])->default('unverified')->after('role');
            $table->text('bio')->nullable()->after('status');
            $table->json('social_media_links')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone');
            $table->dropColumn('role');
            $table->dropColumn('status');
            $table->dropColumn('bio');
            $table->dropColumn('social_media_links');
        });
    }
}

