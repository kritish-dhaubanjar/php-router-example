<?php

use Lapetus\Database\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    $this->DB->prepare("
      CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    ")->execute();
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    $this->DB->prepare("DROP TABLE IF EXISTS users")->execute();
  }
};
