<x-custom.app-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $title }}
        </x-slot>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <x-slot:headerFiles>
            <!--  BEGIN CUSTOM STYLE FILE  -->
            @vite(['resources/scss/light/assets/components/modal.scss'])
            @vite(['resources/scss/dark/assets/components/modal.scss'])
            <link rel="stylesheet" href="{{ asset('plugins/animate/animate.css') }}">
            @vite(['resources/scss/light/assets/elements/alert.scss'])
            @vite(['resources/scss/dark/assets/elements/alert.scss'])
            <link rel="stylesheet" href="{{ asset('plugins/sweetalerts2/sweetalerts2.css') }}">
            @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
            @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])
            <link rel="stylesheet" href="{{ asset('plugins/table/datatable/datatables.css') }}">
            @vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
            @vite(['resources/scss/dark/plugins/table/datatable/dt-global_style.scss'])
            @vite(['resources/scss/light/plugins/editors/quill/quill.snow.scss'])
            @vite(['resources/scss/dark/plugins/editors/quill/quill.snow.scss'])

            <style>
                td,
                th {
                    border-radius: 0px !important;
                }

                a.text-danger {
                    transition: color 0.3s ease;
                }

                a.text-danger:hover {
                    color: #dc3545;
                }

                .icon-trash {
                    width: 30px;
                    height: 30px;
                    color: #dc3545;
                }

                .select2-container--open {
                    z-index: 999999 !important;
                }
            </style>
            <!--  END CUSTOM STYLE FILE  -->
            </x-slot>
            <!-- END GLOBAL MANDATORY STYLES -->

            <x-slot:scrollspyConfig>
                data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
                </x-slot>

                <div class="row layout-top-spacing">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div style="min-height:50vh;" class="widget-content widget-content-area">
                                <div class="p-3 container">
                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close"><i data-feather="x-circle"></i></button>
                                    </div>
                                    @endif
                                    @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close"><i data-feather="x-circle"></i></button>
                                    </div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-center justify-content-sm-start">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary btn-md w-20 ms-4" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                        Input Barang Aset
                                    </button>
                                </div>

                                <div class="table-responsive px-4">
                                    <table id="zero-config" class="table table-bordered">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th scope="col" style="width:40px;">No.</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Kategori</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assetItems as $assetItem)
                                            <tr>
                                                <td style="width:40px;">{{ $loop->iteration }}</td>
                                                <td>{{ $assetItem->name ?? '-' }}</td>
                                                <td>{{ $assetItem->category === 'NonIT' ? 'Non IT' : 'IT' }}</td>
                                                <td class="text-center ">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="openEditModal({{ $assetItem->id }}, '{{ $assetItem->name }}', '{{ $assetItem->category }}','{{ $assetItem->description }}')">
                                                        <i class="text-white" data-feather="edit-2"></i>
                                                    </button>

                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" role="button" onclick="confirmDelete({{ $assetItem->id }});">
                                                        <i class="text-white" data-feather="trash-2"></i>
                                                    </a>
                                                    <!-- Hidden form for delete request -->
                                                    <form id="delete-form-{{ $assetItem->id }}" action="{{ route('asset_item.destroy', $assetItem->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Input Barang Aset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('asset_item.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group d-flex align-items-center my-2">
                                        <button type="button" id="add-asset_item" class="btn btn-sm btn-primary py-0 px-2">
                                            <i data-feather="plus"></i>
                                        </button>
                                        <h2 class="ms-2 py-0 mb-0 h5">Barang Aset</h2>
                                    </div>

                                    <div id="asset_item-inputs" class="mt-2">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">1.</span>
                                            <input type="text" name="asset_item_name[]" class="form-control" style="width: 22.5% !important;flex:0 1 auto !important" placeholder="Nama barang">
                                            <div class="border p-2">
                                                <p class="text-dark ms-2 mb-1">Pilih Kategori</p>
                                                <div class="form-check form-check-inline mx-2">
                                                    <input checked class="form-check-input" type="radio" name="asset_item_category[0]" id="asset_category_1" value="IT">
                                                    <label class="form-check-label" for="asset_category_1">IT</label>
                                                </div>
                                                <div class="form-check form-check-inline mx-2">
                                                    <input class="form-check-input" type="radio" name="asset_item_category[0]" id="asset_category_2" value="NonIT">
                                                    <label class="form-check-label" for="asset_category_2">Non IT</label>
                                                </div>
                                            </div>
                                            <input type="text" name="asset_item_description[]" class="form-control" placeholder="Deskripsi">
                                            <button type="button" class="btn btn-danger remove-asset_item">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button class="btn btn-success text-center align-items-center mt-1 mt-2 py-auto" type="submit">
                                        <i data-feather="save"></i><span class="icon-name ms-1">Simpan</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalTitle">Edit Barang Aset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" action="" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <label>Barang Aset</label>
                                        <input type="text" id="asset_item_name" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group my-2">
                                        <p class="text-dark ms-2 mb-1">Pilih Kategori</p>
                                        <div class="form-check form-check-inline mx-2">
                                            <input class="form-check-input" type="radio" name="category" id="asset_category_1" value="IT">
                                            <label class="form-check-label" for="asset_category_1">IT</label>
                                        </div>
                                        <div class="form-check form-check-inline mx-2">
                                            <input class="form-check-input" type="radio" name="category" id="asset_category_2" value="NonIT">
                                            <label class="form-check-label" for="asset_category_2">Non IT</label>
                                        </div>
                                    </div>
                                    <textarea name="description" hidden></textarea>
                                    <div class="form-group p-3 rounded shadow-sm bg-light ">
                                        <div id="description-editor" class="mt-2 overflow-scroll" style="max-height: 400px;">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  BEGIN CUSTOM SCRIPTS FILE  -->
                <x-slot:footerFiles>
                    <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
                    <script src="{{ asset('plugins/editors/quill/quill.js') }}"></script>
                    <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
                    <script src="{{ asset('plugins/table/datatable/datatables.js') }}"></script>
                    <script type="module" src="{{ asset('plugins/editors/quill/quill.js') }}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/button-ext/dataTables.buttons.min.js')}}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/button-ext/jszip.min.js')}}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/button-ext/buttons.html5.min.js')}}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/button-ext/buttons.print.min.js')}}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/pdfmake/pdfmake.min.js')}}"></script>
                    <script src="{{asset('plugins-rtl/table/datatable/pdfmake/vfs_fonts.js')}}"></script>
                    <script>
                        var quill = new Quill('#description-editor', {
                            modules: {
                                toolbar: [
                                    [{
                                        header: [1, 2, false]
                                    }],
                                    ['bold', 'italic', 'underline'],
                                    ['image', 'code-block']
                                ]
                            },
                            placeholder: 'Masukkan deskripsi barang aset (jika ada)',
                            theme: 'snow' // or 'bubble'
                        });

                        function openEditModal(id, name, category, description) {
                            $('#edit-form input[name=category][value=' + category + ']').prop('checked', true)
                            quill.root.innerHTML = description;
                            $('#edit-form textarea[name=description]').val(description)

                            quill.on('editor-change', function(eventName, ...args) {
                                if (eventName === 'text-change') {
                                    $('#edit-form textarea[name=description]').val(quill.root.innerHTML)
                                }
                            });

                            // Populate the form fields
                            document.getElementById('asset_item_name').value = name;
                            // document.getElementById('asset_item_category').value = category;

                            // Update the form action URL
                            document.getElementById('edit-form').action = '/admin/pengaturan/barang-aset/' + id;

                            // Show the modal
                            new bootstrap.Modal(document.getElementById('editModal')).show();
                        }

                        window.addEventListener('load', function() {
                            feather.replace();
                        })

                        function confirmDelete(id) {
                            Swal.fire({
                                title: 'Anda yakin ingin hapus?',
                                text: "Data tidak dapat dikembalikan!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, hapus!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('delete-form-' + id).submit();
                                }
                            });
                        }

                        function updateNumbering() {
                            const missionInputs = document.querySelectorAll('#asset_item-inputs .input-group');
                            missionInputs.forEach((input, index) => {
                                input.querySelector('.input-group-text').textContent = `${index + 1}.`;
                            });
                        }
                        document.addEventListener('DOMContentLoaded', function() {
                            $('#zero-config').DataTable({
                                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex flex-column flex-sm-row justify-content-center align-items-center justify-content-sm-end mt-sm-0 mt-3'Bf>>>" +
                                    "<'table-responsive'tr>" +
                                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                                "buttons": [{
                                        extend: 'pdfHtml5',
                                        text: 'PDF',
                                        className: 'btn btn-danger', // Warna biru
                                        exportOptions: {
                                            columns: [0, 1, 2] // Indeks kolom yang ingin Anda ekspor (dimulai dari 0)
                                        },
                                        filename: function() {
                                            var d = new Date();
                                            var n = d.toISOString();
                                            return 'PDF_Export_' + n;
                                        },
                                        customize: function(doc) {
                                            doc.styles.tableHeader.alignment = 'left'; // Contoh penyesuaian
                                            // Tambahkan kustomisasi pdfmake Anda di sini
                                            doc.content[1].table.widths = ['auto', '*', '*'];
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        text: 'Excel',
                                        className: 'btn btn-success', // Warna biru
                                        exportOptions: {
                                            columns: [0, 1, 2] // Indeks kolom yang ingin Anda ekspor (dimulai dari 0)
                                        },
                                        filename: function() {
                                            var d = new Date();
                                            var n = d.toISOString();
                                            return 'Excel_Export_' + n;
                                        },
                                    }
                                ],
                                "oLanguage": {
                                    "oPaginate": {
                                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                                    },
                                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                                    "sSearchPlaceholder": "Search...",
                                    "sLengthMenu": "Results :  _MENU_",
                                },
                                "drawCallback": function(settings) {
                                    feather.replace();
                                },
                                "stripeClasses": [],
                                "lengthMenu": [7, 10, 20, 50],
                                "pageLength": 10
                            });
                            const assetItemContainer = document.getElementById('asset_item-inputs');

                            document.getElementById('add-asset_item').addEventListener('click', function() {
                                const index = assetItemContainer.querySelectorAll('.input-group').length;
                                const newInputGroup = `
        <div class="input-group mb-2">
            <span class="input-group-text">${index}.</span>
            <input type="text" name="asset_item_name[]" class="form-control" style="width: 22.5% !important;flex:0 1 auto !important" placeholder="Nama barang"><div class="border p-2"><p class="text-dark ms-2 mb-1">Pilih Kategori</p><div class="form-check form-check-inline mx-2"><input checked class="form-check-input" type="radio" name="asset_item_category[${index}]" id="category-radio-${index}" value="IT"><label class="form-check-label" for="category-radio-${index}">IT</label></div><div class="form-check form-check-inline mx-2"><input class="form-check-input" type="radio" name="asset_item_category[${index}]" id="category-radio-${index}" value="NonIT"><label class="form-check-label" for="category-radio-${index}">Non IT</label></div></div>
            <input type="text" name="asset_item_description[]" class="form-control" placeholder="Deskripsi">
            <button type="button" class="btn btn-danger remove-asset_item">
                <i data-feather="trash"></i>
            </button>
        </div>`;
                                assetItemContainer.insertAdjacentHTML('beforeend', newInputGroup);
                                feather.replace();
                            });

                            assetItemContainer.addEventListener('click', function(event) {
                                if (event.target.classList.contains('remove-asset_item')) {
                                    event.target.closest('.input-group').remove();
                                    updateNumbering();
                                }
                            });

                            function updateNumbering() {
                                const inputGroups = assetItemContainer.querySelectorAll('.input-group');
                                inputGroups.forEach((group, index) => {
                                    group.querySelector('.input-group-text').textContent = `${index + 1}.`;
                                });
                            }
                        });
                    </script>
                    </x-slot>
                    <!--  END CUSTOM SCRIPTS FILE  -->
                    </x-base-layout>