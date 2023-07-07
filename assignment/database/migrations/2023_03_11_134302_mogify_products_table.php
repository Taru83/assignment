<!-- 

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// class CreateProductsTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::create('products', function (Blueprint $table) {
//             $table->bigIncrements('id');
//             $table->foreign('company_id')->references('id')->on('companies');
//             $table->string('product_name');
//             $table->integer('price');
//             $table->integer('stock');
//             $table->text('comment')->nullable();
//             $table->integer('img_path');
//             $table->timestamps();
//         });
//     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
} -->
