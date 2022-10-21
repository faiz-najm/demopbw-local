<!DOCTYPE html>
<html>
<head>
    <title>Laravel Ajax CRUD Tutorial Example - ItSolutionStuff.com</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Laravel Ajax CRUD Tutorial Example</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewEmployee"> Create New Employee</a>
    <table class="table table-bordered data-table">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Gaji</th>
            <th>Umur</th>
            <th>Posisi</th>
            <th>No HP</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="employeeForm" name="employeeForm" class="form-horizontal">
                    <input type="hidden" name="employee_id" id="employee_id">
                    <div class="form-group">
                        <label for="nama" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Name"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gaji" class="col-sm-2 control-label">Gaji</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="gaji" name="gaji" placeholder="Enter Name"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="umur" class="col-sm-2 control-label">Umur</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="umur" name="umur" placeholder="Enter Umur"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="posisi" class="col-sm-2 control-label">Posisi</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Enter Posisi"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nohp" class="col-sm-2 control-label">No HP</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Enter No HP"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
    $(function () {

        /*------------------------------------------
         --------------------------------------------
         Pass Header Token
         --------------------------------------------
         --------------------------------------------*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Render DataTable
        --------------------------------------------
        --------------------------------------------*/
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees-ajax-crud.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'nama', name: 'nama'},
                {data: 'gaji', name: 'gaji'},
                {data: 'umur', name: 'umur'},
                {data: 'posisi', name: 'posisi'},
                {data: 'nohp', name: 'nohp'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        /*------------------------------------------
        --------------------------------------------
        Click to Button
        --------------------------------------------
        --------------------------------------------*/
        $('#createNewEmployee').click(function () {
            $('#saveBtn').val("create-employee");
            $('#employee_id').val('');
            $('#employeeForm').trigger("reset");
            $('#modelHeading').html("Create New Employee");
            $('#ajaxModel').modal('show');
        });

        /*------------------------------------------
        --------------------------------------------
        Click to Edit Button
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.editEmployee', function () {
            var employee_id = $(this).data('id');
            $.get("{{ route('employees-ajax-crud.index') }}" + '/' + employee_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Employee");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#employee_id').val(data.id);
                $('#nama').val(data.nama);
                $('#gaji').val(data.gaji);
                $('#umur').val(data.umur);
                $('#posisi').val(data.posisi);
                $('#nohp').val(data.no_hp);
            })
        });

        /*------------------------------------------
        --------------------------------------------
        Create Employee Code
        --------------------------------------------
        --------------------------------------------*/
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#employeeForm').serialize(),
                url: "{{ route('employees-ajax-crud.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {

                    $('#employeeForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        Delete Employee Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deleteEmployee', function () {

            var employee_id = $(this).data("id");
            confirm("Are You sure want to delete !");

            $.ajax({
                type: "DELETE",
                url: "{{ route('employees-ajax-crud.store') }}" + '/' + employee_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    });
</script>
</html>
