@extends('layouts.app')

@section('title', 'Korpa - Masline')

@section('content')
    <div class="container py-5">
        <h1 class="section-title">Корпа</h1>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Empty Cart Message -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart text-muted" style="font-size: 5rem;"></i>
                    <h2 class="h4 mt-4">Ваша корпа је празна</h2>
                    <p class="text-muted mb-4">Истражите наше производе и додајте их у корпу</p>
                    <a href="{{ route('catalog') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left me-2"></i>Погледајте производе
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 mb-4">Преглед корпе</h3>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Међузбир:</span>
                            <span>0.00 €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Достава:</span>
                            <span>0.00 €</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Укупно:</strong>
                            <strong>0.00 €</strong>
                        </div>
                        
                        <button class="btn btn-success w-100" disabled>
                            Настави на плаћање
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 