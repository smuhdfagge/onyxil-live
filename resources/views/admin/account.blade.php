@extends('layouts.admin')
@section('title', 'Your account')
@section('content')
<div class="grid-2 grid-wide">
    <section class="panel">
        <h2>Your details</h2>
        <form method="post" action="{{ route('admin.account.update') }}" class="fields">
            @csrf @method('put')
            @foreach (['name' => 'Name', 'email' => 'Email'] as $k => $l)
                <div class="fld fld-wide @error($k) has-err @enderror"><label for="a-{{ $k }}">{{ $l }}</label><input id="a-{{ $k }}" name="{{ $k }}" type="{{ $k === 'email' ? 'email' : 'text' }}" value="{{ old($k, auth()->user()->{$k}) }}" required>@error($k)<p class="err">{{ $message }}</p>@enderror</div>
            @endforeach
            <p class="help fld-wide">To change your password, fill in all three boxes below. Use at least 10 characters.</p>
            <div class="fld fld-wide @error('current_password') has-err @enderror"><label for="a-cur">Current password</label><input id="a-cur" type="password" name="current_password" autocomplete="current-password">@error('current_password')<p class="err">{{ $message }}</p>@enderror</div>
            <div class="fld @error('password') has-err @enderror"><label for="a-new">New password</label><input id="a-new" type="password" name="password" autocomplete="new-password">@error('password')<p class="err">{{ $message }}</p>@enderror</div>
            <div class="fld"><label for="a-new2">Repeat new password</label><input id="a-new2" type="password" name="password_confirmation" autocomplete="new-password"></div>
            <div class="fld fld-wide"><button class="btn btn-primary">Save</button></div>
        </form>
    </section>
    <section class="panel">
        <h2>Admin accounts</h2>
        <ul class="feed">
            @foreach ($users as $u)
                <li><span><strong>{{ $u->name }}</strong> <span class="muted">{{ $u->email }}</span></span>
                    @if ($u->id === auth()->id())<span class="pill pill-on">You</span>
                    @else
                        <form method="post" action="{{ route('admin.account.users.destroy', $u) }}" class="inline" data-confirm="Remove {{ $u->name }}'s admin access?">@csrf @method('delete')<button class="link link-danger">Remove</button></form>
                    @endif
                </li>
            @endforeach
        </ul>
        <h2 style="margin-top:28px">Add an admin</h2>
        @if ($errors->newAdmin->any())<p class="err">{{ $errors->newAdmin->first() }}</p>@endif
        <form method="post" action="{{ route('admin.account.users.store') }}" class="fields">
            @csrf
            <div class="fld"><label for="n-name">Name</label><input id="n-name" name="name" required></div>
            <div class="fld"><label for="n-email">Email</label><input id="n-email" name="email" type="email" required></div>
            <div class="fld fld-wide"><label for="n-pw">Temporary password</label><input id="n-pw" name="password" type="text" minlength="10" required autocomplete="off"><p class="help">Share it with them privately and ask them to change it after signing in.</p></div>
            <div class="fld fld-wide"><button class="btn">Add admin</button></div>
        </form>
    </section>
</div>
@endsection
