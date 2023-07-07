<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request){
      $items = Company::all();
      return view('company.index', ['items' => $items]);
    }

    public function add(Request $request){
      return view('company.add');
    }

    public function create(Request $request){
      $this->validate($request, Company::$rules);
      $company = new Company;
      $form = $request->all();
      unset($form['_token']);
      $company->fill($form)->save();
      return redirect('/company');
    }
}
