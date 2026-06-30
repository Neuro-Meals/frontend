@extends('layouts.landing')

@section('title', config('app.name', 'Nutrio Meals') . ' - Premium Healthy Meal Subscriptions')

@section('content')
    @include('landing.partials.header')

    <main>
        @include('landing.partials.hero')
        @include('landing.partials.about')
        @include('landing.partials.why')
        @include('landing.partials.how-it-works')
        @include('landing.partials.plans')
        @include('landing.partials.dashboard')
        @include('landing.partials.gallery')
        @include('landing.partials.subscription')
        @include('landing.partials.delivery')
        @include('landing.partials.calculator')
        @include('landing.partials.testimonials')
        @include('landing.partials.app')
        @include('landing.partials.cta')
    </main>

    @include('landing.partials.footer')
@endsection
