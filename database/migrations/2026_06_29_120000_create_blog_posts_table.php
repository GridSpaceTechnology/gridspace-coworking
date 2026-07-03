<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->text('featured_image')->nullable();
            $table->string('category_slug')->default('workspace-trends');
            $table->json('tags')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedSmallInteger('read_time')->default(1);
            $table->string('author_name')->nullable();
            $table->string('author_role')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('category_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
