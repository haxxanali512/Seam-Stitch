<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Tailor;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Validator;

class BankController extends Controller
{
  use ApiResponser;
  public function addBank(Request $request)
  {
    $userid = Auth::user()->id;
    $tailor = Tailor::where('user_id', '=', $userid)->first();
    $tailor_id = $tailor->id;
    $bank_details = $request->validate([
      'account_title' => 'string',
      'account_number' => 'string',
      'bank_name' => 'string',
      'branch_code' => 'string',
      'image' => 'mimes:jpg,png,jped|max:5048'
    ]);
    $fName = explode(" ", $request->account_title);
    $newImage = time() . $fName[0] . '.' . $request->image->extension();
    $request->image->move(public_path('images'), $newImage);

    $userid = Auth::user()->id;
    $tailor = Tailor::where('user_id', '=', $userid)->first();
    $tailor_id = $tailor->id;

    $bank = new Bank();
    $bank->tailor_id = $tailor_id;
    $bank->account_title = $request->account_title;
    $bank->account_num = Crypt::encryptString($request->account_number);
    $bank->bank_name = $request->bank_name;
    $bank->branch_code = $request->branch_code;
    $bank->cheque = $newImage;
    $bank->save();
    $remaining_banks = DB::table('bankdetails')->where('tailor_id','=', $tailor_id)->get();
    return $this->success('Information updated', $remaining_banks);
  }
  public function deleteRow($id)
  {
    $bank = Bank::where('id',$id)->first();
    $id_t = $bank->tailor_id;
    $bank = Bank::find($id)->delete();
    $remaining_banks = DB::table('bankdetails')->where('tailor_id','=', $id_t)->get();
    return $this->success('Bank deleted', $remaining_banks);
  }
  public function getBankdetails()
  {
    $tailor_id = 0;
    // echo(Auth::user()->id);
    $tailor = Tailor::where('user_id', '=', Auth::user()->id)->first();
    $tailor_id = $tailor->id;
    $getBank = Bank::where('tailor_id', '=', $tailor_id)->first();
    if (is_null($getBank)) {
      return $this->error("No records found");
    }
    return $this->success("Bank details", $getBank);
  }
  public function getAllBanks()
  {
    $banks = Bank::all();
    return $this->success("All bank details", $banks);
  }
}
