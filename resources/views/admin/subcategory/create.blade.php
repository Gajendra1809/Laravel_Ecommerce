@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="subCategoryForm" id="subCategoryForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Show On Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')
    <script>
        $("#subCategoryForm").submit(function(event) {
            event.preventDefault();
            var element = $("#subCategoryForm");
            $.ajax({
                url: "{{ route('sub-categories.store') }}",
                type: "post",
                data: element.serialize(),
                datatype: 'json',
                success: function(response) {
                    if (response.status) {
                        // Store the flash message in sessionStorage
                        sessionStorage.setItem('flashMessage', response.message);
                        window.location.href = response.redirect;
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.name);
                        } else {
                            $('#name').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                        if (errors.slug) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.slug);
                        } else {
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                        if (errors.category_id) {
                            $('#category_id').addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.category_id);
                        } else {
                            $('#category_id').removeClass('is-invalid').siblings('p').removeClass(
                                'invalid-feedback').html("");
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong");
                }
            });
        });
        $("#name").change(function() {
            var element = $(this);
            $.ajax({
                url: "{{ route('getSlug') }}",
                type: 'get',
                data: {
                    title: element.val()
                },
                datatype: 'json',
                success: function(response) {
                    if (response.status) {
                        $("#slug").val(response.slug);
                    }
                }
            });
        });
    </script>
@endsection
