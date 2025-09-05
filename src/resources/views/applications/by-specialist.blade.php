@extends('layouts.app')

@section('content')
    <style>
        .page-wrap { max-width: 100%; }
        .muted { color:#6b7280 }
        table { width:100%; border-collapse:collapse; background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:12px; overflow:hidden }
        th,td { padding:.6rem .75rem; border-top:1px solid rgba(0,0,0,.06); font-size:.92rem }
        thead th { background:#f8fafc; border-top:none; text-align:left }
        .row { display:flex; align-items:center; justify-content:space-between; gap:.75rem }
        .btn { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .6rem; border-radius:10px; border:1px solid #e5e7eb; background:#fff; }
        .btn-primary { background:#3b82f6; color:#fff; border-color:#2563eb; }
        .flash { margin-bottom:10px; padding:.6rem .8rem; border-radius:10px; }
        .flash.err{ background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
    </style>

    <div class="container page-wrap py-4">
        <div class="row">
            <div>
                <h1 class="m-0">Заявки специалиста: {{ $spec->name }}</h1>
                <div class="muted">
                    @if($spec->specialization?->name)
                        Специализация: {{ $spec->specialization->name }}
                    @endif
                    @if($spec->workplace_id)
                        • Место работы: {{ $spec->workplace?->name ?? '—' }} (ID {{ $spec->workplace_id }})
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('specialists.index') }}" class="btn">← К специалистам</a>
            </div>
        </div>

        @if(session('err')) <div class="flash err">{{ session('err') }}</div> @endif

        <div id="cards-grid" class="grid">
            @if($apps->count() === 0)
                <div class="muted">У этого специалиста нет заявок. Можно смело удалить.</div>
            @else
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Номер</th>
                        <th>Вызывающая организация</th>
                        <th>Организация проводившая консультацию</th>
                        <th>Пациент</th>
                        <th>Дата консультации</th>
                        <th>Дата создания заявки</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($apps as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->application_number ?? '—' }}</td>
                            <td>{{ $a->requestedByInstitution?->name }}</td>
                            <td>{{ $a->referredFromInstitution?->name }}</td>
                            <td>{{ $a->patient_name ?? '—' }}</td>
                            <td>{{ $a->consultation_date->format('d.m.Y') ?? '—' }}</td>
                            <td>{{ $a->created_at->format('d.m.Y') ?? '—' }}</td>
                            <td>
                                <a href="{{ route('applications.edit', $a->id) }}" class="btn">Изменить</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div style="margin-top:10px">
                    {{ $apps->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
