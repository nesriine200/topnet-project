@extends('home.index')
<div class="container mt-3">
    <a href="{{ url('roles') }}" class="btn btn-primary mx-2">Roles</a>
    <a href="{{ url('index') }}" class="btn btn-primary mx-2">Users</a>
    <a href="{{ url('permissions') }}" class="btn btn-primary mx-2 " >Permissions</a>
</div>
