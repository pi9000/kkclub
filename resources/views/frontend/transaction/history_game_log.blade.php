@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container px-1">
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <div class="title cus-title">@lang('sidenav.history')</div>
            </div>
            <div class="history-options px-15 py-15 mb-15 bg-form">
                <a class="history-option {{ request()->routeIs('history') ? 'active ' : '' }}" onclick="showLoading()" href="{{ route('history') }}" alt="history">@lang('public.deposit')</a>
                <a class="history-option {{ request()->routeIs('history_withdraw') ? 'active ' : '' }}" onclick="showLoading()" href="{{ route('history_withdraw') }}"
                    alt="history">@lang('public.withdraw')</a>
                <a class="history-option {{ request()->routeIs('history_game_log') ? 'active ' : '' }}" onclick="showLoading()" href="{{ route('history_game_log') }}"
                    alt="history">@lang('public.game_log')</a>
            </div>
            <div class="form-wrapper px-15 py-15 bg-form">
                <div id="platform-filter-wrap">
                    <div class="options hide-scrollbar" style="cursor:pointer">
                        <div class="option active" show="showall">@lang('public.all')</div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="show-table">
                        <table id="history" class="table-info">
                            <thead>
                                <tr>
                                    <th>@lang('public.date')</th>
                                    <th>@lang('public.history_amount')</th>
                                    <th>@lang('public.history_status')</th>
                                    <th>@lang('public.history_status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" style="text-align: center;">@lang('public.history_no_data')</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-pagination">
                        <div class="btn-pagination" page="prev">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left"
                                width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentcolor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="15 6 9 12 15 18" />
                            </svg>
                        </div>
                        <div class="page_num"></div>
                        <div class="btn-pagination" page="next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right"
                                width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentcolor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <polyline points="9 6 15 12 9 18" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.layouts.popup')
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var element = document.querySelector('.history-options .active');
            var container = element.closest('.history-options');
            document.querySelectorAll('.history-option').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    localStorage.setItem('historyScroll', container.scrollLeft);
                    if (event.target.href != '#') {
                        window.location.href = event.target.href;
                    }
                });
            });

            if (localStorage.getItem('historyScroll')) {
                setTimeout(function() {
                    container.scrollLeft = localStorage.getItem('historyScroll');
                }, 300);
                localStorage.removeItem('historyScroll');
            } else {
                console.log('cd');
                if (element) {
                    var elementRect = element.getBoundingClientRect();
                    console.log(elementRect);
                    container.scrollTo({
                        left: elementRect.left,
                        behavior: 'instant'
                    })
                }
            }
        });

        var data = @json($history),
            filter = [],
            cols = ["Game", "Bet", "Win","Date"];
        const maxRow = 20;
        createRecordFilter(filter);
        setupTable(data);

        function createRecordFilter(x) {
            if (x.length < 1) {
                document.getElementById('platform-filter-wrap').remove();
                return;
            }
            let parent = document.getElementById('platform-filter-wrap');
            let options = parent.querySelector('.options');
            options.innerHTML = "";
            let option;
            option = document.createElement('div');
            option.setAttribute('class', 'option active');
            option.setAttribute('show', 'showall');
            option.innerHTML = 'All';
            option.addEventListener('click', filterPlatform);
            options.appendChild(option);
            x.forEach((elmnt) => {
                option = document.createElement('div');
                option.setAttribute('class', 'option');
                option.innerHTML = elmnt;
                option.setAttribute('show', elmnt);
                option.addEventListener('click', filterPlatform);
                options.appendChild(option);
            });
            parent.appendChild(options);
        }

        function filterPlatform(event) {
            let elmnt = event.target;
            let con = document.getElementById('platform-filter-wrap');
            con.querySelectorAll('.option').forEach((x) => {
                x.classList.remove('active');
            });
            elmnt.classList.add('active');
            let selected = [];
            if (elmnt.getAttribute('show').toLowerCase() == "showall") {
                selected = data;
            } else {
                data.forEach((row) => {
                    if (row.filter.toLowerCase() == elmnt.getAttribute('show').toLowerCase()) {
                        selected.push(row);
                    }
                });
            }
            setupTable(selected);
        }


        function setupTable(val) {

            const table = document.getElementById('history');
            var tr, th, td;
            try {

                let thead = table.querySelector('thead');
                thead.innerHTML = '';
                let tbody = table.querySelector('tbody');
                tbody.innerHTML = "";

                tr = document.createElement('tr');
                Object.values(cols).forEach((x) => {
                    th = document.createElement('th');
                    th.innerHTML = x;
                    tr.appendChild(th);
                });
                thead.appendChild(tr);


                if (val.length > 0) {

                    Object.values(val).forEach((x) => {
                        tr = document.createElement('tr');
                        Object.entries(x).forEach(([k, v]) => {
                            if (k != 'class' && k != 'filter') {
                                td = document.createElement('td');
                                td.innerHTML = v;
                                tr.appendChild(td);
                                if (k == 'status') {
                                    td.classList.add(x.class);
                                }
                            }
                        });
                        tbody.appendChild(tr);
                    });

                } else {
                    tr = document.createElement('tr');
                    td = document.createElement('td');
                    td.classList.add('empty-record');
                    td.innerHTML = 'No record';
                    td.setAttribute('colspan', cols.length);
                    td.setAttribute('style', 'text-align:center');
                    tr.appendChild(td);
                    tbody.appendChild(tr);
                }
                table.append(thead, tbody);
            } catch (e) {
                console.log(e);
            }

            const totalRow = table.querySelectorAll('tbody tr').length;
            const pageNum = Math.ceil(totalRow / maxRow);
            const container = table.parentNode.closest('.table-container');
            const pagination = container.querySelectorAll('.page_num')[0];
            pagination.innerHTML = "";
            let ul = document.createElement('ul');
            for (let n = 1; n <= pageNum; n++) {
                let li = document.createElement('li');
                li.innerHTML = n;
                li.setAttribute('page', n);
                li.addEventListener('click', tablePagination);
                ul.appendChild(li);
            }
            pagination.appendChild(ul);
            pagination.querySelectorAll('li')[0].click();
        }

        function tablePagination() {
            try {
                const page = this.getAttribute('page');
                const container = this.parentNode.closest('.table-container');
                const table = container.querySelectorAll('table')[0];
                let start = (((maxRow * page) - maxRow) + 1);
                let end = maxRow * page;
                let n = 1;

                container.querySelectorAll('.page_num ul li').forEach((x) => {
                    x.classList.remove('active');
                });
                this.classList.add('active');
                table.querySelectorAll('tbody tr').forEach((x) => {
                    if (n >= start && n <= end) {
                        x.classList.remove('hide');
                    } else {
                        x.classList.add('hide');
                    }
                    n++;
                });
                limitPagination(container);
                container.querySelectorAll('.btn-pagination').forEach((btn) => {
                    btn.addEventListener('click', togglePagination);
                });
            } catch (e) {
                console.log(e);
            }
        }

        function togglePagination() {
            const container = this.parentNode.closest('.table-pagination');
            const current = parseInt(container.querySelectorAll('.page_num li.active')[0].getAttribute('page'));
            max = container.querySelectorAll('.page_num li').length;
            var point;
            if (this.getAttribute('page') == 'next') {
                point = current == max ? current : parseInt(current + 1);
            } else {
                point = current == 1 ? point : parseInt(current - 1);
            }
            container.querySelectorAll('.page_num li')[parseInt(point - 1)].click();
        }

        // Set the table pagination limited to 5
        function limitPagination(container) {
            const allPages = container.querySelectorAll('.page_num ul li');
            var currentPage = parseInt(container.querySelectorAll('.page_num ul .active')[0].getAttribute('page'));
            var start = 1;
            var end = 5;
            var between = 2;
            if (allPages.length > 5) {
                if (currentPage > 3) {
                    end = currentPage + 2;
                    while (end > allPages.length) {
                        end--;
                        between++;
                    }
                    start = currentPage - between;
                }
                let count = 1;
                allPages.forEach((x) => {
                    if (count >= start && count <= end) {
                        x.classList.remove('hide');
                    } else {
                        x.classList.add('hide');
                    }
                    count++;
                });
            } else {
                return;
            }
        }
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('new_assets/css/table.css') }}" />
    <link rel="stylesheet" href="{{ asset('new_assets/css/history.css') }}" />
@endpush
