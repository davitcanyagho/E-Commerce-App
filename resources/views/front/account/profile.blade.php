@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
            @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <form action="" name="profileForm" id="profileForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Nama</label>
                                    <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Ketik nama anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Ketik email anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Nomor HP</label>
                                    <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Ketik nomor hp anda" class="form-control">
                                    <p></p>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Alamat</h2>
                    </div>
                    <form action="" name="addressForm" id="addressForm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Nama depan</label>
                                    <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Ketik nama depan anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="name">Nama belakang</label>
                                    <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Ketik nama belakang anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="email" placeholder="Ketik email anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Nomor HP</label>
                                    <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Ketik nomor hp anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Negara</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Pilih negara</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                            <option {{ (!empty($address) && $address->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Alamat</label>
                                    <textarea name="address" id="address" cols="30" rows="10" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone">Apartemen</label>
                                    <input value="{{ (!empty($address)) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="Ketik apartemen anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone">Kota</label>
                                    <input value="{{ (!empty($address)) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="Ketik kota anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone">Jalan</label>
                                    <input value="{{ (!empty($address)) ? $address->state : '' }}" type="text" name="state" id="state" placeholder="Ketik jalan anda" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone">Kode Pos</label>
                                    <input value="{{ (!empty($address)) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Ketik kode pos anda" class="form-control">
                                    <p></p>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#profileForm").submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: "{{ route('account.updateProfile') }}",
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {

                    window.location.href = "{{ route('account.profile') }}";

                    $("#profileForm #name").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                    $("#profileForm #enail").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                    $("#profileForm #phone").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                        window.location.href = "{{ route('account.profile') }}";

                } else {
                    var errors = response.errors;
                    if (errors.name) {
                        $("#profileForm #name").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.name)
                        .addClass('invalid-feedback');
                    } else {
                        $("#profileForm #name").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.email) {
                        $("#profileForm #email").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.email)
                        .addClass('invalid-feedback');
                    } else {
                        $("#profileForm #email").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.phone) {
                        $("#profileForm #phone").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.phone)
                        .addClass('invalid-feedback');
                    } else {
                        $("#profileForm #phone").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }
                }
            }
        });
    });

    $("#addressForm").submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: "{{ route('account.updateAddress') }}",
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response.status == true) {

                    window.location.href = "{{ route('account.profile') }}";

                    $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                    $("#enail").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                    $("#phone").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');

                        window.location.href = "{{ route('account.profile') }}";

                } else {
                    var errors = response.errors;
                    if (errors.first_name) {
                        $("#first_name").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.first_name)
                        .addClass('invalid-feedback');
                    } else {
                        $("#first_name").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.last_name) {
                        $("#last_name").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.last_name)
                        .addClass('invalid-feedback');
                    } else {
                        $("#last_name").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.email) {
                        $("#addressForm #email").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.email)
                        .addClass('invalid-feedback');
                    } else {
                        $("#addressForm #email").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.mobile) {
                        $("#mobile").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.mobile)
                        .addClass('invalid-feedback');
                    } else {
                        $("#mobile").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.country_id) {
                        $("#country_id").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.country_id)
                        .addClass('invalid-feedback');
                    } else {
                        $("#country_id").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.address) {
                        $("#address").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.address)
                        .addClass('invalid-feedback');
                    } else {
                        $("#address").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.apartment) {
                        $("#apartment").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.apartment)
                        .addClass('invalid-feedback');
                    } else {
                        $("#apartment").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.city) {
                        $("#city").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.city)
                        .addClass('invalid-feedback');
                    } else {
                        $("#city").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.state) {
                        $("#state").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.state)
                        .addClass('invalid-feedback');
                    } else {
                        $("#state").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }

                    if (errors.zip) {
                        $("#zip").addClass('is-invalid')
                        .siblings('p')
                        .html(errors.zip)
                        .addClass('invalid-feedback');
                    } else {
                        $("#zip").removeClass('is-invalid')
                        .siblings('p')
                        .html('')
                        .removeClass('invalid-feedback');
                    }
                }
            }
        });
    });

    // addressForm
</script>
@endsection