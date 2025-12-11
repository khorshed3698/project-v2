<div class="table-responsive">
    <table class="table table-bordered" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <td></td>
            <td class="bg-yellow" colspan="{{ $viewMode == 'off' ? '5' : '4' }}">Existing  information (Latest BIDA Reg. Info.)</td>
            <td class="bg-green" colspan="4">Proposed information</td>
        </tr>
        <tr>
            @if ($viewMode == 'off')
             <th scope="col"><input type="checkbox" id="select-all-imported"></th>
            @endif
            <th scope="col" class="light-yellow">SL.</th>
            <th scope="col" class="light-yellow">Name of machineries</th>
            <th scope="col" class="light-yellow">Quantity</th>
            <th scope="col" class="light-yellow">Unit prices TK</th>
            <th scope="col" class="light-yellow">Total value (Million) TK</th>

            <th scope="col" class="light-green">Name of machineries</th>
            <th scope="col" class="light-green">Quantity</th>
            <th scope="col" class="light-green">Unit prices TK</th>
            <th scope="col" class="light-green">Total value (Million) TK</th>
            <th scope="col">Action Type</th>
            @if ($viewMode == 'off')
             <th scope="col" style="width: 55px;">Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($importedMachineryData['getData'] as $machineryImported)
            <tr>
                @if ($viewMode == 'off')
                    <td><input type="checkbox" value={{ Encryption::encodeId($machineryImported->id) }} class="row-checkbox"></td>
                @endif
                <td class="light-yellow">{{ $machineryImported->sl }}</td>
                <td class="light-yellow">{{ $machineryImported->l_machinery_imported_name }}</td>
                <td class="light-yellow">{{ $machineryImported->l_machinery_imported_qty }}</td>
                <td class="light-yellow">{{ $machineryImported->l_machinery_imported_unit_price }}</td>
                <td class="light-yellow">{{ $machineryImported->l_machinery_imported_total_value }}</td>

                <td class="light-green">{{ $machineryImported->n_l_machinery_imported_name }}</td>
                <td class="light-green">{{ $machineryImported->n_l_machinery_imported_qty }}</td>
                <td class="light-green">{{ $machineryImported->n_l_machinery_imported_unit_price }}</td>
                <td class="light-green">{{ $machineryImported->n_l_machinery_imported_total_value }}</td>
                <td>
                    @if(!in_array($machineryImported->amendment_type, ['no change']) )
                        <span class="badge">
                        {{ $machineryImported->amendment_type }}
                    </span>
                    @endif
                </td>
                @if ($viewMode == 'off')
                <td>
                    <div style="width: 55px; display: inline-block; text-align: center;">
                        <a class="btn btn-xs btn-success"
                           data-toggle="modal"
                           data-target="#braModal"
                           onclick="openModal(this)"
                           data-action="{{ url('bida-registration-amendment/imported-machinery-edit-form/'.Encryption::encodeId($machineryImported->id)) }}">
                            <i class="far fa-edit"></i>
                        </a>
                        <a class="btn btn-xs btn-danger"
                           onclick="confirmDelete('{{ url('bida-registration-amendment/imported-machinery-delete/'.Encryption::encodeId($machineryImported->id)) }}', 'imported_machinery')">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </div>
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot align="right">
        <tr>
            <th scope="col" class="light-yellow" colspan="{{ $viewMode == 'off' ? '5' : '4' }}" style="text-align: right">Total:</th>
            <th scope="col" class="light-yellow">{{ number_format($importedMachineryData['ex_imported_machinery_total'], 5) }}</th>

            <th scope="col" class="light-green" colspan="3" style="text-align: right">Total:</th>
            <th scope="col" class="light-green">{{ number_format($importedMachineryData['pro_imported_machinery_total'], 5) }}</th>
        </tr>
        <tr>
            <td colspan="9"><strong>Grand Total:</strong></td>
            <td style="text-align: left;">
                <strong id="grand_imported_machinery">{{ number_format($importedMachineryData['grand_total'],5,'.','') }}</strong>
            </td>
        </tr>
        </tfoot>
    </table>
</div>

<script>
        // Select all checkboxes imported machinery
    $('#select-all-imported').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $('.row-checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.row-checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    $('#batch-delete').off('click').on('click', function() {
        var selectedIds = [];

        // Collect all selected checkbox values
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        // Check if any checkboxes are selected
        if (selectedIds.length === 0) {
            swal({
                type: 'warning',
                title: 'No items selected',
                text: 'Please select at least one item to delete.'
            });
            return false;
        }

        // Confirm deletion
        var confirmDelete = window.confirm('Are you sure you want to delete the selected items?');
        if (confirmDelete) {
            // Perform the AJAX request
            $.ajax({
                url: '/bida-registration-amendment/batch-delete-imported-machinery',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    ids: selectedIds
                },
                success: function(response) {
                    if (response.responseCode == 1) {
                        swal({
                            type: 'success',
                            title: 'Deleted!',
                            text: 'Data has been deleted successfully'
                        });
                        // Reload the data after deletion
                        loadImportedMachineryData(20, 'off');
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });

</script>