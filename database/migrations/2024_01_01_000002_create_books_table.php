<?php
// 2024_01_01_000002_create_books_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->string('publisher')->nullable();
            $table->smallInteger('published_year')->nullable(); // smallInt supports years before 1901
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};
