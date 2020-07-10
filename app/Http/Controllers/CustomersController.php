<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if($request->has('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if($request->has('city')) {
            $query->where('city', $request->city);
        }

        return $query->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request) {
        $validatedData = $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:customers,email',
            'password' => 'required|string|min:6|max:191',
            'name' => 'required|max:191',
            'city' => 'required|max:191',
            'address' => 'required|max:191',
            'country_name' => 'required|max:191|exists:countries,name',
            'state' => 'nullable|max:191',
            'zip' => 'nullable|max:191',
            'phone' => 'nullable|max:191',
            'company_name' => 'nullable|max:191',
        ]);
        // The default validation messages will do for that test api

        $country = Country::where('name', $validatedData["country_name"])->first();

        // If there is only partial info for that country in the db
        if(!$country->fullInformation) {
            // Make the third party API call
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $CURLrequest = $client->request('GET', 'https://restcountries.eu/rest/v2/alpha/'.$country->iso_2);

            $response = $CURLrequest->getBody()->getContents();
            $apiCountry = json_decode($response, true);

            // Update the country record
            $country->iso_3 = $apiCountry['alpha3Code'] ? $apiCountry['alpha3Code'] : null;
            $country->capital = $apiCountry['capital'] ? $apiCountry['capital'] : null;
            $country->area = $apiCountry['area'] ? $apiCountry['area'] : null;
            $country->flag = $apiCountry['flag'] ? $apiCountry['flag'] : null;
            $country->currency_code = $apiCountry['currencies'][0]['code'] ? $apiCountry['currencies'][0]['code'] : null;
            $country->currency_symbol = $apiCountry['currencies'][0]['symbol'] ? $apiCountry['currencies'][0]['symbol'] : null;
            $country->save();
        }

        // Prepare customer values
        $validatedData['password'] = Hash::make($request->password);
        $validatedData['country_id'] = $country->id;
        unset($validatedData["country_name"]);

        $customer = new Customer($validatedData);
        $customer->save();

        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \App\Customer
     */
    public function show(Request $request, Customer $customer)
    {
        return $customer->load('country');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \App\Customer
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:customers,email,'.$customer->id,   // ignore current row
            'password' => 'required|string|min:6|max:191',
            'name' => 'required|max:191',
            'city' => 'required|max:191',
            'address' => 'required|max:191',
            'country_name' => 'required|max:191|exists:countries,name',
            'state' => 'nullable|max:191',
            'zip' => 'nullable|max:191',
            'phone' => 'nullable|max:191',
            'company_name' => 'nullable|max:191',
        ]);
        // The default validation messages will do for that test api

        $country = Country::where('name', $validatedData["country_name"])->first();

        // If there is only partial info for that country in the db
        if($country->id !== $customer->country_id) {
            // Make the third party API call
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $CURLrequest = $client->request('GET', 'https://restcountries.eu/rest/v2/alpha/'.$country->iso_2);

            $response = $CURLrequest->getBody()->getContents();
            $apiCountry = json_decode($response, true);

            // Update the country record
            $country->iso_3 = $apiCountry['alpha3Code'] ? $apiCountry['alpha3Code'] : null;
            $country->capital = $apiCountry['capital'] ? $apiCountry['capital'] : null;
            $country->area = $apiCountry['area'] ? $apiCountry['area'] : null;
            $country->flag = $apiCountry['flag'] ? $apiCountry['flag'] : null;
            $country->currency_code = $apiCountry['currencies'][0]['code'] ? $apiCountry['currencies'][0]['code'] : null;
            $country->currency_symbol = $apiCountry['currencies'][0]['symbol'] ? $apiCountry['currencies'][0]['symbol'] : null;
            $country->save();
        }

        // Prepare customer values
        $validatedData['password'] = Hash::make($request->password);
        $validatedData['country_id'] = $country->id;
        unset($validatedData["country_name"]);

        $customer->fill($validatedData)->save();

        return $customer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \App\Customer
     * @throws \Exception
     */
    public function destroy(Request $request, Customer $customer)
    {
        $customer->delete();
        return $customer;
    }
}
