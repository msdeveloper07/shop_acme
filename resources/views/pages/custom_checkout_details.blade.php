@extends('layouts.checkout')
@section('content')

   @if($checkout_type == "multistep")
      @include('pages/multistep_checkout')
   @else
      @include('pages/single_checkout')
   @endif

@stop