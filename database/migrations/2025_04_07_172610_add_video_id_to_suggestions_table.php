<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->string('video_id')->nullable()->after('youtube_link');
        });
    }
    
    public function down()
    {
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropUnique(['video_id']);
            $table->dropColumn('video_id');
        });
    }
    
};
