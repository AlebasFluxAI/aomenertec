@extends('errors::minimal-403')

@section('title', __('errors.Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'No tienes permisos para visualizar esta informacion'))

