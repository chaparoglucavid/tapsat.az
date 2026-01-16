@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ $announcement->title }}</h3>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'owner') }}:</strong>
                                <div>
                                    @if($announcement->store)
                                        <span class="badge bg-label-info me-1">{{ t_db('general', 'store') }}</span>
                                        {{ $announcement->store->store_name }}
                                    @else
                                        <span class="badge bg-label-primary me-1">{{ t_db('general', 'user') }}</span>
                                        {{ $announcement->user->name ?? '-' }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'category') }}:</strong>
                                <div>{{ $announcement->category->getTranslation('name', app()->getLocale()) }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'city') }}:</strong>
                                <div>{{ $announcement->city->getTranslation('name', app()->getLocale()) }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'status') }}:</strong>
                                <div>
                                    <span class="badge bg-label-{{ $announcement->status->color() }}">
                                        {{ $announcement->status->label() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'price') }}:</strong>
                                <div>{{ $announcement->price }} {{ $announcement->currency }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'is_new') }}:</strong>
                                <div>{{ $announcement->is_new ? t_db('general', 'yes') : t_db('general', 'no') }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'has_delivery') }}:</strong>
                                <div>{{ $announcement->has_delivery ? t_db('general', 'yes') : t_db('general', 'no') }}</div>
                            </div>
                            <div class="col-md-3">
                                <strong>{{ t_db('general', 'created_at') }}:</strong>
                                <div>{{ $announcement->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>

                        @if($announcement->rejection_reason)
                            <div class="mb-3">
                                <strong>{{ t_db('general', 'rejection_reason') }}:</strong>
                                <div>{{ $announcement->rejection_reason }}</div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <strong>{{ t_db('general', 'description') }}:</strong>
                            <div class="mt-1">{{ $announcement->description }}</div>
                        </div>

                        @if($announcement->images && $announcement->images->count())
                            <div class="mb-4">
                                <strong>{{ t_db('general', 'images') }}:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($announcement->images as $image)
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="" class="img-thumbnail" style="max-width: 150px;">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">{{ t_db('general', 'complaints') }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('announcements.complaints.store', $announcement->uuid) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'complaint_subject') }}</label>
                                <select name="complaint_subject_id" class="form-select" required>
                                    <option value="">{{ t_db('general', 'select') }}</option>
                                    @foreach($complaintSubjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('complaint_subject_id') == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'complaint_message') }}</label>
                                <textarea name="message" class="form-control" rows="3">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                {{ t_db('general', 'send_complaint') }}
                            </button>
                        </form>

                        <hr class="my-4">

                        <h5 class="mb-3">{{ t_db('general', 'complaint_history') }}</h5>
                        <ul class="list-group">
                            @forelse($announcement->complaints as $complaint)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div><strong>{{ $complaint->subject->name ?? '-' }}</strong></div>
                                            @if($complaint->message)
                                                <div class="mt-1">{{ $complaint->message }}</div>
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            {{ $complaint->created_at->format('d.m.Y H:i') }}
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">
                                    {{ t_db('general', 'no_records_found') }}
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

