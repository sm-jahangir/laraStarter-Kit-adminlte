@extends('layouts.backend.app')

@section('title', 'Menu Builder')

@section('content')

	<div class="content-wrapper">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-menu icon-gradient bg-mean-fruit">
						</i>
					</div>
					<div>Menu Builder ({{ $menu->name }})</div>
				</div>
				<div class="page-title-actions">
					<div class="d-inline-block">
						<a href="{{ route('app.menus.index') }}" class="btn-shadow btn btn-danger">
							<span class="btn-icon-wrapper pr-2 opacity-7">
								<i class="fas fa-arrow-circle-left fa-w-20"></i>
							</span>
							{{ __('Back to list') }}
						</a>

						<a href="{{ route('app.menus.item.create', $menu->id) }}" class="btn btn-shadow btn-primary">
							<i class="fas fa-plus-circle"></i>
							<span>Create Menu Item</span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				{{-- how to use callout --}}
				<div class="main-card mb-3 card">
					<div class="card-body">
						<h5 class="card-title">How To Use:</h5>
						<p>You can output a menu anywhere on your site by calling <code>menu('name')</code></p>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body menu-builder">
						<h5 class="card-title">Drag and drop the menu Items below to re-arrange them.</h5>
						<div class="dd">
							<ol>
								@forelse ($menu->menuItems as $item)
									<li>
										<span> {{ $item->title }} </span>
									</li>
								@empty
									<div class="text-center">
										<strong>No Menu Items Found :)</strong>
									</div>
								@endforelse
							</ol>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>
	</div>
@endsection

@push('js')
@endpush
