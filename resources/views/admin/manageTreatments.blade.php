@extends('layouts.adminlayout')
@section('content')

    <main id="main">

        <div class="container mt-4">


            <div class="card mb-4" style="margin-top: 100px;">
                <div class="card-body">
                    <!-- Grid row -->
                    <div class="row">
                        <!-- Grid column -->
                        <div class="col-md-12">
                            <h2 class="pt-3 pb-4 text-center font-bold font-up deep-purple-text">All Treatments</h2>
                            <!-- <div class="input-group md-form form-sm form-2 pl-0">
                                    <input class="form-control my-0 py-1 pl-3 purple-border" type="text" placeholder="Search something here..." aria-label="Search">
                                    <span class="input-group-addon waves-effect purple lighten-2" id="basic-addon1"><a><i class="fa fa-search white-text" aria-hidden="true"></i></a></span>
                                </div> -->
                            <p class="text-muted"><a href="{{ route('receptionist.treatment.add') }}"><code>Add
                                        Treatment</code></a></p>
                        </div>
                        <!-- Grid column -->
                    </div>
                    <!-- Grid row -->
                    <!--Table-->
                    <table class="table table-striped">
                        <!--Table head-->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Medicine Name</th>
                                <th>Price</th>
                                <th>Action</th>
                                <!-- <th>Status</th>
                                    <th>Action</th> -->
                            </tr>
                        </thead>
                        <!--Table head-->
                        <!--Table body-->
                        <tbody>
                            @php
                                $currentPage = $treatments->currentPage();
                                $perPage = $treatments->perPage();
                                $startingNumber = ($currentPage - 1) * $perPage + 1;
                            @endphp
                            @foreach ($treatments as $treatment)
                                <tr>
                                    <th scope="row">{{ $loop->index + $startingNumber }}</th>
                                    <td>{{ $treatment->treatmentName }}</td>
                                    <td>{{ $treatment->price }}</td>
                                    <td>
                                        <a href="{{ route('receptionist.treatment.edit', $treatment->id) }}"><button
                                                type="button" class="btn btn-outline-primary">Edit</button></a>
                                        /
                                        <form action="{{ route('receptionist.treatment.delete', $treatment->id) }}"
                                            style="display:inline;" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" name="deleteTreatment" value="Delete"
                                                class="btn btn-danger text-center"></input>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!--Table body-->
                    </table>
                    <!--Table-->
                </div>
            </div>
            <div class="row">
                <div class="pagination custom-style">
                    {{ $treatments->links() }}
                </div>
            </div>
        </div>

    </main>
@endsection
