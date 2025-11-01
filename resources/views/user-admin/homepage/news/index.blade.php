@extends('layouts.admin', ['title' => 'Manage News'])

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">News & Announcements</h1>
            <p class="text-[14px] text-gray-900/60">Manage news articles and announcements for the website.
            </p>
        </div>
        <button id="create-news-modal-btn"
            class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
            <i class="fi fi-sr-square-plus opacity-70 flex justify-center items-center text-[18px]"></i>
            Add News/Announcement
        </button>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-center flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-full">

            <div class="w-full flex flex-row justify-between items-center gap-4">
                <label for="myCustomSearch"
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                    <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                    <input type="search" name="" id="myCustomSearch"
                        class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                        placeholder="Search by title, content, or status...">
                    <button id="clear-btn"
                        class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                        <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                    </button>
                </label>

                <div class="flex flex-row justify-start items-center w-full gap-2">
                    <div
                        class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-1 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                        <select name="status_filter" id="status_filter"
                            class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                            <option value="" selected disabled>Status</option>
                            <option value="">All</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <table id="newsTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                            </th>
                            <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Title</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/4 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Content</span>
                            </th>
                            <th class="w-1/8 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Type</span>
                            </th>
                            <th class="w-1/8 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Visibility</span>
                            </th>
                            <th class="w-1/8 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/8 text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 cursor-pointer">Published</span>
                                <i class="fi fi-sr-sort text-[12px] text-gray-400"></i>
                            </th>
                            <th class="w-1/8 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                <span class="mr-2 font-medium opacity-60 select-none">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTable will populate this --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create/Edit News Modal --}}
    <x-modal modal_id="news-modal" modal_name="News Article" close_btn_id="news-modal-close-btn"
        modal_container_id="news-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-document flex justify-center items-center'></i>
        </x-slot>

        <form id="news-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="news_id" name="news_id" value="">

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" id="title" name="title" required
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea id="content" name="content" rows="4" required
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"></textarea>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" required
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div>
                <label for="visibility" class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                <select id="visibility" name="visibility" required
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-4 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]">
                    <option value="public">Public (Everyone)</option>
                    <option value="students_only">Students Only</option>
                    <option value="both" selected>Both (Public & Students)</option>
                </select>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" id="is_announcement" name="is_announcement" value="1"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">This is an announcement (not regular news)</span>
                </label>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="news-modal-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="news-form" id="news-submit-btn"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Save
            </button>
        </x-slot>
    </x-modal>
@endsection

@push('scripts')
    <script type="module">
        import {
            clearSearch
        } from "{{ asset('js/clearSearch.js') }}"
        import {
            initModal
        } from "{{ asset('js/modal.js') }}";
        import {
            showAlert
        } from "{{ asset('js/alert.js') }}";
        import {
            showLoader,
            hideLoader
        } from "{{ asset('js/loader.js') }}";

        let newsTable;
        let selectedStatus = '';

        document.addEventListener("DOMContentLoaded", function() {
            initModal('news-modal', 'create-news-modal-btn', 'news-modal-close-btn', 'news-modal-cancel-btn',
                'news-modal-container');

            // Debug: Check if modal button exists
            const modalBtn = document.getElementById('create-news-modal-btn');

            if (modalBtn) {
                modalBtn.addEventListener('click', function() {
                    // Reset form when opening modal
                    document.getElementById('news-form').reset();
                    document.getElementById('news_id').value = '';
                    document.getElementById('news-submit-btn').textContent = 'Save';
                });
            }

            const customSearch = document.getElementById("myCustomSearch");
            const statusFilter = document.getElementById("status_filter");

            // Initialize DataTable
            newsTable = new DataTable('#newsTable', {
                paging: true,
                searching: true,
                autoWidth: false,
                serverSide: true,
                processing: true,
                ajax: {
                    url: '/admin/getNews',
                    data: function(d) {
                        d.status_filter = selectedStatus;
                    }
                },
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        width: '5%',
                        targets: 0,
                        className: 'text-center'
                    },
                    {
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '20%',
                        targets: 2
                    },
                    {
                        width: '12%',
                        targets: 3,
                        className: 'text-center'
                    },
                    {
                        width: '12%',
                        targets: 4,
                        className: 'text-center'
                    },
                    {
                        width: '12%',
                        targets: 5,
                        className: 'text-center'
                    },
                    {
                        width: '12%',
                        targets: 6,
                        className: 'text-center'
                    },
                    {
                        width: '12%',
                        targets: 7,
                        className: 'text-center'
                    }
                ],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                columns: [{
                        data: 'index'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'content'
                    },
                    {
                        data: 'type',
                        render: function(data, type, row) {
                            const typeClass = data === 'Announcement' ? 'bg-red-100 text-red-800' :
                                'bg-blue-100 text-blue-800';
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${typeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'visibility',
                        render: function(data, type, row) {
                            const visibilityClass = data === 'public' ?
                                'bg-gray-100 text-gray-800' :
                                data === 'students_only' ? 'bg-green-100 text-green-800' :
                                'bg-blue-100 text-blue-800';
                            const visibilityText = data === 'public' ? 'Public' :
                                data === 'students_only' ? 'Students Only' :
                                'Both';
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${visibilityClass}">${visibilityText}</span>`;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            const statusClass = data === 'published' ?
                                'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                            return `<span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'published_at'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class='flex flex-row justify-center items-center gap-2'>
                                    <button onclick="editNews(${data})" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rs-edit text-[16px]"></i>
                                    </button>
                                    <button onclick="deleteNews(${data})" class="group relative inline-flex items-center gap-2 bg-red-100 text-red-500 font-semibold px-3 py-1 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                        <i class="fi fi-rs-trash text-[16px]"></i>
                                    </button>
                                </div>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            customSearch.addEventListener("input", function() {
                newsTable.search(this.value).draw();
            });

            clearSearch('clear-btn', 'myCustomSearch', newsTable);

            statusFilter.addEventListener('change', (e) => {
                selectedStatus = e.target.value;
                newsTable.draw();
            });

            // Style table rows
            newsTable.on('draw', function() {
                let rows = document.querySelectorAll('#newsTable tbody tr');
                rows.forEach(function(row) {
                    row.classList.add('hover:bg-gray-200', 'transition', 'duration-150');
                    let cells = row.querySelectorAll('td');
                    cells.forEach(function(cell) {
                        cell.classList.add('px-4', 'py-1', 'text-start', 'font-regular',
                            'text-[14px]', 'opacity-80', 'truncate', 'border-t',
                            'border-[#1e1e1e]/10', 'font-semibold');
                    });
                });
            });

            newsTable.on("init", function() {
                const defaultSearch = document.querySelector("#dt-search-0");
                if (defaultSearch) {
                    defaultSearch.remove();
                }
            });

            // Form submission
            document.getElementById('news-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);
                const newsId = document.getElementById('news_id').value;
                const isEdit = newsId !== '';

                // Basic validation
                const title = formData.get('title');
                const content = formData.get('content');
                const status = formData.get('status');
                const visibility = formData.get('visibility');

                if (!title || !content || !status || !visibility) {
                    showAlert('error', 'Please fill in all required fields');
                    return;
                }


                showLoader(isEdit ? "Updating..." : "Creating...");

                const url = '/admin/news';
                const method = 'POST';

                fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            showAlert('success', data.success);
                            closeModal();
                            form.reset();
                            document.getElementById('news_id').value = '';
                            newsTable.draw();
                        } else if (data.error) {
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        showAlert('error', 'Something went wrong: ' + err.message);
                    });
            });
        });

        // Global functions for edit and delete
        window.editNews = function(id) {
            fetch(`/admin/news/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('news_id').value = data.id;
                    document.getElementById('title').value = data.title;
                    document.getElementById('content').value = data.content;
                    document.getElementById('status').value = data.status;
                    document.getElementById('visibility').value = data.visibility || 'both';
                    document.getElementById('is_announcement').checked = data.is_announcement || false;
                    document.getElementById('news-submit-btn').textContent = 'Update';
                    openModal();
                })
                .catch(err => {
                    showAlert('error', 'Failed to load news data');
                });
        };

        window.deleteNews = function(id) {
            if (confirm('Are you sure you want to delete this news article?')) {
                showLoader("Deleting...");

                fetch(`/admin/news/${id}`, {
                        method: 'DELETE',
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            showAlert('success', data.success);
                            newsTable.draw();
                        } else if (data.error) {
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        showAlert('error', 'Something went wrong');
                    });
            }
        };

        function closeModal() {
            let modal = document.querySelector('#news-modal');
            let body = document.querySelector('#news-modal-container');

            if (modal && body) {
                modal.classList.remove('opacity-100', 'scale-100');
                modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                body.classList.remove('opacity-100');
                body.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        function openModal() {
            let modal = document.querySelector('#news-modal');
            let body = document.querySelector('#news-modal-container');

            if (modal && body) {
                modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
                body.classList.remove('opacity-0', 'pointer-events-none');
                body.classList.add('opacity-100');
            }
        }
    </script>
@endpush
