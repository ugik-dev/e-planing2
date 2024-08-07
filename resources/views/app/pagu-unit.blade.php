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
        <style>
            .table-hover tbody tr:hover {
                background-color: #f5f5f5;
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <a href="{{ route('unit_budget.excel', $paguLembaga->year) }}"
                                class="btn btn-success btn-md me-2">Export Excel</a>
                            {{-- <button id="save-unit_budgets" class="btn btn-primary btn-md">Simpan Data</button> --}}
                            <button id="save-unit_budgets" class="btn btn-primary btn-md">Simpan Data</button>
                        </div>
                        <div class="table-responsive my-4">
                            <table id="unit_budget-table" class="table table-bordered ">
                                <thead class="bg-light">
                                    <tr class="text-center">
                                        <th scope="col" style="width:40px;">No.</th>
                                        <th scope="col">Unit Kerja</th>
                                        <th scope="col">Pagu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0 @endphp
                                    @forelse ($workUnits as $unitBudget)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="form-group my-auto">
                                                    <input hidden name="work_unit_id" class="work_unit_id"
                                                        value="{{ $unitBudget->id }}">
                                                    {{ $unitBudget->name }}
                                                </div>
                                            </td>
                                            <td class="pagu text-center counter-factory">
                                                {{ $unitBudget->paguUnit[0]->nominal ?? '0' }}
                                            </td>
                                            @php $total += $unitBudget->paguUnit[0]->nominal ?? '0'  @endphp
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr>
                                        <td colspan="2"><b>TOTAL</b></td>
                                        <td class="text-center" id="total_pagu">
                                            Rp {{ number_format($total, 0, ',', '.') ?? '0' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--  BEGIN CUSTOM SCRIPTS FILE  -->
        <x-slot:footerFiles>
            <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
            <script src="{{ asset('plugins/editors/quill/quill.js') }}"></script>
            <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
            <script src="{{ asset('plugins-rtl/input-mask/jquery.inputmask.bundle.min.js') }}"></script>
            {{-- <script src="{{ asset('plugins-rtl/input-mask/input-mask.js') }}"></script> --}}
            <script>
                var workUnits = @json($workUnits);
                var selectedValues = [...getSelectedWorkUnitIds()]; // Array to keep track of selected values
                function getSelectedWorkUnitIds() {
                    // Fetch all the selected work unit ids from the table
                    return Array.from(document.querySelectorAll('#unit_budget-table .select-work_unit'))
                        .map(select => select.value) // get the values
                        .filter(value => value !== ''); // exclude empty (unselected) values
                }

                function handleSelectChange() {
                    var selectedValue = this.value;
                    selectedValues.push(selectedValue); // Add the selected value to the array

                    var allSelects = document.querySelectorAll('.select-work_unit');
                    allSelects.forEach(function(select) {
                        if (select !== this) {
                            for (var i = 0; i < select.options.length; i++) {
                                if (select.options[i].value === selectedValue) {
                                    select.remove(i);
                                }
                            }
                        }
                    }.bind(this));
                }

                function makeEditable(e) {
                    if (e.target.classList.contains('pagu')) {
                        var currentValue = e.target.innerHTML.replace(/[Rp. ,]/g, ''); // Remove existing formatting
                        e.target.innerHTML = '<input class="form-control text-center pagu-mask" type="text" value="' +
                            currentValue + '" onBlur="updatePagu(this)" />';
                        var inputElement = e.target.firstChild;
                        inputElement.focus();

                        // Apply Inputmask for Rupiah formatting
                        $(inputElement).inputmask({
                            alias: 'numeric',
                            groupSeparator: '.',
                            radixPoint: ',',
                            digits: 2,
                            autoGroup: true,
                            prefix: 'Rp ', // Space after Rp
                            rightAlign: false,
                            removeMaskOnSubmit: true,
                            unmaskAsNumber: true
                        });
                    }
                }


                function updatePagu(inputElement) {
                    // Get unmasked value
                    var unmaskedValue = $(inputElement).inputmask('unmaskedvalue');
                    var formattedValue = 'Rp ' + new Intl.NumberFormat('id-ID').format(unmaskedValue);
                    var cell = inputElement.parentElement;
                    cell.innerHTML = formattedValue;
                    // hitungPagu();
                    console.log(hitungPagu())
                }

                function hitungPagu() {
                    var total = 0;
                    $('.counter-factory').each(function() {
                        var valueText = $(this).text();
                        var numericValue = parseInt(valueText.replace(/[^0-9]/g, ''), 10);
                        total += numericValue;
                    });
                    var formattedValue = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    console.log(formattedValue)
                    $('#total_pagu').html(formattedValue)
                    return total;
                }

                function saveUnitBudgets() {
                    var table = document.getElementById('unit_budget-table');
                    var rows = table.querySelectorAll('tbody tr');
                    var unitBudgets = [];

                    rows.forEach(function(row) {
                        var unitIdElement = row.querySelector('.work_unit_id');
                        var paguElement = row.querySelector('.pagu');

                        if (unitIdElement && paguElement) {
                            unitBudgets.push({
                                pagu_lembaga_id: '{{ $paguLembaga->id }}',
                                work_unit_id: unitIdElement.value,
                                pagu: paguElement.textContent.replace(/[Rp. ,]/g, '')
                            });
                        }
                    });

                    // POST request using Axios
                    axios.post('{{ route('unit_budget.store') }}', unitBudgets)
                        .then(function() {
                            Swal.fire("Success", "Pagu unit berhasil disimpan.", "success")
                                .then(() => window.location.reload());
                        })
                        .catch(function(error) {
                            Swal.fire("Error", "Error saving data: " + error, "error");
                        });
                }

                document.getElementById('save-unit_budgets').addEventListener('click', saveUnitBudgets);

                window.addEventListener('load', function() {
                    feather.replace();
                })

                document.addEventListener('DOMContentLoaded', function() {
                    $('.pagu').each(function() {
                        var oldPaguText = $(this).text();
                        $(this).text('Rp ' + new Intl.NumberFormat('id-ID').format(oldPaguText))

                    })
                    // $('.addRowButton').on('click', addRow);
                    $('#unit_budget-table').on('click', makeEditable);
                    // var selects = document.querySelectorAll('.select-work_unit');
                    // selects.forEach(function(select) {
                    //     select.addEventListener('change', handleSelectChange);
                    // });

                    document.getElementById('save-unit_budgets').addEventListener('click', saveUnitBudgets);
                });
            </script>
        </x-slot>
        <!--  END CUSTOM SCRIPTS FILE  -->
        </x-base-layout>
