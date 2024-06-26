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
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />


        <style>
            td,
            th {
                border-radius: 0px !important;
            }

            td {
                white-space: normal !important;
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

            .table-responsive>nav>div>div>span {
                display: none
            }

            .table-responsive>nav>div>div>p {
                margin-top: 15px
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
                                <button type="button" class="btn-close text-white" data-bs-dismiss="alert"
                                    aria-label="Close"><i data-feather="x-circle"></i></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close text-white" data-bs-dismiss="alert"
                                    aria-label="Close"><i data-feather="x-circle"></i></button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center px-4 center-input-button">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter">
                            Input Sasaran Kegiatan
                        </button>
                    </div>

                    <div class="container px-3">
                        <table id="performanceIndicator-table" class="table table-bordered table-hover">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th scope="col" style="width:40px;">No.</th>
                                    <th scope="col">Indikator Kinerja Sasaran Kegiatan</th>
                                    <th scope="col">Sasaran Kegiatan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach ($programTargetsHasPerformanceIndicators as $programTarget)
                                    @foreach ($programTarget->performanceIndicators as $performanceIndicator)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $programTarget->name }}</td>
                                            <td>{{ $performanceIndicator->name }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <a type="button" class="btn btn-sm btn-warning mx-1"
                                                        onclick="openEditModal({{ $performanceIndicator->id }}, '{{ $performanceIndicator->name }}', '{{ $programTarget->id }}', '{{ $programTarget->name }}')">
                                                        <i class="text-white" data-feather="edit-2"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm mx-1"
                                                        role="button"
                                                        onclick="confirmDelete({{ $performanceIndicator->id }});">
                                                        <i class="text-white" data-feather="trash-2"></i>
                                                    </a>
                                                    <!-- Hidden form for delete request -->
                                                    <form id="delete-form-{{ $performanceIndicator->id }}"
                                                        action="{{ route('performance_indicator.delete', $performanceIndicator->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Input Sasaran Kegiatan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18">
                            </line>
                            <line x1="6" y1="6" x2="18" y2="18">
                            </line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('performance_indicator.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="program_target"><b>Indikator Kinerja Sasaran Program</b></label>
                            <select id="program_target" name="program_target_id" class="form-control select2">
                                <option value="">Pilih Indikator Kinerja Sasaran Program</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group d-flex align-items-center mb-2">
                            <button type="button" id="add-performance_indicator"
                                class="btn btn-sm btn-primary py-0 px-2">
                                <i data-feather="plus"></i>
                            </button>
                            <label for="performance_indicator" class="ms-2 py-0 mb-0">Sasaran Kegiatan</label>
                        </div>
                        <div id="performance_indicator-inputs" class="mb-4">
                            <div class="input-group mb-2">
                                <span class="input-group-text">1.</span>
                                <input type="text" name="performance_indicator[]" class="form-control">
                                <button type="button" class="btn btn-danger remove-performance_indicator">
                                    <i data-feather="trash"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-success text-center align-items-center float-end" type="submit">
                            <i data-feather="save" class="me-2"></i><span class="icon-name">Simpan</span>
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Edit Sasaran Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18">
                            </line>
                            <line x1="6" y1="6" x2="18" y2="18">
                            </line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" action="" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label><b>Indikator Kinerja Sasaran Program</b></label>
                            <select id="program_target_edit" name="program_target" class="form-control select2"
                                disabled>
                                <option value="">Pilih Indikator Kinerja Sasaran Program</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label><b>Sasaran Kegiatan</b></label>
                            <input type="text" id="performance_indicator_name" name="name"
                                class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning float-end">Update</button>
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
        <script src="{{ asset('plugins-rtl/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins-rtl/table/datatable/button-ext/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins-rtl/table/datatable/button-ext/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins-rtl/table/datatable/button-ext/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins-rtl/table/datatable/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins-rtl/table/datatable/pdfmake/vfs_fonts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function openEditModal(id, name, programTargetId, programTargetName) {
                // Populate the form fields
                document.getElementById('performance_indicator_name').value = name;

                // Populate the dropdown and set the selected value
                const programTargetEdit = document.getElementById('program_target_edit');
                programTargetEdit.innerHTML = `<option value="${programTargetId}" selected>${programTargetName}</option>`;

                // Set the form action
                document.getElementById('edit-form').action = '/admin/perkin/sasaran-kegiatan/' + id + '/update';

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
                const missionInputs = document.querySelectorAll('#performance_indicator-inputs .input-group');
                missionInputs.forEach((input, index) => {
                    input.querySelector('.input-group-text').textContent = `${index + 1}.`;
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                $('#performanceIndicator-table').DataTable({
                    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex flex-column flex-sm-row justify-content-center align-items-center justify-content-sm-end mt-sm-0 mt-3'Bf>>>" +
                        "<'table-responsive'tr>" +
                        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "buttons": [{
                            text: 'PDF',
                            className: 'buttons-pdf buttons-html5 btn btn-danger',
                            action: function(e, dt, node, config) {
                                window.location.href =
                                    "{{ route('download.performance-indicator.pdf') }}";
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
                                return 'Sasaran_Program_' + n;
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

                const missionContainer = document.getElementById('performance_indicator-inputs');

                document.getElementById('add-performance_indicator').addEventListener('click', function() {
                    const newInput = `<div class="input-group mb-2">
                        <span class="input-group-text"></span>
                        <input type="text" name="performance_indicator[]" class="form-control">
                        <button type="button" class="btn btn-danger remove-performance_indicator">
                            <i data-feather="trash"></i>
                        </button>
                        </div>`;
                    missionContainer.insertAdjacentHTML('beforeend', newInput);
                    feather.replace();
                    updateNumbering();
                });

                missionContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-performance_indicator')) {
                        event.target.closest('.input-group').remove();
                        updateNumbering();
                    }
                });
            });

            $('#exampleModalCenter').on('shown.bs.modal', function() {
                $('#program_target').select2({
                    dropdownParent: $('#exampleModalCenter'),
                    placeholder: 'Pilih Indikator Kinerja Sasaran Program',
                    theme: 'bootstrap-5',
                    ajax: {
                        transport: function(params, success, failure) {
                            // Using Axios to fetch the data
                            axios.get(`{{ route('program_targets.index') }}`, {
                                    params: {
                                        search: params.data.term,
                                    }
                                })
                                .then(function(response) {
                                    // Call the `success` function with the formatted results
                                    success({
                                        results: response.data.map(function(item) {
                                            return {
                                                id: item.id,
                                                text: item.name
                                            };
                                        })
                                    });
                                })
                                .catch(function(error) {
                                    // Call the `failure` function in case of an error
                                    failure(error);
                                });
                        },
                        delay: 250,
                        cache: true
                    }
                });
            }).on('hidden.bs.modal', function() {
                $('#program_target').select2('destroy');
            });

            $('#exampleModalCenter').on('shown.bs.modal', function() {
                $('#program_target_edit').select2({
                    dropdownParent: $('#exampleModalCenter'),
                    placeholder: 'Pilih Indikator Kinerja Sasarab Program',
                    theme: 'bootstrap-5',
                    ajax: {
                        transport: function(params, success, failure) {
                            // Using Axios to fetch the data
                            axios.get(`{{ route('program_targets.index') }}`, {
                                    params: {
                                        search: params.data.term,
                                        limit: 10
                                    }
                                })
                                .then(function(response) {
                                    // Call the `success` function with the formatted results
                                    success({
                                        results: response.data.map(function(item) {
                                            return {
                                                id: item.id,
                                                text: item.name
                                            };
                                        })
                                    });
                                })
                                .catch(function(error) {
                                    // Call the `failure` function in case of an error
                                    failure(error);
                                });
                        },
                        delay: 250,
                        cache: true
                    }
                });

            }).on('hidden.bs.modal', function() {
                $('#program_target_edit').select2('destroy');
            });
        </script>
    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
    </x-base-layout>
