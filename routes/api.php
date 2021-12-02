<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\PasswordsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeasurementsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProductComponentsController;

Route::post('/sendmessage', [ChatController::class, 'sendMessage']);
    Route::post('/getconversationbyauth', [ChatController::class, 'getConvoAuth']);
    Route::post('/getmessages', [ChatController::class, 'getMessages']);
        Route::get('/viewtailorprofile/{id}', [TailorController::class, 'viewTailor']);
Route::post('/newpassword', [PasswordsController::class, 'verifyCodeandPassword']);


 Route::get('/admindashboard', [AdminController::class, 'adminDashboard']);
     Route::post('/isallowed', [AdminController::class, 'isAllowed']);
    Route::delete('/deleteTailor/{id}', [TailorController::class, 'deleteTailor']);
    Route::delete('/deletecomplaint/{id}', [AdminController::class, 'deleteComplaint']);
    Route::get('/getallusers', [UsersController::class, 'getAllUsers']);
Route::delete('/deletecustomer/{id}', [AdminController::class, 'deletecustomer']);
Route::post('/test', [OrderController::class, 'orders']);
Route::get('/viewcustomer/{id}',[UsersController::class, 'customer']);
Route::post('/adminlogin',[UsersController::class, 'adminLogin']);
Route::post('/addcarasolimages', [AdminController::class, 'addImages']);
    Route::get('/getcarasolimages', [AdminController::class, 'getImages']);
    Route::get('/makeallow', [AdminController::class, 'requestedtailors']);

Route::post('/register', [UsersController::class, 'registeration']);
Route::post('/chat', [ChatController::class, 'sendMessage']);
Route::post('/logintailor', [UsersController::class, 'logintailor']);

Route::post('/login', [UsersController::class, 'login']);
Route::post('/gettailorbyrating', [TailorController::class,'getTailorbyRating']);
Route::get('/bestsellers', [TailorController::class,'bestSellers']);
Route::get('/tailorproductswithclick/{id}', [TailorController::class,'clicktailorproducts']);
Route::post('/forgot', [PasswordsController::class, 'forgotPassword']);
Route::post('/send', [PasswordsController::class, 'sendForgetPassword']);
Route::post('/verifycode', [PasswordsController::class, 'verifyCode']);
Route::post('/getpassword', [PasswordsController::class, 'tempPassword']);

Route::post('/gettailorsbyrating', [TailorController::class, 'getTailorbyRating']);
Route::post('/tailorregister', [UsersController::class, 'registerTailor']);
Route::post('/verifyphone', [UsersController::class, 'verify']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/showone/{id}', [ProductsController::class, 'showRespective']);
Route::post('/getprouctbycategory', [ProductsController::class, 'getProductByCategory']);
Route::post('/filterproducts', [ProductsController::class, 'filter']);
Route::get('/tailoproduct/{id}', [ProductsController::class, 'getProductsByTailor']);


Route::get('/getbrands', [ProductsController::class, 'showBrands']);
Route::get('/gettailors', [TailorController::class, 'getAllTailors']);

Route::get('/getproductbysubcategory/{id}', [ProductsController::class, 'getProductBySubcategory']);

    Route::get('/categories', [CategoryController::class, 'showCategories']);

Route::post('/getsubcategorieswithcategory', [CategoryController::class, 'getAllSub']);

    Route::get('/subcategories', [SubCategoryController::class, 'showsubCategories']);
    Route::post('/addsubcategory', [SubCategoryController::class, 'addSubCategory']);

    Route::get('/getAll', [CategoryController::class, 'getAllCategories']);


Route::get('/showproduct', [ProductsController::class, 'showProduct']);

Route::get('/showall', [ProductsController::class, 'showAll']);
Route::post('/searchproduct', [ProductsController::class, 'searchProduct']);
    Route::post('/searchtailor', [TailorController::class, 'searchTailor']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/makeorder', [OrderController::class, 'completeOrder']);
    Route::get('/getproductsoftailor', [TailorController::class,'AuthTailorProducts']);
    Route::get('/orderslist', [TailorController::class,'orderslist']);
    Route::post('/statusupdate', [OrderController::class, 'statusUpdate']);
    Route::get('/userorders', [OrderController::class, 'authOrders']);

 //   Route::get('/showall', [ProductsController::class, 'showAll']);
 Route::post('/addtailorbank', [BankController::class, 'addbank']);
 Route::post('/addtocart', [CartController::class, 'addcart']);
 Route::get('/getcart', [CartController::class, 'getCart']);
Route::post('/sendnotification', [AdminController::class, 'send_push_notification']);

 Route::delete('/deletecart/{id}', [CartController::class, 'delete']);

    Route::post('/logout', [UsersController::class, 'logout']);
Route::post('/changepassword', [PasswordsController::class, 'updatePassword']);

    Route::post('/giverating', [TailorController::class, 'giveTailorRating']);

Route::post('/publishproduct', [ProductsController::class, 'publishpost']);

    Route::post('/users', [UsersController::class, 'storeUser']);

    Route::post('/userupdate', [UsersController::class, 'updateUserInfo']);

    Route::get('/getuser/{id}', [UsersController::class, 'getUserData']);

    Route::post('/updatetailor', [TailorController::class, 'updateTailorProfile']);
    Route::post('/addshopaddress', [LocationController::class, 'shopaddress']);
    Route::post('/tailorcnic', [TailorController::class, 'updatecnic']);



    Route::get('/gettailor/{id}', [TailorController::class, 'getTailor']);
    Route::get('/totalrevenue', [OrderController::class, 'totalRevenue']);
    // Route::delete('/deletetailor/{id}', [TailorController::class, 'deleteProduct']);


    Route::post('/setlocation', [LocationController::class, 'store']);

    Route::post('/updatelocation/{id}', [LocationController::class, 'update']);

    Route::get('/showlocation', [LocationController::class, 'show']);
    Route::post('/giveratingtoproduct', [ProductsController::class, 'giveProductRating']);
    Route::post('/getproductrating', [ProductsController::class, 'getProductRating']);
    Route::post('/components', [ProductComponentsController::class, 'addComponent']);
    Route::post('/setspecs', [ProductsController::class, 'setSpecs']);

    Route::delete('/deleteproduct/{id}', [ProductsController::class, 'deleteProduct']);

    Route::post('/viewcustomer', [UsersController::class, 'viewCustomer']);

    Route::delete('/deleterow/{id}', [BankController::class, 'deleteRow']);
    Route::get('/getbankdetails', [BankController::class, 'getBankdetails']);
    Route::get('/getallbanks', [BankController::class, 'getAllBanks']);
    Route::post('/complaint', [AdminController::class, 'submitComplaint']);
    Route::get('/viewallcomplaints', [AdminController::class, 'viewComplaints']);

    Route::post('/viewtailor', [AdminController::class, 'viewTailor']);


    Route::get('/tailordashboard', [TailorController::class, 'tailorDashboard']);
    Route::post('/images', [MeasurementsController::class, 'uploadImages']);
    Route::post('/addmeasurements', [MeasurementsController::class, 'addMeasurements']);


});

