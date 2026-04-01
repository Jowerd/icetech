@extends('layouts.app')

@section('title', 'გვერდი ვერ მოიძებნა • ICETECH')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <style>
        .error-page {
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 20px;
        }

        .error-code {
            font-size: 7rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #00a4bd, #1a365d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0;
        }

        .error-icon {
            font-size: 3.5rem;
            color: #00a4bd;
            margin-bottom: 20px;
            display: block;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 12px;
        }

        .error-subtitle {
            color: #6c757d;
            font-size: 1rem;
            max-width: 420px;
            margin: 0 auto 32px auto;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-home {
            background: #00a4bd;
            color: #fff;
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-home:hover {
            background: #1a365d;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-back {
            background: transparent;
            color: #1a365d;
            border: 2px solid #1a365d;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #1a365d;
            color: #fff;
        }

        .error-divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #00a4bd, #1a365d);
            border-radius: 2px;
            margin: 20px auto 28px auto;
        }
    </style>
@endpush

@section('content')
<div class="error-page">
    <div>
        <i class="bi bi-compass error-icon"></i>
        <p class="error-code">404</p>
        <div class="error-divider"></div>
        <h1 class="error-title">გვერდი ვერ მოიძებნა</h1>
        <p class="error-subtitle">
            სამწუხაროდ, თქვენ მიერ მოთხოვნილი გვერდი არ არსებობს ან გადაიტანეს სხვა მისამართზე.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn-home">
                <i class="bi bi-house-door"></i> მთავარ გვერდზე
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="bi bi-arrow-left"></i> უკან
            </a>
        </div>
    </div>
</div>
@endsection
