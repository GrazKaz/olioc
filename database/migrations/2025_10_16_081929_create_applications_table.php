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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('rpw');
            $table->date('data_wplywu');
            $table->Integer('nr_wniosku');
            $table->string('powiat_gmina',1);
            $table->string('urzad');
            $table->string('gmina');
            $table->string('powiat');
            $table->string('ulica');
            $table->string('nr_budynku');
            $table->string('kod_pocztowy');
            $table->string('miejscowosc');
            $table->foreignId('task_id');
            $table->string('tresc',1000);
            $table->integer('schron_liczba')->nullable();
            $table->integer('schron_liczba_osob')->nullable();
            $table->integer('mds_liczba')->nullable();
            $table->integer('mds_liczba_osob')->nullable();
            $table->integer('ukrycie_liczba')->nullable();
            $table->integer('ukrycie_liczba_osob')->nullable();
            $table->decimal('koszt_calkowity');
            $table->decimal('wydatki_biezace')->default(0);
            $table->decimal('wydatki_inwestycyjne')->default(0);
            //tu bedziemy obliczać sumę wydatki_biezace+ wydatki_inwestycyjne suma1
            $table->decimal('dotacja_biezaca')->default(0);;
            $table->decimal('dotacja_na_wydatki')->default(0);
            //tu bedziemy obliczać sumę dotacja_biezaca+ dotacja_na_wydatki suma2
            $table->decimal('srodki_biezaca')->default(0);;
            $table->decimal('srodki_inwestycyjne')->default(0);
            //tu bedziemy obliczać sumę srodki_biezaca+ srodki_inwestycyjne suma3
            $table->integer('dyspozycja');
            $table->integer('dzial');
            $table->integer('status');
            $table->decimal('kwota_umowy')->default(0);
            $table->text('nr_zad_umowy')->nullable();
            $table->integer('typ_zadania')->nullable();
            $table->text('uwagi')->nullable();
            $table->date('wysylka_data')->nullable();
            $table->integer('wysylka_nr_pozycji')->nullable();
            $table->integer('zgoda_mswia')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
