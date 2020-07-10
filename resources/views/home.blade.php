<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Reactive core services test case</title>

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="container text-center">
            <div class="row">
                <div class="col-12 mt-4">
                    <h2>Info</h2>
                </div>
                <div class="col-12 mb-4">
                    <b>Installation:</b><br>
                    Please run migrations and seeders<br><br>

                    <b>Purpose:</b><br>
                    Example REST API with personal token authentication based on Laravel Sanctum - new official package availble from Laravel 7.0. We can have multiple users in our database and we can issue a hashed personal access token for each user based on the user device. After authenticating we can use the API to perform CRUD operations, with some small perks on our customer model, using Laravel`s reource controllers and Elloquent:<br><br>
                    1. Retrieve list of all of our customers with included filtering by name and city and pagination.<br>
                    2. Register new customer. As a part of the registration we are passing the customer`s country name. As we have incomplete country information in our database (only country name and iso number), we must perform a third party API call to <a href="https://restcountries.eu" target="_blank">https://restcountries.eu</a> to retrieve the missing country information.
                    3. Get a customer with its country information.<br>
                    4. Edit a customer by getting it by "Route Model Binding".<br>
                    5. Delete a customer by using "Soft Deletes".<br><br>

                    We have basic error handling for 404, missing resource and unavailability of third party resources.<br>
                    On our frontend we have blade templates, html and bootstrap.
            </div>

                <div class="col-12">
                    <h2>Preseeded user</h2>
                </div>
                <div class="col-12 mb-4">
                    Email: <b>apiuser@test.com</b><br>
                    Password: <b>secret</b><br>
                </div>

                <div class="col-12 mb-4">
                    <h2>Generate Bearer token</h2>
                </div>

                <div class="col-12 mb-4">
                    <form novalidate method="POST" action="{{ route('login', app()->getLocale()) }}">
                        @csrf

                        <div class="form-group row justify-content-center">
                            <div class="col-12 col-lg-7">
                                <input id="email" type="email" class="form-control authButton @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email *" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-12 col-lg-7">
                                <input id="password" type="password" class="form-control authButton @error('password') is-invalid @enderror" name="password" placeholder="Password *" autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-12 col-lg-7">
                                <input id="deviceName" type="text" class="form-control authButton @error('device_name') is-invalid @enderror" name="device_name" value="{{ old('device_name') }}" placeholder="Your device *">

                                @error('device_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-12 col-lg-7">
                                <button type="submit" class="btn btn-primary btn-white">
                                    Authenticate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(Session::get('token'))
                    <div class="col-12">
                        <h2>Token</h2>
                    </div>
                    <div class="col-12 mb-4">
                        {{ Session::get('token') }}
                    </div>
                @endif

                <div class="col-12">

                    <h2>The request</h2>
                    Content-Type: <b>application/json</b><br>
                    Accept: <b>application/json</b><br>
                    Authorization: <b>Bearer token</b><br><br>
                </div>

                <div class="col-12">
                    <h2>Endpoints</h2>
                </div>
                <div class="col-12 mb-4">
                    Get all customers - <b>GET: .../api/customers</b> | Filters by 'name' and 'city' as GET params<br>
                    Get a customer - <b>GET: .../api/customers/{id}</b> | With country<br>
                    Register a customer - <b>POST: .../api/customers</b> | <u>Req:</u> 'email', 'password', 'name', 'city', 'address', 'country_name' | <u>Opt:</u> 'state', 'zip', 'phone', 'company_name'<br>
                    Edit a customer - <b>PUT: .../api/customers/{id}</b><br>
                    Delete a customer - <b>DELETE: .../api/customers/{id}</b><br>
                </div>

                <div class="col-12">
                    <h2>Postman example request collection</h2>
                </div>
                <div class="col-12 mb-5">
                    <a href="https://www.getpostman.com/collections/aaa0d74cfdf3d401f799" target="_blank">Get example request collection</a>
                </div>
            </div>
        </div>
    </body>
</html>
