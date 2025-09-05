{{-- resources/views/specialists/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <style>
        .page-wrap {
            max-width: 100%;
        }

        .header-line {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .count-badge {
            padding: .35rem .6rem;
            border-radius: 999px;
            font-size: .875rem;
            background: #eef2ff;
            color: #3b5bfd;
        }

        .search-wrap {
            position: sticky;
            top: 0;
            z-index: 5;
            background: rgba(248, 249, 250, .85);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .search-holder {
            position: relative;
        }

        .search-input {
            width: 100%;
            height: 2.75rem;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, .08);
            padding: 0 2.5rem 0 2.5rem; /* иконки слева/справа */
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }

        .search-input:focus {
            border-color: rgba(59, 91, 253, .45);
            box-shadow: 0 6px 16px rgba(59, 91, 253, .08);
            outline: 0;
        }

        .search-icon, .clear-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            opacity: .6;
        }

        .search-icon {
            left: .8rem;
        }

        .clear-icon {
            right: .8rem;
            cursor: pointer;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 12px;
        }

        @media (min-width: 992px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .card {
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 14px;
            background: #fff;
            padding: 14px 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            transition: transform .06s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
            border-color: rgba(59, 91, 253, .18);
        }

        .title {
            font-weight: 700;
            font-size: 1.05rem;
            line-height: 1.2;
            margin: 0;
        }

        .chip {
            font-size: .75rem;
            padding: .2rem .55rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, .2);
        }

        .meta, .small-muted {
            color: #6c757d;
            font-size: .92rem;
        }

        .small-muted {
            font-size: .78rem;
        }

        .rows {
            display: grid;
            grid-template-columns: 1fr auto; /* текст + чип справа */
            gap: .5rem .75rem;
            align-items: start;
        }

        .line {
            display: flex;
            align-items: center;
            gap: .4rem;
            min-height: 1.25rem;
        }

        .label {
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .70rem;
            color: #99a1ab;
        }

        /* Клаmp: красиво обрезаем многословных специалистов */
        .clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Подсветка совпадений */
        mark.hl {
            background: #fff3cd;
            color: inherit;
            padding: 0 .08em;
            border-radius: 2px;
        }

        /* Пустое состояние */
        .empty {
            text-align: center;
            color: #8a8f98;
            padding: 28px 0;
        }
    </style>

    <div class="container page-wrap py-4">
        <div class="header-line mb-2 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h1 class="m-0">Специалисты</h1>
                <span class="count-badge" id="count-badge">{{ $specialists->count() }}</span>
            </div>

            <a href="{{ route('specialists.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Добавить специалиста
            </a>
        </div>

        <div class="search-wrap py-3 mb-3">
            <div class="search-holder">
                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M20 20L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input id="live-search" class="search-input" type="text"
                       placeholder="Ищи по имени"
                       autocomplete="off">
                <svg id="clear-btn" class="clear-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     style="display:none">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="small-muted mt-1">Печатай. Автофильтр. Нажми «Esc» чтобы очистить.</div>
        </div>

        <div id="cards-grid" class="grid">
            @forelse($specialists as $s)
                @php
                    $needle = \Illuminate\Support\Str::of($s->name ?? '')->lower();
                @endphp

                <div class="card spec-card" data-search="{{ $needle }}">
                    <div class="rows">
                        <h2 class="title clamp-1 js-name">{!! e($s->name) !!}</h2>
                        <span class="chip">
                            <div class="flex justify-center gap-2">
                            {{-- Редактировать --}}
                            <a href="{{ route('specialists.edit', $s->id) }}"
                               class="p-2 rounded hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 text-black"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                <path fill-rule="evenodd"
                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                      clip-rule="evenodd"/>
                            </svg>
                            </a>

                            {{-- Удалить --}}
                            <form action="{{ route('specialists.destroy', $s->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Точно удалить специалиста?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 rounded hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-red-600"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M9 2a1 1 0 00-1 1v1H5a1 1 0 000 2v10a2 2 0 002 2h6a2 2 0 002-2V6a1 1 0 100-2h-3V3a1 1 0 00-1-1H9zm-2 6a1 1 0 112 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8z"
                                          clip-rule="evenodd"/>
                                </svg>
                                </button>
                            </form>
                        </div>
                        </span>

                        @if($s->education)
                            <div>
                                <div class="label">Специализация</div>
                                <div class="js-education clamp-2">{{ $s->specialization->name ?? "-" }}</div>
                            </div>
                            <div></div>
                        @endif

                        @if($s->workplace->name || $s->workplace_id)
                            <div class="line js-workplace clamp-2">
                                <div class="label">Место работы</div>
                                <div class="js-education clamp-2">{{ $s->workplace->name ?? "-" }}</div>
                            </div>
                            <div></div>
                        @endif

                        @if($s->education)
                            <div>
                                <div class="label">Образование</div>
                                <div class="js-education clamp-2">{{ $s->education }}</div>
                            </div>
                            <div></div>
                        @endif

                        @if($s->additional_info)
                            <div>
                                <div class="label">Доп. информация</div>
                                <div class="js-info clamp-2">{{ $s->additional_info }}</div>
                            </div>
                            <div></div>
                        @endif

                        <div class="small-muted">ID: {{ $s->id }}</div>
                        <div></div>
                    </div>
                </div>
            @empty
                <div class="empty">Пусто. Как список задач после дедлайна. Шучу. Но пусто.</div>
            @endforelse
        </div>

        <div id="nothing-found" class="empty" style="display:none">Ничего не нашлось. Убери пару символов, дай базе
            шанс.
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('live-search');
            const clear = document.getElementById('clear-btn');
            const cards = Array.from(document.querySelectorAll('.spec-card'));
            const grid = document.getElementById('cards-grid');
            const empty = document.getElementById('nothing-found');
            const badge = document.getElementById('count-badge');

            // Быстрые хоткеи
            document.addEventListener('keydown', e => {
                if (e.key === '/') {
                    e.preventDefault();
                    input.focus();
                }
                if (e.key === 'Escape') {
                    input.value = '';
                    applyFilter();
                }
            });

            input.addEventListener('input', debounced(applyFilter, 70));
            clear.addEventListener('click', () => {
                input.value = '';
                input.focus();
                applyFilter();
            });

            function applyFilter() {
                const q = input.value.trim().toLowerCase();
                let visible = 0;

                cards.forEach(card => {
                    const hay = card.dataset.search; // уже lower на бэке
                    const match = q === '' || hay.includes(q);
                    card.style.display = match ? '' : 'none';
                    if (match) {
                        visible++;
                        highlight(card, q);
                    } else {
                        highlight(card, ''); // снять подсветку
                    }
                });

                grid.style.display = visible ? 'grid' : 'none';
                empty.style.display = visible ? 'none' : 'block';
                badge.textContent = visible;
                clear.style.display = q ? 'block' : 'none';
            }

            // Подсветка совпадений по основным полям
            function highlight(card, q) {
                ['js-name'].forEach(cls => {         // ← раньше было ['js-name','js-workplace','js-education','js-info']
                    const el = card.querySelector('.' + cls);
                    if (!el) return;
                    const original = el.getAttribute('data-original') || el.textContent;
                    if (!el.getAttribute('data-original')) el.setAttribute('data-original', original);
                    el.innerHTML = q ? markMatches(original, q) : escapeHtml(original);
                });
            }

            function markMatches(text, q) {
                const safe = escapeRegExp(q);
                if (!safe) return escapeHtml(text);
                const re = new RegExp('(' + safe + ')', 'gi');
                return escapeHtml(text).replace(re, '<mark class="hl">$1</mark>');
            }

            // Мелочь, но важна
            function escapeHtml(str) {
                return str.replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[m]));
            }

            function escapeRegExp(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            function debounced(fn, wait) {
                let t = null;
                return (...a) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, a), wait);
                };
            }

            // Инициализация
            applyFilter();
        })();
    </script>
@endsection
