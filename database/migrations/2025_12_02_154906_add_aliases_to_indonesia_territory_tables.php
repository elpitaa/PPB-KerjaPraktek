<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Provinces: rename columns using raw SQL
        DB::statement('ALTER TABLE provinces CHANGE id prov_id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE provinces CHANGE name prov_name VARCHAR(255)');
        DB::statement('ALTER TABLE provinces CHANGE code prov_code CHAR(2)');
        
        // Cities: rename columns  
        DB::statement('ALTER TABLE cities CHANGE id city_id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE cities CHANGE name city_name VARCHAR(255)');
        DB::statement('ALTER TABLE cities CHANGE code city_code CHAR(4)');
        DB::statement('ALTER TABLE cities CHANGE province_code prov_id CHAR(2)');
        
        // Districts: rename columns
        DB::statement('ALTER TABLE districts CHANGE id dis_id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE districts CHANGE name dis_name VARCHAR(255)');
        DB::statement('ALTER TABLE districts CHANGE code dis_code CHAR(7)');
        DB::statement('ALTER TABLE districts CHANGE city_code city_id CHAR(4)');
        
        // Villages (SubDistricts): rename columns
        DB::statement('ALTER TABLE villages CHANGE id subdis_id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE villages CHANGE name subdis_name VARCHAR(255)');
        DB::statement('ALTER TABLE villages CHANGE code subdis_code CHAR(10)');
        DB::statement('ALTER TABLE villages CHANGE district_code dis_id CHAR(7)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE provinces CHANGE prov_id id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE provinces CHANGE prov_name name VARCHAR(255)');
        DB::statement('ALTER TABLE provinces CHANGE prov_code code CHAR(2)');
        
        DB::statement('ALTER TABLE cities CHANGE city_id id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE cities CHANGE city_name name VARCHAR(255)');
        DB::statement('ALTER TABLE cities CHANGE city_code code CHAR(4)');
        DB::statement('ALTER TABLE cities CHANGE prov_id province_code CHAR(2)');
        
        DB::statement('ALTER TABLE districts CHANGE dis_id id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE districts CHANGE dis_name name VARCHAR(255)');
        DB::statement('ALTER TABLE districts CHANGE dis_code code CHAR(7)');
        DB::statement('ALTER TABLE districts CHANGE city_id city_code CHAR(4)');
        
        DB::statement('ALTER TABLE villages CHANGE subdis_id id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE villages CHANGE subdis_name name VARCHAR(255)');
        DB::statement('ALTER TABLE villages CHANGE subdis_code code CHAR(10)');
        DB::statement('ALTER TABLE villages CHANGE dis_id district_code CHAR(7)');
    }
};
