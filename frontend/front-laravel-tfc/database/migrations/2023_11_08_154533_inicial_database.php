<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    // alterar value para valores que queremos e adicionar outra tabela de 1 para N com o container com os valores atuais do container e o seu histórico

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('perms', function (Blueprint $table) {
            $table->id('perm_id');
            $table->string('perm_name');

            $table->timestamps();
        });

        Schema::create('perms_relations', function (Blueprint $table) {
            $table->id('perm_relation_id');
            $table->unsignedBigInteger('perm_id');
            $table->string('perm_name');
            
            $table->foreign('perm_id')->references('perm_id')->on('perms')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name');
            $table->string('user_pass');
            $table->integer('user_super');
            $table->string('user_guid')->nullable();
            $table->unsignedBigInteger('perm_id')->nullable();
            
            $table->foreign('perm_id')->references('perm_id')->on('perms')->onDelete('cascade');
            $table->timestamps();
        });

        \DB::table('users')->insert([
            'user_name' => 'root',
            'user_pass' => \Hash::make('wo9384yjfrtw3978gnh89x04fng'),
            'user_super' => 1,
        ]);
        \DB::table('perms')->insert([
            'perm_name' => 'autorized',
        ]);
        \DB::table('perms_relations')->insert([
            'perm_id' => 1,
            'perm_name' => 'autorized',
        ]);

        // create table container 
        Schema::create('containers', function (Blueprint $table) {
            $table->id('container_id');
            $table->string('container_name');
            $table->string('container_location');
            $table->string('container_guid');
            $table->float('container_margin_ph');
            $table->float('container_margin_ec');
            $table->float('container_action_time_ph');
            $table->float('container_action_time_ec');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->timestamps();
        });

        // create table arduinos
        Schema::create('arduinos',function (Blueprint $table) {
            $table->id('arduino_id');
            $table->string('arduino_name');
            $table->unsignedBigInteger('container_id')->nullable();
            $table->string('arduino_guid');
            $table->unsignedBigInteger('user_id');
            
            $table->foreign('container_id')->references('container_id')->on('containers');
            $table->foreign('user_id')->references('user_id')->on('users');
        });

        // create table values
        Schema::create('target_values', function (Blueprint $table){
            $table->id('value_id');
            $table->float('value_ph');
            $table->float('value_temp');
            $table->float('value_electric_condutivity');
            $table->unsignedBigInteger('container_id');

            $table->foreign('container_id')->references('container_id')->on('containers');
        });

        // create table real_time_values
        Schema::create('real_time_values', function (Blueprint $table){
            $table->id('real_time_value_id');
            $table->float('value_ph');
            $table->float('value_temp');
            $table->float('value_electric_condutivity');
            $table->dateTime('value_time');
            $table->unsignedBigInteger('container_id');

            $table->foreign('container_id')->references('container_id')->on('containers');
        });

        // create table reles
        Schema::create('reles', function (Blueprint $table){
            $table->id('rele_id');
            $table->string('rele_name');
            $table->boolean('rele_state');
            $table->unsignedBigInteger('arduino_id');

            $table->foreign('arduino_id')->references('arduino_id')->on('arduinos');
        });

        // create sensor_types
        Schema::create('sensor_types', function(Blueprint $table){
            $table->id('sensor_type_id');
            $table->string('sensor_type_name');
        });

        // create sensors 
        Schema::create('sensors', function(Blueprint $table){
            $table->id('sensor_id');
            $table->string('sensor_name');
            $table->unsignedBigInteger('sensor_type_id');
            $table->unsignedBigInteger('rele_id');

            $table->foreign('sensor_type_id')->references('sensor_type_id')->on('sensor_types');
            $table->foreign('rele_id')->references('rele_id')->on('reles');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
