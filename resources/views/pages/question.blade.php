@extends('layouts.base')
@section('title', 'Questions')
@section('main', 'Accounts Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('edit'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('edit') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('delete'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session()->get('delete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mt-4 mb-2">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            All Question


                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                    <div id="DataTables_Table_0_filter" class="dataTables_filter searchinput">



                                    </div>


                                    <div class="dt-buttons btn-group flex-wrap ">
                                        <button class="btn btn-secondary add-new btn-primary mx-3" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                            data-bs-target="#addNewBus"><span><i
                                                    class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                                    class="d-none d-sm-inline-block">Add New Question</span></span></button>
                                    </div>

                                </div>

                            </div>

                        </div>



                        <table class="table border-top dataTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Question</th>
                                    <th>Item</th>


                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="searchResults">
                                @foreach ($questions as $question)
                                    <tr class="odd">


                                        <td class="">{{ $question->question }}</td>
                                        <td class="">{{ $question->item }}</td>



                                        <td class="" style="">
                                            <div class="d-flex align-items-center">
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#editBus{{ $question->id }}"
                                                    class="text-body delete-record"><i class="ti ti-edit"></i></a>

                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $question->id }}"
                                                    class="text-body delete-record">
                                                    <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                </a>


                                            </div>


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                                want to delete
                                                                this Question?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After deleting this Theme you cannot use
                                                                this Question</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn" data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center"
                                                                        href="{{ route('dashboard-question-delete', $question->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="editBus{{ $question->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Edit Question
                                                            </h5>

                                                        </div>

                                                        <form
                                                            action="{{ route('dashboard-question-edit', $question->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Question
                                                                        </label>
                                                                        <input type="text" id="nameWithTitle"
                                                                            name="question"
                                                                            value="{{ $question->question }}"
                                                                            class="form-control"
                                                                            placeholder="Add question" required />
                                                                        <span class="text-danger mt-1">
                                                                            @error('question')
                                                                                {{ $message }}
                                                                            @enderror
                                                                        </span>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Item</label>
                                                                        <textarea id="" name="item" class="form-control" rows="3" placeholder="Item" required>{{ $question->item }}</textarea>
                                                                    </div>

                                                                </div>


                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-label-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Edit
                                                                    Question</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach



                            </tbody>


                        </table>

                        <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCenterTitle">Add New Question</h5>

                                    </div>

                                    <form id="addBusForm" action="{{ route('dashboard-question-add') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Question</label>
                                                    <input type="text" id="nameWithTitle" name="question"
                                                        class="form-control" placeholder="Add Qeustion" required />
                                                    <span class="text-danger mt-1">
                                                        @error('question')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Item</label>
                                                    <input type="text" name="item" class="form-control" value="" data-role="tagsinput" />

                                                </div>

                                            </div>

                                          

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary"
                                                data-bs-dismiss="modal" id="closeButton">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Add Question</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Offcanvas to add new user -->








            </div>
        </div>
    @endsection

    @section('script')
        <script>
            // $(document).ready(function() {

            //     console.log('dhsgjdfx')
            //     $('input[name="input"]').tagsinput({
            //         trimValue: true,
            //         confirmKeys: [13, 44, 32],
            //         focusClass: 'my-focus-class'
            //     });

            //     $('.bootstrap-tagsinput input').on('focus', function() {
            //         $(this).closest('.bootstrap-tagsinput').addClass('has-focus');
            //     }).on('blur', function() {
            //         $(this).closest('.bootstrap-tagsinput').removeClass('has-focus');
            //     });

            // });


            $(document).ready(function() {
                $('#closeButton').on('click', function(e) {
                    $('#addBusForm')[0].reset();

                });

            });
        </script>
    @endsection
